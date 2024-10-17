<?php

namespace Database\Factories;

use App\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition()
    {
        return [
            'name' => $this->faker->userName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => Hash::make('password'),
        ];
    }
}
