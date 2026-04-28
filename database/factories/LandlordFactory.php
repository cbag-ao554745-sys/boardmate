<?php

namespace Database\Factories;

use App\Models\Landlord;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class LandlordFactory extends Factory
{
    protected $model = Landlord::class;

    public function definition(): array
    {
        return [
            'person_id' => Person::factory(),
            'user_id' => User::factory(),
        ];
    }
}
