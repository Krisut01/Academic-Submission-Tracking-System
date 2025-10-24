<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ThesisDocument;
use Illuminate\Support\Facades\Hash;

class TestDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test student if not exists
        $student = User::firstOrCreate(
            ['email' => 'student@test.com'],
            [
                'name' => 'Test Student',
                'password' => Hash::make('password'),
                'role' => 'student',
            ]
        );

        // Create test faculty if not exists
        $faculty = User::firstOrCreate(
            ['email' => 'faculty@test.com'],
            [
                'name' => 'Test Faculty',
                'password' => Hash::make('password'),
                'role' => 'faculty',
            ]
        );

        // Create additional faculty members
        $faculty2 = User::firstOrCreate(
            ['email' => 'faculty2@test.com'],
            [
                'name' => 'Dr. Jane Smith',
                'password' => Hash::make('password'),
                'role' => 'faculty',
            ]
        );

        $faculty3 = User::firstOrCreate(
            ['email' => 'faculty3@test.com'],
            [
                'name' => 'Prof. John Doe',
                'password' => Hash::make('password'),
                'role' => 'faculty',
            ]
        );

        // Create thesis documents for the student
        $documents = [
            [
                'document_type' => 'proposal',
                'title' => 'Research Proposal: AI in Healthcare',
                'status' => 'approved',
            ],
            [
                'document_type' => 'approval_sheet',
                'title' => 'Thesis Approval Sheet',
                'status' => 'approved',
            ],
            [
                'document_type' => 'panel_assignment',
                'title' => 'Panel Assignment Request',
                'status' => 'approved',
            ],
            [
                'document_type' => 'final_manuscript',
                'title' => 'Final Thesis Manuscript: AI in Healthcare',
                'status' => 'approved',
            ],
        ];

        foreach ($documents as $docData) {
            ThesisDocument::firstOrCreate(
                [
                    'user_id' => $student->id,
                    'document_type' => $docData['document_type'],
                ],
                [
                    'student_id' => 'STU-2024-001',
                    'full_name' => $student->name,
                    'course_program' => 'Computer Science',
                    'title' => $docData['title'],
                    'description' => 'Test thesis document for ' . $docData['document_type'],
                    'status' => $docData['status'],
                    'submission_date' => now(),
                    'reviewed_by' => $faculty->id,
                    'reviewed_at' => now(),
                ]
            );
        }

        $this->command->info('Test data seeded successfully!');
        $this->command->info('Student: student@test.com / password');
        $this->command->info('Faculty: faculty@test.com / password');
        $this->command->info('Created ' . count($documents) . ' thesis documents');
    }
}
