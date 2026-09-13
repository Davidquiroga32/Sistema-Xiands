<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@xiands.com')],
            [
                'name' => env('ADMIN_NAME', 'Administradora'),
                'password' => bcrypt(env('ADMIN_PASSWORD', 'password')),
            ]
        );

        $admin->assignRole('administradora');
    }
}
