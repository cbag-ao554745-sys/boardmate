<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PaymentRecord;
use App\Models\PaymentMethod;
use App\Models\Lease;
use App\Http\Requests\StorePaymentRecordRequest;
use App\Http\Requests\UpdatePaymentRecordRequest;

class PaymentRecordController extends Controller
{
    public function index()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        // Redirect to current month/year if no filters are provided
        if (!request('month') && !request('year') && !request('status')) {
            return redirect()->route('payments.index', [
                'month' => now()->month,
                'year' => now()->year,
                'status' => '',
            ]);
        }

        $query = PaymentRecord::whereIn('lease_id', function ($q) use ($landlord) {
            $q->select('lease_id')->from('lease')->where('landlord_id', $landlord->landlord_id);
        })
            ->with(['lease.room', 'lease.tenants.person', 'paymentMethod'])
            ->orderByDesc('bills_due_date');

        if (request('month')) {
            $query->whereMonth('bills_due_date', request('month'));
        }
        if (request('year')) {
            $query->whereYear('bills_due_date', request('year'));
        }
        if (request('status')) {
            $query->where('status', request('status'));
        }

        $payments = $query->paginate(10)->through(function ($payment) {
            $primaryTenant = $payment->lease?->tenants->first()?->person;
            $tenantName = $primaryTenant ? trim("{$primaryTenant->first_name} {$primaryTenant->last_name}") : 'N/A';

            return [
                'payment_id' => $payment->payment_id,
                'tenant_name' => $tenantName,
                'room_number' => $payment->lease?->room?->room_number ?? 'N/A',
                'rent_amount' => (float) $payment->rent_amount,
                'electricity_amount' => (float) $payment->electricity_amount,
                'water_amount' => (float) $payment->water_amount,
                'other_fees' => (float) $payment->other_fees,
                'total_amount' => (float) $payment->total_amount,
                'amount_paid' => (float) $payment->amount_paid,
                'balance' => (float) $payment->balance,
                'payment_method' => $payment->paymentMethod?->name ?? 'N/A',
                'status' => $payment->status,
                'bills_due_date' => $payment->bills_due_date?->format('M d, Y'),
                'date_paid' => $payment->date_paid?->format('M d, Y'),
            ];
        });

        return view('payments.index', compact('payments'));
    }

    public function create()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $leases = $landlord
            ->leases()
            ->where('status', 'Active')
            ->with('room', 'tenants.person')
            ->get()
            ->map(function ($lease) {
                return [
                    'lease_id' => $lease->lease_id,
                    'room_number' => $lease->room->room_number,
                    'tenant_names' => $lease->tenants->map(fn($t) => trim("{$t->person->first_name} {$t->person->last_name}"))->join(', '),
                    'monthly_rent' => (float) $lease->monthly_rent,
                    'payment_due_day' => (int) $lease->payment_due_day,
                    'tenants' => $lease->tenants
                        ->map(
                            fn($t) => [
                                'tenant_id' => $t->tenant_id,
                                'first_name' => $t->person->first_name,
                                'last_name' => $t->person->last_name,
                                'is_primary' => (bool) ($t->pivot->is_primary_tenant ?? false),
                            ],
                        )
                        ->toArray(),
                ];
            })
            ->toArray();

        $paymentMethods = PaymentMethod::active()->get();

        return view('payments.create', compact('leases', 'paymentMethods'));
    }

    public function store(StorePaymentRecordRequest $request)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $rentAmount = (float) ($request->rent_amount ?? 0);
        $electricityAmount = (float) ($request->electricity_amount ?? 0);
        $waterAmount = (float) ($request->water_amount ?? 0);
        $otherFees = (float) ($request->other_fees ?? 0);
        $totalAmount = $rentAmount + $electricityAmount + $waterAmount + $otherFees;
        $amountPaid = (float) ($request->amount_paid ?? 0);
        $balance = $totalAmount - $amountPaid;

        // Convert payment method name to ID
        $paymentMethodId = null;
        if ($request->payment_method) {
            $paymentMethod = PaymentMethod::where('name', $request->payment_method)->first();
            $paymentMethodId = $paymentMethod?->payment_method_id;
        }

        // Status is automatically computed by database trigger trg_recompute_payment_status_*
        $payment = PaymentRecord::create([
            'lease_id' => $request->lease_id,
            'tenant_id' => $request->tenant_id,
            'rent_amount' => $rentAmount,
            'electricity_amount' => $electricityAmount,
            'water_amount' => $waterAmount,
            'other_fees' => $otherFees,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'balance' => $balance,
            'payment_method_id' => $paymentMethodId,
            'payment_reference' => $request->payment_reference,
            'bills_due_date' => $request->bills_due_date,
            'date_paid' => $request->date_paid ?: null,
        ]);

        $payment->refresh();

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => AuditLog::ACTION_INSERT,
            'table_name' => 'payment_record',
            'record_id' => $payment->payment_id,
            'description' => "Payment record created for lease #{$request->lease_id}. Amount paid: {$amountPaid}. Status: {$payment->status}.",
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully.');
    }

    public function show(PaymentRecord $payment)
    {
        $payment->load('lease.room', 'lease.tenants.person', 'tenant.person', 'paymentMethod');

        return view('payments.show', [
            'payment' => $payment,
            'tenant' => $payment->tenant,
        ]);
    }

    public function edit(PaymentRecord $payment)
    {
        $payment->load('lease.room', 'tenant.person', 'paymentMethod');

        $paymentMethods = PaymentMethod::active()->get();

        return view('payments.edit', compact('payment', 'paymentMethods'));
    }

    public function update(UpdatePaymentRecordRequest $request, PaymentRecord $payment)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $rentAmount = (float) ($request->rent_amount ?? 0);
        $electricityAmount = (float) ($request->electricity_amount ?? 0);
        $waterAmount = (float) ($request->water_amount ?? 0);
        $otherFees = (float) ($request->other_fees ?? 0);
        $totalAmount = $rentAmount + $electricityAmount + $waterAmount + $otherFees;
        $amountPaid = (float) ($request->amount_paid ?? 0);
        $balance = $totalAmount - $amountPaid;

        // Convert payment method name to ID
        $paymentMethodId = null;
        if ($request->payment_method) {
            $paymentMethod = PaymentMethod::where('name', $request->payment_method)->first();
            $paymentMethodId = $paymentMethod?->payment_method_id;
        }

        // Status is automatically recomputed by database trigger trg_recompute_payment_status_*
        $payment->update([
            'rent_amount' => $rentAmount,
            'electricity_amount' => $electricityAmount,
            'water_amount' => $waterAmount,
            'other_fees' => $otherFees,
            'total_amount' => $totalAmount,
            'amount_paid' => $amountPaid,
            'balance' => $balance,
            'payment_method_id' => $paymentMethodId,
            'payment_reference' => $amountPaid > 0 ? $request->payment_reference : null,
            'date_paid' => $amountPaid > 0 ? $request->date_paid : null,
            'bills_due_date' => $request->bills_due_date,
        ]);

        $payment->refresh();

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => AuditLog::ACTION_UPDATE,
            'table_name' => 'payment_record',
            'record_id' => $payment->payment_id,
            'description' => "Payment record #{$payment->payment_id} updated. Status changed to: {$payment->status}.",
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment updated successfully.');
    }

    public function destroy(PaymentRecord $payment)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;
        $paymentId = $payment->payment_id;

        $payment->delete();

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => AuditLog::ACTION_DELETE,
            'table_name' => 'payment_record',
            'record_id' => $paymentId,
            'description' => "Payment record #{$paymentId} deleted.",
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment deleted successfully.');
    }
}
