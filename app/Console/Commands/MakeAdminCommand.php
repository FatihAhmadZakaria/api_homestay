<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class MakeAdminCommand extends Command
{
    protected $signature = 'make:admin
                            {name : The name of the admin}
                            {email : The email of the admin}
                            {password : The password for the admin}';

    protected $description = 'Create a new admin user';

    public function handle()
    {
        $username = $this->argument('name');
        $email = $this->argument('email');
        $password = $this->argument('password');

        Admin::create([
            'name' => $username,
            'email' => $email,
            'password' => Hash::make($password),
        ]);

        $this->info('Admin user created successfully!');
    }
}
