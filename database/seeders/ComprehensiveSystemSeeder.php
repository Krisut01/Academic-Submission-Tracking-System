<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\AcademicForm;
use App\Models\ThesisDocument;
use App\Models\PanelAssignment;
use App\Models\Notification;
use Carbon\Carbon;

class ComprehensiveSystemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing data (optional - comment out if you want to keep existing data)
        $this->command->info('Clearing existing data...');
        
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        Notification::truncate();
        DB::table('panel_evaluations')->truncate();
        DB::table('panel_assignment_reviews')->truncate();
        PanelAssignment::truncate();
        ThesisDocument::truncate();
        AcademicForm::truncate();
        DB::table('activity_logs')->truncate();
        User::truncate();
        
        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('Starting comprehensive seeding...');

        // Create Admin
        $this->command->info('Creating admin user...');
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@rmt.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create 5 Faculty Members
        $this->command->info('Creating 5 faculty members...');
        $faculty = [];
        $facultyNames = [
            'Dr. Maria Santos',
            'Prof. Juan Dela Cruz',
            'Dr. Ana Rodriguez',
            'Prof. Carlos Mendoza',
            'Dr. Linda Reyes'
        ];

        foreach ($facultyNames as $index => $name) {
            $faculty[] = User::create([
                'name' => $name,
                'email' => 'faculty' . ($index + 1) . '@rmt.com',
                'password' => Hash::make('password'),
                'role' => 'faculty',
                'email_verified_at' => now(),
            ]);
        }

        // Create 10 Students with varied thesis progress
        $this->command->info('Creating 10 students...');
        $students = [];
        $studentData = [
            ['name' => 'John Smith', 'student_id' => '2021-00001', 'course' => 'BS Computer Science'],
            ['name' => 'Jane Doe', 'student_id' => '2021-00002', 'course' => 'BS Information Technology'],
            ['name' => 'Michael Brown', 'student_id' => '2021-00003', 'course' => 'BS Computer Science'],
            ['name' => 'Sarah Johnson', 'student_id' => '2021-00004', 'course' => 'BS Information Technology'],
            ['name' => 'David Wilson', 'student_id' => '2021-00005', 'course' => 'BS Computer Science'],
            ['name' => 'Emily Davis', 'student_id' => '2021-00006', 'course' => 'BS Information Technology'],
            ['name' => 'Robert Martinez', 'student_id' => '2021-00007', 'course' => 'BS Computer Science'],
            ['name' => 'Lisa Anderson', 'student_id' => '2021-00008', 'course' => 'BS Information Technology'],
            ['name' => 'James Taylor', 'student_id' => '2021-00009', 'course' => 'BS Computer Science'],
            ['name' => 'Patricia Thomas', 'student_id' => '2021-00010', 'course' => 'BS Information Technology'],
        ];

        foreach ($studentData as $index => $data) {
            $students[] = User::create([
                'name' => $data['name'],
                'email' => 'student' . ($index + 1) . '@rmt.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'email_verified_at' => now(),
            ]);
        }

        // Create Academic Forms for students
        $this->command->info('Creating academic forms...');
        foreach ($students as $index => $student) {
            $studentInfo = $studentData[$index];
            
            // Registration Form (all students have this)
            AcademicForm::create([
                'user_id' => $student->id,
                'form_type' => 'registration',
                'student_id' => $studentInfo['student_id'],
                'title' => 'Course Registration Form - ' . $studentInfo['course'],
                'description' => 'Course registration form for enrollment and subject selection.',
                'status' => $index < 8 ? 'approved' : 'pending',
                'form_data' => [
                    'course_program' => $studentInfo['course'],
                    'year_level' => '4th Year',
                    'semester' => '2nd Semester',
                    'subjects' => ['Thesis Writing 1', 'Capstone Project', 'Seminar'],
                    'units' => 18,
                    'gpa' => rand(250, 400) / 100,
                ],
                'submission_date' => now()->subMonths(rand(1, 3)),
                'reviewed_at' => $index < 8 ? now()->subMonths(rand(0, 2)) : null,
                'reviewed_by' => $index < 8 ? $admin->id : null,
                'review_comments' => $index < 8 ? 'Registration approved. Good academic standing.' : null,
            ]);

            // Evaluation Form (students 1-6 have this)
            if ($index < 6) {
                AcademicForm::create([
                    'user_id' => $student->id,
                    'form_type' => 'evaluation',
                    'student_id' => $studentInfo['student_id'],
                    'title' => 'Mid-Term Evaluation Form',
                    'description' => 'Evaluation form for academic progress and thesis status assessment.',
                    'status' => $index < 4 ? 'approved' : ($index < 5 ? 'under_review' : 'pending'),
                    'form_data' => [
                        'completed_subjects' => ['Research Methods', 'Advanced Programming', 'Database Systems'],
                        'thesis_progress' => $index < 3 ? 'Proposal Approved' : 'Proposal Submitted',
                        'current_gpa' => rand(300, 395) / 100,
                    ],
                    'submission_date' => now()->subWeeks(rand(2, 8)),
                    'reviewed_at' => $index < 4 ? now()->subWeeks(rand(1, 4)) : null,
                    'reviewed_by' => $index < 4 ? $admin->id : null,
                    'review_comments' => $index < 4 ? 'Satisfactory progress. Continue with thesis work.' : null,
                ]);
            }

            // Clearance Form (only students 1-3 have this - they're near completion)
            if ($index < 3) {
                AcademicForm::create([
                    'user_id' => $student->id,
                    'form_type' => 'clearance',
                    'student_id' => $studentInfo['student_id'],
                    'title' => 'Graduation Clearance Form',
                    'description' => 'Clearance form for graduation requirements and final obligations.',
                    'status' => $index < 2 ? 'approved' : 'under_review',
                    'form_data' => [
                        'clearance_type' => 'graduation',
                        'library_cleared' => true,
                        'finance_cleared' => true,
                        'registrar_cleared' => $index < 2,
                        'thesis_completed' => $index < 2,
                    ],
                    'submission_date' => now()->subDays(rand(5, 15)),
                    'reviewed_at' => $index < 2 ? now()->subDays(rand(1, 5)) : null,
                    'reviewed_by' => $index < 2 ? $admin->id : null,
                    'review_comments' => $index < 2 ? 'All requirements completed. Cleared for graduation.' : null,
                ]);
            }
        }

        // Create Thesis Documents for students
        $this->command->info('Creating thesis documents...');
        $thesisTopics = [
            'Machine Learning Approaches for Student Performance Prediction',
            'Development of Mobile Application for Campus Navigation',
            'Blockchain-Based Student Records Management System',
            'AI-Powered Chatbot for Academic Advising',
            'IoT-Based Smart Classroom Management System',
            'Cloud-Based Collaborative Learning Platform',
            'Data Mining Techniques for Educational Analytics',
            'Virtual Reality Application for Science Education',
            'Cybersecurity Framework for Educational Institutions',
            'Automated Grading System Using Natural Language Processing',
        ];

        foreach ($students as $index => $student) {
            $studentInfo = $studentData[$index];
            $adviser = $faculty[rand(0, count($faculty) - 1)];
            
            // All students have submitted proposals
            $proposal = ThesisDocument::create([
                'user_id' => $student->id,
                'document_type' => 'proposal',
                'student_id' => $studentInfo['student_id'],
                'full_name' => $student->name,
                'course_program' => $studentInfo['course'],
                'title' => $thesisTopics[$index],
                'description' => 'Research proposal for ' . $thesisTopics[$index],
                'abstract' => 'This research aims to investigate and develop solutions for improving educational outcomes through technology integration. The study will employ quantitative and qualitative methods to gather data and analyze results.',
                'research_area' => $index % 2 == 0 ? 'Artificial Intelligence' : 'Software Development',
                'adviser_name' => $adviser->name,
                'adviser_id' => $adviser->id,
                'status' => $index < 7 ? 'approved' : ($index < 9 ? 'under_review' : 'pending'),
                'submission_date' => now()->subMonths(rand(2, 5)),
                'reviewed_at' => $index < 7 ? now()->subMonths(rand(1, 4)) : null,
                'reviewed_by' => $index < 7 ? $adviser->id : null,
                'review_comments' => $index < 7 ? 'Proposal approved. Proceed with research implementation.' : null,
            ]);

            // Students 1-7 have panel assignments with different defense stages
            if ($index < 7) {
                $panelMembers = [];
                $selectedFaculty = array_rand($faculty, 3);
                foreach ($selectedFaculty as $fIndex) {
                    $panelMembers[] = $faculty[$fIndex]->id;
                }

                // Different defense scenarios:
                // Students 0-1: Completed proposal defense (past)
                // Students 2-3: Upcoming proposal defense (future - within 2 weeks)
                // Students 4-5: Proposal defense scheduled (future - next month)
                // Student 6: Completed proposal, now has final defense scheduled
                
                if ($index < 2) {
                    // Completed proposal defense
                    PanelAssignment::create([
                        'student_id' => $student->id,
                        'thesis_document_id' => $proposal->id,
                        'thesis_title' => $thesisTopics[$index],
                        'thesis_description' => 'Proposal defense for ' . $thesisTopics[$index],
                        'panel_members' => $panelMembers,
                        'panel_chair_id' => $panelMembers[0],
                        'secretary_id' => $panelMembers[1],
                        'defense_date' => now()->subDays(rand(15, 45)),
                        'defense_venue' => 'Computer Laboratory ' . ($index + 1),
                        'defense_instructions' => 'Please prepare a 30-minute presentation followed by Q&A session. Bring printed copies of your proposal for panel members.',
                        'defense_type' => 'proposal_defense',
                        'status' => 'completed',
                        'completed_at' => now()->subDays(rand(10, 40)),
                        'completed_by' => $student->id,
                        'student_notified' => true,
                        'panel_notified' => true,
                        'notification_sent_at' => now()->subDays(rand(50, 60)),
                        'created_by' => $admin->id,
                    ]);
                } elseif ($index < 4) {
                    // Upcoming proposal defense (within 2 weeks)
                    PanelAssignment::create([
                        'student_id' => $student->id,
                        'thesis_document_id' => $proposal->id,
                        'thesis_title' => $thesisTopics[$index],
                        'thesis_description' => 'Proposal defense for ' . $thesisTopics[$index],
                        'panel_members' => $panelMembers,
                        'panel_chair_id' => $panelMembers[0],
                        'secretary_id' => $panelMembers[1],
                        'defense_date' => now()->addDays(rand(3, 12)),
                        'defense_venue' => 'Room ' . (301 + $index),
                        'defense_instructions' => 'Please prepare a 30-minute presentation of your research proposal. Panel members will evaluate your research methodology and feasibility.',
                        'defense_type' => 'proposal_defense',
                        'status' => 'scheduled',
                        'student_notified' => true,
                        'panel_notified' => true,
                        'notification_sent_at' => now()->subDays(rand(5, 10)),
                        'created_by' => $admin->id,
                    ]);
                } elseif ($index < 6) {
                    // Proposal defense scheduled (next month)
                    PanelAssignment::create([
                        'student_id' => $student->id,
                        'thesis_document_id' => $proposal->id,
                        'thesis_title' => $thesisTopics[$index],
                        'thesis_description' => 'Proposal defense for ' . $thesisTopics[$index],
                        'panel_members' => $panelMembers,
                        'panel_chair_id' => $panelMembers[0],
                        'secretary_id' => $panelMembers[1],
                        'defense_date' => now()->addDays(rand(20, 35)),
                        'defense_venue' => 'Conference Room ' . ($index - 3),
                        'defense_instructions' => 'Prepare comprehensive presentation covering background, methodology, expected outcomes, and timeline. Duration: 30-45 minutes including Q&A.',
                        'defense_type' => 'proposal_defense',
                        'status' => 'scheduled',
                        'student_notified' => true,
                        'panel_notified' => false,
                        'notification_sent_at' => now()->subDays(2),
                        'created_by' => $admin->id,
                    ]);
                } else {
                    // Student 6: Has completed proposal, now scheduled for final defense
                    // First create completed proposal defense
                    PanelAssignment::create([
                        'student_id' => $student->id,
                        'thesis_document_id' => $proposal->id,
                        'thesis_title' => $thesisTopics[$index],
                        'thesis_description' => 'Proposal defense for ' . $thesisTopics[$index],
                        'panel_members' => $panelMembers,
                        'panel_chair_id' => $panelMembers[0],
                        'secretary_id' => $panelMembers[1],
                        'defense_date' => now()->subDays(60),
                        'defense_venue' => 'AVR Room',
                        'defense_instructions' => 'Proposal defense presentation and evaluation.',
                        'defense_type' => 'proposal_defense',
                        'status' => 'completed',
                        'completed_at' => now()->subDays(55),
                        'completed_by' => $student->id,
                        'student_notified' => true,
                        'panel_notified' => true,
                        'notification_sent_at' => now()->subDays(65),
                        'created_by' => $admin->id,
                    ]);
                }
            }

            // Students 1-3 have submitted approval sheets
            if ($index < 3) {
                ThesisDocument::create([
                    'user_id' => $student->id,
                    'document_type' => 'approval_sheet',
                    'student_id' => $studentInfo['student_id'],
                    'full_name' => $student->name,
                    'course_program' => $studentInfo['course'],
                    'title' => 'Proposal Defense Approval Sheet - ' . $thesisTopics[$index],
                    'description' => 'Approval sheet containing defense results and panel signatures.',
                    'adviser_name' => $adviser->name,
                    'adviser_id' => $adviser->id,
                    'defense_date' => now()->subDays(rand(5, 25)),
                    'defense_type' => 'Proposal Defense',
                    'status' => 'approved',
                    'submission_date' => now()->subDays(rand(3, 20)),
                    'reviewed_at' => now()->subDays(rand(1, 15)),
                    'reviewed_by' => $adviser->id,
                    'review_comments' => 'Defense approval confirmed. Proceed to final manuscript preparation.',
                ]);
            }

            // Students 1-2 have submitted final manuscripts
            if ($index < 2) {
                $finalManuscript = ThesisDocument::create([
                    'user_id' => $student->id,
                    'document_type' => 'final_manuscript',
                    'student_id' => $studentInfo['student_id'],
                    'full_name' => $student->name,
                    'course_program' => $studentInfo['course'],
                    'title' => $thesisTopics[$index] . ' - Final Manuscript',
                    'description' => 'Complete final manuscript with all revisions incorporated.',
                    'abstract' => 'This research successfully developed and implemented a solution for ' . strtolower($thesisTopics[$index]) . '. Results show significant improvements in the target metrics.',
                    'research_area' => $index % 2 == 0 ? 'Artificial Intelligence' : 'Software Development',
                    'adviser_name' => $adviser->name,
                    'adviser_id' => $adviser->id,
                    'final_revisions_completed' => true,
                    'has_plagiarism_report' => true,
                    'plagiarism_percentage' => rand(5, 15) + (rand(0, 99) / 100),
                    'status' => 'approved',
                    'submission_date' => now()->subDays(rand(10, 20)),
                    'reviewed_at' => now()->subDays(rand(5, 15)),
                    'reviewed_by' => $adviser->id,
                    'review_comments' => 'Final manuscript approved. Ready for final defense.',
                ]);

                // Create final defense schedule for students with approved manuscripts
                $finalPanelMembers = [];
                $finalSelectedFaculty = array_rand($faculty, 3);
                foreach ($finalSelectedFaculty as $fIndex) {
                    $finalPanelMembers[] = $faculty[$fIndex]->id;
                }

                PanelAssignment::create([
                    'student_id' => $student->id,
                    'thesis_document_id' => $finalManuscript->id,
                    'thesis_title' => $thesisTopics[$index] . ' - Final Defense',
                    'thesis_description' => 'Final defense for ' . $thesisTopics[$index],
                    'panel_members' => $finalPanelMembers,
                    'panel_chair_id' => $finalPanelMembers[0],
                    'secretary_id' => $finalPanelMembers[1],
                    'defense_date' => $index == 0 ? now()->addDays(7) : now()->addDays(18),
                    'defense_venue' => $index == 0 ? 'Main Conference Room' : 'Graduate Studies Room',
                    'defense_instructions' => 'Final defense presentation (45 minutes). Present complete research findings, methodology, results, conclusions, and recommendations. Prepare to answer in-depth questions from the panel.',
                    'defense_type' => 'final_defense',
                    'status' => 'scheduled',
                    'student_notified' => true,
                    'panel_notified' => true,
                    'notification_sent_at' => now()->subDays(rand(3, 7)),
                    'created_by' => $admin->id,
                ]);
            }
        }

        // Create comprehensive notifications
        $this->command->info('Creating sample notifications...');
        
        // Student Notifications
        // Notification for student 1 (near completion)
        Notification::create([
            'user_id' => $students[0]->id,
            'type' => 'thesis_status_updated',
            'title' => 'Final Manuscript Approved! ✅',
            'message' => 'Your final manuscript has been approved by ' . $faculty[0]->name . '. You may now proceed to final defense scheduling.',
            'priority' => 'high',
            'is_read' => false,
            'created_at' => now()->subMinutes(30),
        ]);

        // Notification for student 2
        Notification::create([
            'user_id' => $students[1]->id,
            'type' => 'defense_scheduled',
            'title' => 'Defense Scheduled',
            'message' => 'Your proposal defense is scheduled for ' . now()->addDays(10)->format('F j, Y'),
            'priority' => 'high',
            'is_read' => false,
            'created_at' => now()->subHours(2),
        ]);

        // Notification for faculty
        Notification::create([
            'user_id' => $faculty[0]->id,
            'type' => 'thesis_submitted',
            'title' => 'New Thesis Document Submitted',
            'message' => 'Student ' . $students[9]->name . ' submitted a new proposal for review.',
            'priority' => 'normal',
            'is_read' => false,
            'created_at' => now()->subHours(5),
        ]);

        // ===== ADMIN NOTIFICATIONS (Many diverse notifications) =====
        
        // Recent form submissions (last few hours)
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'form_submitted',
            'title' => 'New Student Registration Form',
            'message' => 'Student ' . $students[8]->name . ' (' . $studentData[8]['student_id'] . ') submitted a registration form for review.',
            'data' => json_encode(['url' => '/admin/records?tab=forms&status=pending']),
            'priority' => 'normal',
            'is_read' => false,
            'created_at' => now()->subMinutes(15),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'form_submitted',
            'title' => 'New Clearance Form Submission',
            'message' => 'Student ' . $students[7]->name . ' submitted a clearance form. Requires verification.',
            'data' => json_encode(['url' => '/admin/records?tab=forms&status=pending']),
            'priority' => 'normal',
            'is_read' => false,
            'created_at' => now()->subMinutes(45),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'form_submitted',
            'title' => 'Evaluation Form Submitted',
            'message' => 'Student ' . $students[6]->name . ' submitted an evaluation form for thesis progress assessment.',
            'data' => json_encode(['url' => '/admin/records?tab=forms&status=pending']),
            'priority' => 'normal',
            'is_read' => false,
            'created_at' => now()->subHours(1)->subMinutes(30),
        ]);

        // Thesis document submissions
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'thesis_submitted',
            'title' => 'New Thesis Proposal Uploaded',
            'message' => 'Student ' . $students[9]->name . ' uploaded thesis proposal: "' . $thesisTopics[9] . '"',
            'data' => json_encode(['url' => '/admin/records?tab=thesis_documents&status=pending']),
            'priority' => 'high',
            'is_read' => false,
            'created_at' => now()->subHours(3),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'thesis_submitted',
            'title' => 'Final Manuscript Submitted',
            'message' => 'Student ' . $students[0]->name . ' submitted final manuscript for approval. Ready for final defense scheduling.',
            'data' => json_encode(['url' => '/admin/records?tab=thesis_documents&document_type=final_manuscript']),
            'priority' => 'high',
            'is_read' => true,
            'created_at' => now()->subHours(6),
            'read_at' => now()->subHours(5),
        ]);

        // Defense-related notifications
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'defense_upcoming',
            'title' => 'Upcoming Defense This Week',
            'message' => 'Reminder: ' . $students[2]->name . ' has a proposal defense scheduled in ' . now()->diffInDays(now()->addDays(5)) . ' days.',
            'data' => json_encode(['url' => '/admin/panel']),
            'priority' => 'high',
            'is_read' => false,
            'created_at' => now()->subHours(8),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'defense_upcoming',
            'title' => 'Defense Tomorrow - Action Required',
            'message' => 'Student ' . $students[3]->name . ' defense is scheduled for tomorrow. Ensure panel members are notified.',
            'data' => json_encode(['url' => '/admin/panel']),
            'priority' => 'urgent',
            'is_read' => false,
            'created_at' => now()->subHours(12),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'defense_completed',
            'title' => 'Defense Completed Successfully',
            'message' => 'Student ' . $students[0]->name . ' completed proposal defense. Approval sheet pending.',
            'data' => json_encode(['url' => '/admin/panel']),
            'priority' => 'normal',
            'is_read' => true,
            'created_at' => now()->subDays(2),
            'read_at' => now()->subDays(1)->subHours(5),
        ]);

        // Panel assignment requests
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'panel_request',
            'title' => 'Panel Assignment Request',
            'message' => 'Student ' . $students[8]->name . ' submitted panel assignment request for proposal defense.',
            'data' => json_encode(['url' => '/admin/records?tab=thesis_documents&document_type=panel_assignment']),
            'priority' => 'high',
            'is_read' => false,
            'created_at' => now()->subDays(1),
        ]);

        // System activity notifications
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'user_registered',
            'title' => 'New Student Account Created',
            'message' => 'A new student account was created: ' . $students[9]->name . ' (student10@rmt.com)',
            'data' => json_encode(['url' => '/admin/users']),
            'priority' => 'low',
            'is_read' => true,
            'created_at' => now()->subDays(3),
            'read_at' => now()->subDays(2)->subHours(10),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'review_pending',
            'title' => 'Documents Awaiting Review',
            'message' => 'You have 3 thesis documents and 5 academic forms pending review for more than 2 days.',
            'data' => json_encode(['url' => '/admin/records']),
            'priority' => 'normal',
            'is_read' => false,
            'created_at' => now()->subDays(1)->subHours(6),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'document_approved',
            'title' => 'Faculty Approved Thesis Proposal',
            'message' => $faculty[1]->name . ' approved thesis proposal for student ' . $students[5]->name . '.',
            'data' => json_encode(['url' => '/admin/records?tab=thesis_documents&status=approved']),
            'priority' => 'normal',
            'is_read' => true,
            'created_at' => now()->subDays(2)->subHours(3),
            'read_at' => now()->subDays(1)->subHours(8),
        ]);

        // Older notifications (read)
        Notification::create([
            'user_id' => $admin->id,
            'type' => 'system_update',
            'title' => 'System Maintenance Completed',
            'message' => 'Database backup and system optimization completed successfully. All systems operational.',
            'data' => json_encode(['url' => '/admin/reports']),
            'priority' => 'low',
            'is_read' => true,
            'created_at' => now()->subDays(5),
            'read_at' => now()->subDays(4)->subHours(15),
        ]);

        Notification::create([
            'user_id' => $admin->id,
            'type' => 'form_approved',
            'title' => 'Multiple Forms Approved',
            'message' => 'You approved 5 registration forms today. Total pending forms: 3',
            'data' => json_encode(['url' => '/admin/records?tab=forms']),
            'priority' => 'low',
            'is_read' => true,
            'created_at' => now()->subDays(4)->subHours(8),
            'read_at' => now()->subDays(4)->subHours(2),
        ]);

        $this->command->info('✅ Comprehensive seeding completed successfully!');
        $this->command->newLine();
        $this->command->info('=== LOGIN CREDENTIALS (All passwords: "password") ===');
        $this->command->info('Admin: admin@rmt.com');
        $this->command->newLine();
        $this->command->info('Faculty:');
        for ($i = 1; $i <= 5; $i++) {
            $this->command->info("  - faculty{$i}@rmt.com ({$facultyNames[$i-1]})");
        }
        $this->command->newLine();
        $this->command->info('Students:');
        for ($i = 1; $i <= 10; $i++) {
            $this->command->info("  - student{$i}@rmt.com ({$studentData[$i-1]['name']})");
        }
        $this->command->newLine();
        $this->command->info('📊 Summary:');
        $this->command->info('  - 1 Admin');
        $this->command->info('  - 5 Faculty members');
        $this->command->info('  - 10 Students with various thesis progress levels');
        $this->command->info('  - ' . AcademicForm::count() . ' Academic forms');
        $this->command->info('  - ' . ThesisDocument::count() . ' Thesis documents');
        $this->command->info('  - ' . PanelAssignment::count() . ' Panel assignments');
        $this->command->info('  - ' . Notification::count() . ' Notifications');
    }
}

