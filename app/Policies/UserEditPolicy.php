<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Http\Request;

class UserEditPolicy
{
    use HandlesAuthorization;

    public function create(User $user): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }

    public function update(User $user): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }

    public function view(User $user): bool
    {
        return $user->type === User::TYPE_ADMIN;
    }
}
