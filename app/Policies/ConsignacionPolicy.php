<?php

namespace App\Policies;

use App\Models\Consignacion;
use App\Models\User;

class ConsignacionPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Consignacion $consignacion): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Consignacion $consignacion): bool
    {
        return true;
    }

    public function delete(User $user, Consignacion $consignacion): bool
    {
        return $user->hasRole('administradora');
    }

    public function restore(User $user, Consignacion $consignacion): bool
    {
        return $user->hasRole('administradora');
    }

    public function forceDelete(User $user, Consignacion $consignacion): bool
    {
        return $user->hasRole('administradora');
    }
}
