<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::create([
            'name' => 'Administradora',
            'email' => 'admin@xiands.com',
            'password' => bcrypt('password'),
        ]);

        $admin->assignRole('administradora');
    }
}
