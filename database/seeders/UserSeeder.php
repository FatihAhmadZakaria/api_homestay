<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // User pertama
        User::create([
            'nama_depan' => 'John',
            'nama_belakang' => 'Doe',
            'email' => 'jhondoe@email.com',
            'password' => Hash::make('123'),
            'phone' => '084267382017',
        ]);

        // User kedua
        User::create([
            'nama_depan' => 'Jane',
            'nama_belakang' => 'Smith',
            'email' => 'janesmith@email.com',
            'password' => Hash::make('456'),
            'phone' => '084267382018',
        ]);

        // User ketiga
        User::create([
            'nama_depan' => 'Michael',
            'nama_belakang' => 'Johnson',
            'email' => 'michaeljohnson@email.com',
            'password' => Hash::make('789'),
            'phone' => '084267382019',
        ]);
    }
}
