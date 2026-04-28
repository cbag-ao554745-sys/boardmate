<?php

namespace Database\Factories;

use App\Models\Room;
use App\Models\Landlord;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoomFactory extends Factory
{
    protected $model = Room::class;

    public function definition(): array
    {
        return [
            'landlord_id' => Landlord::factory(),
            'room_number' => 'Room ' . $this->faker->unique()->numberBetween(101, 510),
            'floor' => $this->faker->numberBetween(1, 5),
            'monthly_rent' => $this->faker->numberBetween(5000, 15000),
            'status' => $this->faker->randomElement(['Available', 'Occupied', 'Under Maintenance']),
        ];
    }

    public function available(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Available',
        ]);
    }

    public function occupied(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Occupied',
        ]);
    }

    public function underMaintenance(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Under Maintenance',
        ]);
    }
}
