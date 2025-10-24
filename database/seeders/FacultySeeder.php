<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $facultyMembers = [
            [
                'name' => 'Dr. John Smith',
                'email' => 'john.smith@faculty.edu',
                'password' => 'john', // Same as first name for easy testing
            ],
            [
                'name' => 'Dr. Jane Doe',
                'email' => 'jane.doe@faculty.edu',
                'password' => 'jane', // Same as first name for easy testing
            ],
            [
                'name' => 'Prof. Michael Johnson',
                'email' => 'michael.johnson@faculty.edu',
                'password' => 'michael', // Same as first name for easy testing
            ],
            [
                'name' => 'Dr. Sarah Wilson',
                'email' => 'sarah.wilson@faculty.edu',
                'password' => 'sarah', // Same as first name for easy testing
            ],
            [
                'name' => 'Prof. David Brown',
                'email' => 'david.brown@faculty.edu',
                'password' => 'david', // Same as first name for easy testing
            ],
        ];

        foreach ($facultyMembers as $faculty) {
            User::firstOrCreate(
                ['email' => $faculty['email']],
                [
                    'name' => $faculty['name'],
                    'password' => Hash::make($faculty['password']),
                    'role' => 'faculty',
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->info('Faculty seeder completed successfully!');
        $this->command->info('Created 5 faculty members:');
        foreach ($facultyMembers as $faculty) {
            $this->command->info("- {$faculty['name']} | Email: {$faculty['email']} | Password: {$faculty['password']}");
        }
    }
}
