<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PanelAssignment;

class TestPanelAssignment extends Command
{
    protected $signature = 'test:panel-assignment {id?}';
    protected $description = 'Test panel assignment by ID or show all assignments';

    public function handle()
    {
        $id = $this->argument('id');
        
        if ($id) {
            $assignment = PanelAssignment::find($id);
            
            if (!$assignment) {
                $this->error("Panel assignment with ID {$id} not found");
                return;
            }
            
            $this->info("Panel Assignment Found:");
            $this->line("ID: {$assignment->id}");
            $this->line("Student ID: {$assignment->student_id}");
            $this->line("Thesis Title: {$assignment->thesis_title}");
            $this->line("Status: {$assignment->status}");
            $this->line("Defense Date: " . ($assignment->defense_date ? $assignment->defense_date->format('Y-m-d H:i:s') : 'Not set'));
            $this->line("Defense Type: {$assignment->defense_type}");
            $this->line("Panel Members: " . json_encode($assignment->panel_member_ids));
            $this->line("Panel Chair ID: {$assignment->panel_chair_id}");
            $this->line("Secretary ID: {$assignment->secretary_id}");
        } else {
            $this->info("All Panel Assignments:");
            $assignments = PanelAssignment::with(['student', 'panelChair'])->get();
            foreach ($assignments as $assignment) {
                $this->line("ID: {$assignment->id} | Student: {$assignment->student->name} | Status: {$assignment->status} | Defense: " . ($assignment->defense_date ? $assignment->defense_date->format('Y-m-d') : 'Not set'));
            }
        }
    }
}
