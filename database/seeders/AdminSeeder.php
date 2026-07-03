<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@xiands.com'],
            [
                'name' => 'Administradora',
                'password' => bcrypt('password'),
            ]
        );

        $admin->assignRole('administradora');
    }
}
