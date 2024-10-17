<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'nama_depan' => $this->faker->firstName,
            'nama_belakang' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'), // Set default password
            'phone' => $this->faker->phoneNumber,
            'remember_token' => Str::random(10),
        ];
    }
}

