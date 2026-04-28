<?php

namespace Database\Factories;

use App\Models\PaymentRecord;
use App\Models\Lease;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentRecordFactory extends Factory
{
    protected $model = PaymentRecord::class;

    public function definition(): array
    {
        $rent = $this->faker->numberBetween(5000, 15000);
        $electricity = $this->faker->numberBetween(500, 2000);
        $water = $this->faker->numberBetween(200, 800);
        $otherFees = $this->faker->numberBetween(0, 1000);
        $total = $rent + $electricity + $water + $otherFees;
        $dueDate = $this->faker->dateTimeBetween('-3 months', '+1 month');

        return [
            'lease_id' => Lease::factory(),
            'tenant_id' => Tenant::factory(),
            'rent_amount' => $rent,
            'electricity_amount' => $electricity,
            'water_amount' => $water,
            'other_fees' => $otherFees,
            'total_amount' => $total,
            'amount_paid' => $this->faker->numberBetween(0, $total),
            'balance' => 0, // will be calculated
            'payment_method' => $this->faker->randomElement(['Cash', 'GCash']),
            'payment_reference' => $this->faker->uuid(),
            'status' => $this->faker->randomElement(['Unpaid', 'Partial', 'Paid', 'Overdue']),
            'bills_due_date' => $dueDate,
            'date_paid' => null,
        ];
    }

    public function paid(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount_paid' => $attributes['total_amount'],
            'balance' => 0,
            'status' => 'Paid',
            'date_paid' => now(),
        ]);
    }

    public function unpaid(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount_paid' => 0,
            'balance' => $attributes['total_amount'],
            'status' => 'Unpaid',
            'date_paid' => null,
        ]);
    }

    public function overdue(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Overdue',
            'bills_due_date' => now()->subDays(5),
            'amount_paid' => 0,
        ]);
    }
}
