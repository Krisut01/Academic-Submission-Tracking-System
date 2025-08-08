<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class RoleBasedUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@rmt.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create faculty user
        User::create([
            'name' => 'Faculty User',
            'email' => 'faculty@rmt.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'faculty',
        ]);

        // Create student user
        User::create([
            'name' => 'Student User',
            'email' => 'student@rmt.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        // Create additional test users
        User::create([
            'name' => 'Dr. Sarah Wilson',
            'email' => 'sarah.wilson@rmt.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'faculty',
        ]);

        User::create([
            'name' => 'John Doe',
            'email' => 'john.doe@rmt.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane.smith@rmt.test',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'student',
        ]);
    }
}
