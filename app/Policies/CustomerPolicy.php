<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Customer;

class CustomerPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Customer $customer): bool
    {
        return $user->garage_id === $customer->garage_id || $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Customer $customer): bool
    {
        return $user->garage_id === $customer->garage_id || $user->isSuperAdmin();
    }

    public function delete(User $user, Customer $customer): bool
    {
        return ($user->garage_id === $customer->garage_id && $user->isGarageOwner()) || $user->isSuperAdmin();
    }
}