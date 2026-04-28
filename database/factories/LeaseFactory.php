<?php

namespace Database\Factories;

use App\Models\Lease;
use App\Models\Room;
use App\Models\Landlord;
use Illuminate\Database\Eloquent\Factories\Factory;

class LeaseFactory extends Factory
{
    protected $model = Lease::class;

    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-6 months', 'now');
        $endDate = $this->faker->boolean(50) ? $this->faker->dateTimeBetween('now', '+1 year') : null;

        return [
            'room_id' => Room::factory(),
            'landlord_id' => Landlord::factory(),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'monthly_rent' => $this->faker->numberBetween(5000, 15000),
            'deposit_amount' => $this->faker->numberBetween(5000, 10000),
            'initial_payment' => $this->faker->numberBetween(5000, 15000),
            'payment_due_day' => $this->faker->numberBetween(1, 28),
            'status' => 'Active',
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Active',
            'end_date' => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Completed',
            'end_date' => now()->subMonths(3),
        ]);
    }
}
