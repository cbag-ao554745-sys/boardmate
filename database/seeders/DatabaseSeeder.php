<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Person;
use App\Models\Landlord;
use App\Models\Room;
use App\Models\Tenant;
use App\Models\Lease;
use App\Models\PaymentRecord;
use App\Models\Notification;
use App\Models\AuditLog;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    protected $faker;

    public function __construct()
    {
        $this->faker = Faker::create();
    }

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed payment methods first (used system-wide)
        $this->call(PaymentMethodSeeder::class);

        // Create test user for landlord authentication
        $user = User::firstOrCreate(
            ['email' => 'landlord@upahan.local'],
            [
                'username' => 'landlord_demo',
                'email' => 'landlord@upahan.local',
                'password' => bcrypt('password'),
            ],
        );

        // Create landlord person details
        $landlordPerson = Person::factory()->create([
            'first_name' => 'John',
            'middle_name' => 'Michael',
            'last_name' => 'Reyes',
            'contact_number' => '+63912345678',
            'city' => 'Manila',
            'province' => 'Metro Manila',
        ]);

        // Create landlord record linking user and person
        $landlord = Landlord::create([
            'person_id' => $landlordPerson->person_id,
            'user_id' => $user->id,
        ]);

        // Create rooms
        $rooms = Room::factory()
            ->count(8)
            ->create([
                'landlord_id' => $landlord->landlord_id,
            ]);

        // Mark some as occupied
        $rooms[0]->update(['status' => 'Occupied']);
        $rooms[1]->update(['status' => 'Occupied']);
        $rooms[2]->update(['status' => 'Occupied']);
        $rooms[7]->update(['status' => 'Under Maintenance']);

        // Create tenants with guardians
        $tenantPersons = Person::factory()->count(6)->create();
        $guardianPersons = Person::factory()->count(3)->create();

        $tenants = [];
        for ($i = 0; $i < 6; $i++) {
            $guardian = $i < 3 ? $guardianPersons[$i] : null;
            $tenants[] = Tenant::factory()->create([
                'person_id' => $tenantPersons[$i]->person_id,
                'guardian_person_id' => $guardian?->person_id,
                'landlord_id' => $landlord->landlord_id,
                'status' => 'Active',
            ]);
        }

        // Create leases
        $leases = [];
        for ($i = 0; $i < 3; $i++) {
            $lease = Lease::factory()->create([
                'room_id' => $rooms[$i]->room_id,
                'landlord_id' => $landlord->landlord_id,
                'status' => 'Active',
            ]);
            $leases[] = $lease;

            // Attach primary tenant to lease
            $lease->tenants()->attach($tenants[$i], [
                'is_primary_tenant' => true,
                'move_in_date' => now()->subMonths(3),
                'move_out_date' => null,
            ]);

            // Attach secondary tenants (if applicable)
            if ($i < 2 && isset($tenants[$i + 3])) {
                $lease->tenants()->attach($tenants[$i + 3], [
                    'is_primary_tenant' => false,
                    'move_in_date' => now()->subMonths(3),
                    'move_out_date' => null,
                ]);
            }
        }

        // Create payment records
        foreach ($leases as $lease) {
            for ($month = -2; $month <= 0; $month++) {
                $dueDate = now()->addMonths($month)->setDay($lease->payment_due_day);
                $primaryTenant = $lease->tenants()->where('is_primary_tenant', true)->first();

                $status = match ($month) {
                    -2 => 'Paid',
                    -1 => $this->faker->randomElement(['Paid', 'Partial']),
                    0 => $dueDate->isPast() ? 'Overdue' : 'Pending',
                };

                $totalAmount = $lease->monthly_rent + 1200;
                $amountPaid = match ($status) {
                    'Paid' => $totalAmount,
                    'Partial' => $totalAmount * 0.5,
                    default => 0,
                };

                $payment = PaymentRecord::create([
                    'lease_id' => $lease->lease_id,
                    'tenant_id' => $primaryTenant->tenant_id,
                    'rent_amount' => $lease->monthly_rent,
                    'electricity_amount' => 800,
                    'water_amount' => 400,
                    'other_fees' => 0,
                    'total_amount' => $totalAmount,
                    'amount_paid' => $amountPaid,
                    'balance' => max(0, $totalAmount - $amountPaid),
                    'payment_method' => $this->faker->randomElement(['Cash', 'GCash']),
                    'payment_reference' => $status === 'Paid' ? $this->faker->uuid() : null,
                    'status' => $status,
                    'bills_due_date' => $dueDate,
                    'date_paid' => $status === 'Paid' ? $dueDate : null,
                ]);

                if ($status === 'Overdue') {
                    Notification::create([
                        'payment_id' => $payment->payment_id,
                        'landlord_id' => $landlord->landlord_id,
                        'type' => 'Overdue',
                        'message' => 'Overdue payment',
                        'sent_at' => now(),
                        'is_read' => false,
                    ]);
                }
            }
        }

        // Create some audit log entries
        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => 'INSERT',
            'table_name' => 'tenant',
            'record_id' => $tenants[0]->tenant_id,
            'description' => 'Created tenant',
            'timestamp' => now()->subDays(7),
        ]);

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => 'UPDATE',
            'table_name' => 'room',
            'record_id' => $rooms[0]->room_id,
            'description' => 'Updated room status',
            'timestamp' => now()->subDays(5),
        ]);

        AuditLog::create([
            'landlord_id' => $landlord->landlord_id,
            'action' => 'INSERT',
            'table_name' => 'lease',
            'record_id' => $leases[0]->lease_id,
            'description' => 'Created lease',
            'timestamp' => now()->subDays(3),
        ]);
    }
}
