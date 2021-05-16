<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(User $user): bool
    {
        return $user->can('categories.index');
    }

    public function show(User $user): bool
    {
        return $user->can('categories.show');
    }

    public function store(User $user): bool
    {
        return $user->can('categories.store');
    }

    public function update(User $user): bool
    {
        return $user->can('categories.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('categories.delete');
    }
}
