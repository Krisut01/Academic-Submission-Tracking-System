-- Test Data Insert Script for RMT Generation System
-- This script creates students with different document statuses assigned to adviser (ID: 8)

-- First, let's insert some test students
INSERT INTO users (name, email, password, role, email_verified_at, created_at, updated_at) VALUES
('John Smith', 'john.smith@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('Sarah Johnson', 'sarah.johnson@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('Michael Brown', 'michael.brown@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('Emily Davis', 'emily.davis@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('David Wilson', 'david.wilson@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('Lisa Anderson', 'lisa.anderson@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('Robert Taylor', 'robert.taylor@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW()),
('Jennifer Martinez', 'jennifer.martinez@student.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', NOW(), NOW(), NOW());

-- Get the inserted student IDs (assuming they are sequential starting from the next available ID)
-- You may need to adjust these IDs based on your current user table state
SET @john_id = (SELECT id FROM users WHERE email = 'john.smith@student.com');
SET @sarah_id = (SELECT id FROM users WHERE email = 'sarah.johnson@student.com');
SET @michael_id = (SELECT id FROM users WHERE email = 'michael.brown@student.com');
SET @emily_id = (SELECT id FROM users WHERE email = 'emily.davis@student.com');
SET @david_id = (SELECT id FROM users WHERE email = 'david.wilson@student.com');
SET @lisa_id = (SELECT id FROM users WHERE email = 'lisa.anderson@student.com');
SET @robert_id = (SELECT id FROM users WHERE email = 'robert.taylor@student.com');
SET @jennifer_id = (SELECT id FROM users WHERE email = 'jennifer.martinez@student.com');

-- Insert thesis documents with different statuses for each student
-- 1. John Smith - Proposal (PENDING - needs review)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    abstract, research_area, adviser_name, adviser_id, status, submission_date, created_at, updated_at
) VALUES (
    @john_id, 'proposal', 'STU-2024-001', 'John Smith', 'Bachelor of Science in Medical Technology',
    'Development of Automated Blood Cell Counter Using Machine Learning',
    'A comprehensive study on developing an automated blood cell counter using machine learning algorithms for improved accuracy and efficiency in medical diagnostics.',
    'This research aims to develop an automated blood cell counter using machine learning algorithms to improve accuracy and efficiency in medical diagnostics. The system will utilize image processing techniques and deep learning models to identify and count different types of blood cells.',
    'Medical Technology, Machine Learning, Image Processing',
    'Dr. Maria Santos', 8, 'pending', '2024-10-20', NOW(), NOW()
);

-- 2. Sarah Johnson - Proposal (UNDER_REVIEW - currently being reviewed)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    abstract, research_area, adviser_name, adviser_id, status, submission_date, reviewed_at, created_at, updated_at
) VALUES (
    @sarah_id, 'proposal', 'STU-2024-002', 'Sarah Johnson', 'Bachelor of Science in Medical Technology',
    'Effectiveness of Point-of-Care Testing in Rural Healthcare Settings',
    'An investigation into the effectiveness and implementation challenges of point-of-care testing in rural healthcare settings.',
    'This study examines the effectiveness of point-of-care testing devices in rural healthcare settings, focusing on accuracy, cost-effectiveness, and implementation challenges.',
    'Medical Technology, Rural Healthcare, Point-of-Care Testing',
    'Dr. Maria Santos', 8, 'under_review', '2024-10-18', NOW(), NOW(), NOW()
);

-- 3. Michael Brown - Proposal (RETURNED_FOR_REVISION - needs revision)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    abstract, research_area, adviser_name, adviser_id, status, submission_date, reviewed_at, review_comments, created_at, updated_at
) VALUES (
    @michael_id, 'proposal', 'STU-2024-003', 'Michael Brown', 'Bachelor of Science in Medical Technology',
    'Quality Control Measures in Clinical Laboratory Testing',
    'A comprehensive analysis of quality control measures and their impact on clinical laboratory testing accuracy.',
    'This research focuses on evaluating various quality control measures in clinical laboratory testing to improve accuracy and reliability of test results.',
    'Medical Technology, Quality Control, Clinical Laboratory',
    'Dr. Maria Santos', 8, 'returned_for_revision', '2024-10-15', '2024-10-22', 'Please revise the methodology section and provide more detailed statistical analysis. Also, include recent literature from 2023-2024.', NOW(), NOW()
);

-- 4. Emily Davis - Approval Sheet (PENDING - needs approval)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    adviser_name, adviser_id, panel_members, approval_date, defense_date, defense_type, defense_venue,
    status, submission_date, created_at, updated_at
) VALUES (
    @emily_id, 'approval_sheet', 'STU-2024-004', 'Emily Davis', 'Bachelor of Science in Medical Technology',
    'Implementation of Digital Pathology in Cancer Diagnosis',
    'Approval sheet for thesis on digital pathology implementation in cancer diagnosis.',
    'Dr. Maria Santos', 8, '["Dr. John Wilson", "Dr. Lisa Chen", "Dr. Robert Garcia"]', '2024-10-25', '2024-11-15', 'Proposal Defense', 'Room 301, Science Building',
    'pending', '2024-10-23', NOW(), NOW()
);

-- 5. David Wilson - Panel Assignment (PENDING - needs panel assignment review)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    adviser_name, adviser_id, panel_members, defense_date, defense_type, defense_venue, requested_schedule,
    status, submission_date, created_at, updated_at
) VALUES (
    @david_id, 'panel_assignment', 'STU-2024-005', 'David Wilson', 'Bachelor of Science in Medical Technology',
    'Biomarker Analysis in Cardiovascular Disease Detection',
    'Panel assignment for thesis on biomarker analysis in cardiovascular disease detection.',
    'Dr. Maria Santos', 8, '["Dr. Sarah Lee", "Dr. Michael Rodriguez", "Dr. Jennifer Kim"]', '2024-11-20', 'Final Defense', 'Room 205, Medical Building', '2024-11-20 2:00 PM',
    'pending', '2024-10-24', NOW(), NOW()
);

-- 6. Lisa Anderson - Final Manuscript (UNDER_REVIEW - final manuscript review)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    adviser_name, adviser_id, final_revisions_completed, has_plagiarism_report, plagiarism_percentage,
    status, submission_date, reviewed_at, created_at, updated_at
) VALUES (
    @lisa_id, 'final_manuscript', 'STU-2024-006', 'Lisa Anderson', 'Bachelor of Science in Medical Technology',
    'Advanced Techniques in Molecular Diagnostics',
    'Final manuscript for thesis on advanced techniques in molecular diagnostics.',
    'Dr. Maria Santos', 8, true, true, 5.2,
    'under_review', '2024-10-22', NOW(), NOW(), NOW()
);

-- 7. Robert Taylor - Proposal (APPROVED - already approved)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    abstract, research_area, adviser_name, adviser_id, status, submission_date, reviewed_at, reviewed_by, review_comments, created_at, updated_at
) VALUES (
    @robert_id, 'proposal', 'STU-2024-007', 'Robert Taylor', 'Bachelor of Science in Medical Technology',
    'Innovations in Medical Imaging Technology',
    'A study on recent innovations in medical imaging technology and their clinical applications.',
    'This research explores recent innovations in medical imaging technology, focusing on their clinical applications and potential impact on patient care.',
    'Medical Technology, Medical Imaging, Innovation',
    'Dr. Maria Santos', 8, 'approved', '2024-10-10', '2024-10-17', 8, 'Excellent proposal with clear methodology and strong research foundation. Approved for defense scheduling.', NOW(), NOW()
);

-- 8. Jennifer Martinez - Approval Sheet (RETURNED_FOR_REVISION - needs revision)
INSERT INTO thesis_documents (
    user_id, document_type, student_id, full_name, course_program, title, description,
    adviser_name, adviser_id, panel_members, approval_date, defense_date, defense_type, defense_venue,
    status, submission_date, reviewed_at, review_comments, created_at, updated_at
) VALUES (
    @jennifer_id, 'approval_sheet', 'STU-2024-008', 'Jennifer Martinez', 'Bachelor of Science in Medical Technology',
    'Telemedicine Applications in Patient Monitoring',
    'Approval sheet for thesis on telemedicine applications in patient monitoring.',
    'Dr. Maria Santos', 8, '["Dr. David Park", "Dr. Maria Gonzalez", "Dr. James Wilson"]', '2024-10-28', '2024-11-25', 'Final Defense', 'Room 150, Technology Building',
    'returned_for_revision', '2024-10-21', '2024-10-25', 'Please update the panel member information and confirm the defense venue availability.', NOW(), NOW()
);

-- Insert some activity logs for these documents
INSERT INTO activity_logs (user_id, activity_type, description, created_at, updated_at) VALUES
(@john_id, 'document_submission', 'Submitted proposal document for review', NOW(), NOW()),
(@sarah_id, 'document_review', 'Document is currently under review by faculty', NOW(), NOW()),
(@michael_id, 'document_revision', 'Document returned for revision with feedback', NOW(), NOW()),
(@emily_id, 'document_submission', 'Submitted approval sheet for panel review', NOW(), NOW()),
(@david_id, 'document_submission', 'Submitted panel assignment for defense scheduling', NOW(), NOW()),
(@lisa_id, 'document_review', 'Final manuscript is under review', NOW(), NOW()),
(@robert_id, 'document_approval', 'Proposal document approved by faculty', NOW(), NOW()),
(@jennifer_id, 'document_revision', 'Approval sheet returned for revision', NOW(), NOW());

-- Insert notifications for the adviser (ID: 8) about these documents
INSERT INTO notifications (user_id, type, title, message, is_read, created_at, updated_at) VALUES
(8, 'document_review', 'New Document Submission', 'John Smith has submitted a proposal document for your review.', false, NOW(), NOW()),
(8, 'document_review', 'Document Under Review', 'Sarah Johnson\'s proposal is currently under review.', false, NOW(), NOW()),
(8, 'document_revision', 'Document Needs Revision', 'Michael Brown\'s proposal has been returned for revision.', false, NOW(), NOW()),
(8, 'document_approval', 'Approval Sheet Submitted', 'Emily Davis has submitted an approval sheet for review.', false, NOW(), NOW()),
(8, 'panel_assignment', 'Panel Assignment Request', 'David Wilson has submitted a panel assignment for defense scheduling.', false, NOW(), NOW()),
(8, 'document_review', 'Final Manuscript Review', 'Lisa Anderson\'s final manuscript is under review.', false, NOW(), NOW()),
(8, 'document_approval', 'Document Approved', 'Robert Taylor\'s proposal has been approved.', true, NOW(), NOW()),
(8, 'document_revision', 'Approval Sheet Revision', 'Jennifer Martinez\'s approval sheet needs revision.', false, NOW(), NOW());

-- Summary of inserted data:
-- 8 students with different document statuses
-- 8 thesis documents (2 pending, 2 under_review, 2 returned_for_revision, 1 approved, 1 approved)
-- 8 activity logs
-- 8 notifications for the adviser

-- To verify the data, you can run these queries:
-- SELECT u.name, u.email, td.document_type, td.status, td.title FROM users u JOIN thesis_documents td ON u.id = td.user_id WHERE td.adviser_id = 8;
-- SELECT COUNT(*) as total_documents, status FROM thesis_documents WHERE adviser_id = 8 GROUP BY status;
-- SELECT COUNT(*) as unread_notifications FROM notifications WHERE user_id = 8 AND is_read = false;
