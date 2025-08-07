<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'name' => 'Super Admin',
            'email' => 'superAdmin@example.com',
            'password' => Hash::make('password'), 
        ]);

        $role = Role::firstOrCreate(['name' => 'Admin']);

        $admin->assignRole($role);

        
    }
}
