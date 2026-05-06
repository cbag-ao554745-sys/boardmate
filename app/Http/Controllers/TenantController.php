<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Person;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use Illuminate\Support\Facades\DB;

class TenantController extends Controller
{
    public function index()
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $tenants = $landlord->tenants()->with('person', 'guardian')->orderByDesc('created_at');

        if (request('search')) {
            $tenants->whereHas('person', function ($q) {
                $q->where('first_name', 'like', '%' . request('search') . '%')->orWhere('last_name', 'like', '%' . request('search') . '%');
            });
        }

        if (request('status')) {
            $tenants->where('status', request('status'));
        }

        $tenants = $tenants->paginate(10)->through(
            fn($tenant) => [
                'tenant_id' => $tenant->tenant_id,
                'first_name' => $tenant->person->first_name ?? '',
                'last_name' => $tenant->person->last_name ?? '',
                'phone' => $tenant->person->contact_number ?? '', // fix: was reading wrong relation
                'room_number' => $tenant->leases()->active()->first()?->room?->room_number ?? null,
                'room_id' => $tenant->leases()->active()->first()?->room?->room_id ?? null,
                'status' => $tenant->status,
                'created_at' => $tenant->created_at->format('M d, Y'),
            ],
        );

        return view('tenants.index', [
            'tenants' => $tenants,
            'statuses' => ['Active', 'Inactive', 'Blacklisted'],
        ]);
    }

    public function create()
    {
        return view('tenants.create');
    }

    public function store(StoreTenantRequest $request)
    {
        $user = auth('landlord')->user();
        $landlord = $user->landlord;

        $person = Person::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
        ]);

        $guardianPersonId = null;
        if ($request->guardian_first_name) {
            $guardianPerson = Person::create([
                'first_name' => $request->guardian_first_name,
                'middle_name' => $request->guardian_middle_name,
                'last_name' => $request->guardian_last_name,
                'contact_number' => $request->guardian_contact_number,
                'address_line_1' => $request->guardian_address_line_1,
                'address_line_2' => $request->guardian_address_line_2,
                'city' => $request->guardian_city,
                'province' => $request->guardian_province,
                'postal_code' => $request->guardian_postal_code,
            ]);
            $guardianPersonId = $guardianPerson->person_id;
        }

        $tenant = Tenant::create([
            'person_id' => $person->person_id,
            'guardian_person_id' => $guardianPersonId,
            'landlord_id' => $landlord->landlord_id,
            'status' => $request->status ?? 'Active',
        ]);

        return redirect()
            ->route('tenants.index')
            ->with('success', "Tenant {$tenant->person->first_name} {$tenant->person->last_name} created successfully.");
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('person', 'guardian', 'leases.room');

        $activeLease = $tenant->leases()->where('status', 'Active')->with('room')->first();

        $activeLeaseData = $activeLease
            ? [
                'lease_id' => $activeLease->lease_id,
                'room_number' => $activeLease->room->room_number,
                'room_id' => $activeLease->room->room_id,
                'start_date' => $activeLease->start_date->format('M d, Y'),
            ]
            : null;

        // Fetch View 2: vw_tenant_delinquency_profile for risk assessment
        $delinquencyProfile = DB::table('vw_tenant_delinquency_profile')
            ->where('tenant_id', $tenant->tenant_id)
            ->first();

        $riskAssessment = null;
        if ($delinquencyProfile) {
            // Determine risk flag based on delinquency metrics
            $riskFlag = 'Green';
            if ($delinquencyProfile->tenant_status === 'Blacklisted') {
                $riskFlag = 'Red';
            } elseif ($delinquencyProfile->overdue_instances > 2 && $delinquencyProfile->days_late_worst > 30) {
                $riskFlag = 'Red';
            } elseif ($delinquencyProfile->overdue_instances > 0 || $delinquencyProfile->total_outstanding > 0) {
                $riskFlag = 'Yellow';
            }

            $riskAssessment = [
                'status_flag' => $riskFlag,
                'total_outstanding' => (float) $delinquencyProfile->total_outstanding,
                'overdue_instances' => $delinquencyProfile->overdue_instances,
                'worst_late_days' => $delinquencyProfile->days_late_worst,
                'total_leases' => $delinquencyProfile->total_lease_count,
                'most_recent_payment' => $delinquencyProfile->most_recent_payment_date,
            ];
        }

        return view('tenants.show', [
            'tenant' => $tenant,
            'person' => $tenant->person,
            'guardian' => $tenant->guardian,
            'active_lease' => $activeLeaseData,
            'delinquency_profile' => $delinquencyProfile,
            'risk_assessment' => $riskAssessment,
        ]);
    }

    public function edit(Tenant $tenant)
    {
        $tenant->load('person', 'guardian');

        return view('tenants.edit', [
            'tenant' => $tenant,
            'person' => $tenant->person,
            // guardian is accessed via $tenant->guardian
        ]);
    }

    public function update(UpdateTenantRequest $request, Tenant $tenant)
    {
        $tenant->load('person', 'guardian');

        $tenant->person->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'contact_number' => $request->contact_number,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city' => $request->city,
            'province' => $request->province,
            'postal_code' => $request->postal_code,
        ]);

        if ($request->guardian_first_name) {
            if ($tenant->guardian) {
                $tenant->guardian->update([
                    'first_name' => $request->guardian_first_name,
                    'middle_name' => $request->guardian_middle_name,
                    'last_name' => $request->guardian_last_name,
                    'contact_number' => $request->guardian_contact_number,
                    'address_line_1' => $request->guardian_address_line_1,
                    'address_line_2' => $request->guardian_address_line_2,
                    'city' => $request->guardian_city,
                    'province' => $request->guardian_province,
                    'postal_code' => $request->guardian_postal_code,
                ]);
            } else {
                $guardianPerson = Person::create([
                    'first_name' => $request->guardian_first_name,
                    'middle_name' => $request->guardian_middle_name,
                    'last_name' => $request->guardian_last_name,
                    'contact_number' => $request->guardian_contact_number,
                    'address_line_1' => $request->guardian_address_line_1,
                    'address_line_2' => $request->guardian_address_line_2,
                    'city' => $request->guardian_city,
                    'province' => $request->guardian_province,
                    'postal_code' => $request->guardian_postal_code,
                ]);
                $tenant->update(['guardian_person_id' => $guardianPerson->person_id]);
            }
        }

        $tenant->update([
            'status' => $request->status ?? $tenant->status,
        ]);

        return redirect()
            ->route('tenants.index')
            ->with('success', "Tenant {$tenant->person->first_name} {$tenant->person->last_name} updated successfully.");
    }

    public function destroy(Tenant $tenant)
    {
        $tenant->delete();

        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully.');
    }
}
