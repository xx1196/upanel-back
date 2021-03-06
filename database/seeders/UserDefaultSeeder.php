<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserDefaultSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $userAdmin = User::firstOrCreate(
            ['email' => 'admin@admin.com'],

            [
                'name' => 'User admin',
                'password' => Hash::make('secret')
            ]);

        $userSeller = User::firstOrCreate(
            ['email' => 'user@seller.com'],

            [
                'name' => 'User seller',
                'password' => Hash::make('secret')
            ]);

        $userGrocer = User::firstOrCreate(
            ['email' => 'user@grocer.com'],

            [
                'name' => 'User grocer',
                'password' => Hash::make('secret')
            ]);

        $userAdmin->assignRole('admin');
        $userSeller->assignRole('seller');
        $userGrocer->assignRole('grocer');
    }
}
