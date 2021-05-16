<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $roles = [
            'admin',
            'seller',
            'grocer'
        ];

        $permissionsCategories = [
            'categories.index',
            'categories.show',
            'categories.store',
            'categories.update',
            'categories.delete',
        ];

        $permissionsProducts = [
            'products.index',
            'products.show',
            'products.store',
            'products.update',
            'products.delete',
        ];

        $permissionsSellers = [
            'Sales.index',
            'Sales.store',
        ];


        foreach ($roles as $role) {
            Role::findOrCreate($role);
        }

        foreach ($permissionsCategories as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach ($permissionsProducts as $permission) {
            Permission::findOrCreate($permission);
        }

        foreach ($permissionsSellers as $permission) {
            Permission::findOrCreate($permission);
        }


        $roles = Role::all();

        $admin = $roles[0];
        $seller = $roles[1];
        $grocer = $roles[2];

        $admin->syncPermissions(Permission::all());

        $seller->syncPermissions($permissionsSellers);

        $grocer->syncPermissions(array_merge($permissionsCategories, $permissionsProducts));

    }
}
