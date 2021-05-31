<?php

namespace Tests;

use App\Models\User;
use Database\Seeders\CategorySeeder;
use Database\Seeders\ProductSeeder;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\UserDefaultSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Laravel\Passport\Passport;
use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, MakesGraphQLRequests, RefreshDatabase;

    protected $userAdmin;
    protected $userSeller;
    protected $userGrocer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed([
            CategorySeeder::class,
            ProductSeeder::class,
            RolePermissionSeeder::class,
            UserDefaultSeeder::class,
        ]);

        $this->userAdmin = User::role('admin')->first();
        $this->userSeller = User::role('seller')->first();
        $this->userGrocer = User::role('grocer')->first();
    }

    protected function useUserPassport(string $role = 'admin'): void
    {
        $user = match ($role) {
            'seller' => User::role('seller')->first(),
            'grocer' => User::role('grocer')->first(),
            default  => User::role('admin')->first(),
        };

        Passport::actingAs($user);
    }
}
