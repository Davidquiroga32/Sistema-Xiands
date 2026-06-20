<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        Role::create(['name' => 'administradora', 'guard_name' => 'web']);
        Role::create(['name' => 'secretaria', 'guard_name' => 'web']);
    }
}
