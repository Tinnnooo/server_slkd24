<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('!Admin2024'),
            'date_of_birth' => '12-12-2024',
            'phone_number' => '0895603263195',
            'profile_picture' => '',
        ]);

        $role = Role::find(1);
        $user->roles()->attach($role);
    }
}
