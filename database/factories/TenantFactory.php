<?php

namespace Database\Factories;

use App\Models\Tenant;
use App\Models\Person;
use App\Models\Landlord;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'guardian_person_id' => $this->faker->boolean(70) ? Person::factory() : null,
            'landlord_id' => Landlord::factory(),
            'status' => $this->faker->randomElement(['Active', 'Inactive', 'Blacklisted']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Active',
        ]);
    }

    public function withGuardian(): static
    {
        return $this->state(fn (array $attributes) => [
            'guardian_person_id' => Person::factory(),
        ]);
    }
}
