<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::create([
            'name' => 'SuperAdmin',
            'email' => 'superadmin@gmail.com',
            'phone_number' => '09950314865',
            'gender' => 1,
            'address' => 'Tamwe',
            'region' => 'Yangon',
            'role_id' => 1,
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now(),
        ]);

        $admin->assignRole('admin');

        $users = [
            [
                'name' => 'user',
                'email' => 'user@gmail.com',
                'phone_number' => '09950314866',
            ],
            [
                'name' => 'user1',
                'email' => 'user1@gmail.com',
                'phone_number' => '09950314867',
            ],
            [
                'name' => 'user2',
                'email' => 'user2@gmail.com',
                'phone_number' => '09950314868',
            ],
            [
                'name' => 'user3',
                'email' => 'user3@gmail.com',
                'phone_number' => '09950314869',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create(array_merge($userData, [
                'gender' => 1,
                'address' => 'Tamwe',
                'region' => 'Yangon',
                'role_id' => 2, // Assuming 2 represents the user role
                'password' => Hash::make('password'),
                'email_verified_at' => Carbon::now(),
            ]));

            $user->assignRole('user');
        }
    }

}
