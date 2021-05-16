<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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
        return $user->can('products.index');
    }

    public function show(User $user): bool
    {
        return $user->can('products.show');
    }

    public function store(User $user): bool
    {
        return $user->can('products.store');
    }

    public function update(User $user): bool
    {
        return $user->can('products.update');
    }

    public function delete(User $user): bool
    {
        return $user->can('products.delete');
    }
}
