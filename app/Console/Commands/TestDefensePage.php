<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PanelAssignment;
use App\Models\User;

class TestDefensePage extends Command
{
    protected $signature = 'test:defense-page {assignment_id} {student_id?}';
    protected $description = 'Test defense page with assignment ID and optional student ID';

    public function handle()
    {
        $assignmentId = $this->argument('assignment_id');
        $studentId = $this->argument('student_id');
        
        $this->info("Testing defense page for assignment ID: {$assignmentId}");
        
        // Get the assignment
        $assignment = PanelAssignment::find($assignmentId);
        if (!$assignment) {
            $this->error("Panel assignment with ID {$assignmentId} not found");
            return;
        }
        
        $this->info("Assignment found:");
        $this->line("ID: {$assignment->id}");
        $this->line("Student ID: {$assignment->student_id}");
        $this->line("Status: {$assignment->status}");
        $this->line("Defense Date: " . ($assignment->defense_date ? $assignment->defense_date->format('Y-m-d H:i:s') : 'Not set'));
        
        // Check if student has access
        if ($studentId) {
            if ($assignment->student_id != $studentId) {
                $this->error("Student ID {$studentId} does not have access to this assignment (belongs to student {$assignment->student_id})");
                return;
            }
            $this->info("Student {$studentId} has access to this assignment");
        }
        
        // Check if defense date has passed
        $now = now();
        $defenseDate = $assignment->defense_date;
        
        if ($defenseDate) {
            if ($defenseDate <= $now) {
                $this->info("Defense date has passed - student can mark as completed");
            } else {
                $this->info("Defense date is in the future - student cannot mark as completed yet");
            }
        }
        
        // Check status
        if ($assignment->status === 'scheduled') {
            $this->info("Status is 'scheduled' - showing defense details");
        } elseif ($assignment->status === 'completed') {
            $this->info("Status is 'completed' - showing approval sheet button");
        } else {
            $this->info("Status is '{$assignment->status}' - showing appropriate content");
        }
    }
}
