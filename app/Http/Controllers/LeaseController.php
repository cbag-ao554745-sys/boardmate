<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Lease;
use App\Models\PaymentRecord;
use App\Models\Room;
use App\Models\Tenant;
use App\Http\Requests\StoreLeaseRequest;
use App\Http\Requests\UpdateLeaseRequest;
use Illuminate\Validation\ValidationException;

class LeaseController extends Controller
{
    public function index()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $query = $landlord->leases()->with('room', 'tenants.person')->orderByDesc('created_at');

        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('room', fn($r) => $r->where('room_number', 'like', "%{$search}%"))->orWhereHas('tenants.person', function ($p) use ($search) {
                    $p->whereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            });
        }

        if (($status = request('status')) && $status !== 'all') {
            $query->where('status', $status);
        }

        $leases = $query->paginate(10)->through(function (Lease $lease) {
            $primaryPerson = $lease->tenants->first()?->person;
            $tenantName = $primaryPerson ? trim("{$primaryPerson->first_name} {$primaryPerson->last_name}") : 'N/A';

            return [
                'lease_id' => $lease->lease_id,
                'room_id' => $lease->room->room_id,
                'room_number' => $lease->room->room_number,
                'tenant_name' => $tenantName,
                'start_date' => $lease->start_date->format('M d, Y'),
                'end_date' => $lease->end_date?->format('M d, Y'),
                'monthly_rent' => (float) $lease->monthly_rent,
                'status' => $lease->status,
            ];
        });

        return view('leases.index', compact('leases'));
    }

    public function create()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $rooms = $landlord
            ->rooms()
            ->where('status', 'Available')
            ->orderBy('room_number')
            ->get()
            ->map(
                fn(Room $room) => [
                    'room_id' => $room->room_id,
                    'room_number' => $room->room_number,
                    'monthly_rent' => (float) $room->monthly_rent,
                    'status' => $room->status,
                ],
            )
            ->values()
            ->all();

        $tenants = $landlord
            ->tenants()
            ->with('person')
            ->get()
            ->map(
                fn(Tenant $tenant) => [
                    'tenant_id' => $tenant->tenant_id,
                    'name' => trim("{$tenant->person->first_name} {$tenant->person->last_name}"),
                ],
            )
            ->values()
            ->all();

        return view('leases.create', compact('rooms', 'tenants'));
    }

    public function store(StoreLeaseRequest $request)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        if ($request->tenant_id) {
            $alreadyActive = Lease::whereHas('tenants', fn($q) => $q->where('lease_tenant.tenant_id', $request->tenant_id))->where('status', 'Active')->exists();

            if ($alreadyActive) {
                $person = Tenant::with('person')->find($request->tenant_id)?->person;
                throw ValidationException::withMessages([
                    'tenant_id' => "Tenant {$person?->first_name} {$person?->last_name} already has an active lease.",
                ]);
            }
        }

        $room = Room::findOrFail($request->room_id);

        $lease = Lease::create([
            'room_id' => $request->room_id,
            'landlord_id' => $landlord->landlord_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date ?: null,
            'monthly_rent' => $request->monthly_rent ?? $room->monthly_rent,
            'deposit_amount' => $request->deposit_amount ?? 0,
            'initial_payment' => $request->initial_payment ?? 0,
            'payment_due_day' => $request->payment_due_day ?? 1,
            'status' => 'Active',
        ]);

        if ($request->tenant_id) {
            $lease->tenants()->attach($request->tenant_id, [
                'is_primary_tenant' => true,
                'move_in_date' => $request->start_date,
            ]);
        }

        // Create initial payment record if initial_payment is provided
        $initialPayment = $request->initial_payment ?? 0;
        if ($initialPayment > 0 && $request->tenant_id) {
            // Calculate bills_due_date based on payment_due_day
            $startDate = new \DateTime($request->start_date);
            $dueDate = (clone $startDate)->modify('last day of this month');
            $paymentDueDay = min($request->payment_due_day ?? 1, (int) $dueDate->format('t'));
            $dueDate->setDate($dueDate->format('Y'), $dueDate->format('m'), $paymentDueDay);

            // Determine status based on initial payment vs (rent + deposit)
            $rentAmount = $request->monthly_rent ?? $room->monthly_rent;
            $depositAmount = $request->deposit_amount ?? 0;
            $totalDue = $rentAmount + $depositAmount;

            $status = match (true) {
                $initialPayment >= $totalDue => 'Paid',
                $initialPayment > 0 => 'Partial',
                default => 'Pending',
            };

            PaymentRecord::create([
                'lease_id' => $lease->lease_id,
                'tenant_id' => $request->tenant_id,
                'rent_amount' => $rentAmount,
                'electricity_amount' => 0,
                'water_amount' => 0,
                'other_fees' => $depositAmount,
                'amount_paid' => $initialPayment,
                'status' => $status,
                'bills_due_date' => $dueDate->format('Y-m-d'),
                'date_paid' => $initialPayment > 0 ? now() : null,
            ]);
        }

        $room->update(['status' => 'Occupied']);

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => AuditLog::ACTION_INSERT,
            'table_name' => 'lease',
            'record_id' => $lease->lease_id,
            'description' => "Lease #{$lease->lease_id} created for room {$room->room_number}.",
        ]);

        return redirect()->route('leases.index')->with('success', 'Lease created successfully.');
    }

    public function show(Lease $lease)
    {
        $lease->load('room', 'tenants.person');

        $tenantNames = $lease->tenants->map(fn($t) => trim("{$t->person->first_name} {$t->person->last_name}"))->filter()->implode(', ') ?: 'N/A';

        $tenants = $lease->tenants
            ->map(
                fn($t) => [
                    'tenant_id' => $t->tenant_id,
                    'name' => trim("{$t->person->first_name} {$t->person->last_name}"),
                    'is_primary' => (bool) ($t->pivot->is_primary_tenant ?? false),
                ],
            )
            ->values()
            ->all();

        return view('leases.show', compact('lease', 'tenants', 'tenantNames'));
    }

    public function edit(Lease $lease)
    {
        $lease->load('room', 'tenants.person');
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $rooms = $landlord
            ->rooms()
            ->orderBy('room_number')
            ->get()
            ->map(
                fn(Room $room) => [
                    'room_id' => $room->room_id,
                    'room_number' => $room->room_number,
                    'monthly_rent' => (float) $room->monthly_rent,
                ],
            )
            ->values()
            ->all();

        return view('leases.edit', compact('lease', 'rooms'));
    }

    public function update(UpdateLeaseRequest $request, Lease $lease)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        // If attempting to terminate/complete, check for unpaid balance (Trigger 2 enforcement)
        if (in_array($request->status, ['Completed', 'Terminated']) && $lease->status === 'Active') {
            $unpaidBalance = PaymentRecord::where('lease_id', $lease->lease_id)
                ->where('balance', '>', 0)
                ->sum('balance');

            if ($unpaidBalance > 0) {
                throw ValidationException::withMessages([
                    'status' => "Cannot terminate lease with unpaid balance of ₱" . number_format($unpaidBalance, 2) . 
                    ". Collect all payments before terminating.",
                ]);
            }
        }

        $lease->update([
            'end_date' => $request->end_date ?: null,
            'monthly_rent' => $request->monthly_rent,
            'deposit_amount' => $request->deposit_amount,
            'initial_payment' => $request->initial_payment,
            'payment_due_day' => $request->payment_due_day,
            'status' => $request->status,
        ]);

        if (in_array($request->status, ['Completed', 'Terminated'])) {
            $lease->room->update(['status' => 'Available']);
        }

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => AuditLog::ACTION_UPDATE,
            'table_name' => 'lease',
            'record_id' => $lease->lease_id,
            'description' => "Lease #{$lease->lease_id} updated. Status: {$request->status}.",
        ]);

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully.');
    }

    public function destroy(Lease $lease)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;
        $leaseId = $lease->lease_id;

        if ($lease->status === 'Active') {
            $lease->room->update(['status' => 'Available']);
        }

        $lease->delete();

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => AuditLog::ACTION_DELETE,
            'table_name' => 'lease',
            'record_id' => $leaseId,
            'description' => "Lease #{$leaseId} deleted.",
        ]);

        return redirect()->route('leases.index')->with('success', 'Lease deleted successfully.');
    }
}
