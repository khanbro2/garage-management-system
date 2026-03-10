<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ServiceRecord;

class ServiceRecordPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, ServiceRecord $serviceRecord): bool
    {
        return $user->garage_id === $serviceRecord->vehicle->garage_id || $user->isSuperAdmin();
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, ServiceRecord $serviceRecord): bool
    {
        return $user->garage_id === $serviceRecord->vehicle->garage_id || $user->isSuperAdmin();
    }

    public function delete(User $user, ServiceRecord $serviceRecord): bool
    {
        return ($user->garage_id === $serviceRecord->vehicle->garage_id && $user->isGarageOwner()) || $user->isSuperAdmin();
    }
}