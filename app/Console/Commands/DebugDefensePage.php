<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PanelAssignment;

class DebugDefensePage extends Command
{
    protected $signature = 'debug:defense-page {assignment_id}';
    protected $description = 'Debug defense page data';

    public function handle()
    {
        $assignmentId = $this->argument('assignment_id');
        
        $assignment = PanelAssignment::find($assignmentId);
        if (!$assignment) {
            $this->error("Panel assignment with ID {$assignmentId} not found");
            return;
        }
        
        $this->info("=== DEFENSE PAGE DEBUG ===");
        $this->line("Assignment ID: {$assignment->id}");
        $this->line("Student ID: {$assignment->student_id}");
        $this->line("Defense Type: {$assignment->defense_type}");
        $this->line("Defense Type Label: {$assignment->defense_type_label}");
        $this->line("Status: {$assignment->status}");
        $this->line("Defense Date: {$assignment->defense_date}");
        $this->line("Current Time: " . now());
        $this->line("Defense Date <= Now: " . ($assignment->defense_date <= now() ? 'YES' : 'NO'));
        $this->line("Status === 'scheduled': " . ($assignment->status === 'scheduled' ? 'YES' : 'NO'));
        $this->line("Should Show Button: " . ($assignment->status === 'scheduled' && $assignment->defense_date <= now() ? 'YES' : 'NO'));
        
        // Check if there are any issues with the data
        if (!$assignment->defense_type) {
            $this->warn("WARNING: Defense type is null!");
        }
        
        if (!$assignment->defense_date) {
            $this->warn("WARNING: Defense date is null!");
        }
        
        if ($assignment->status !== 'scheduled') {
            $this->warn("WARNING: Status is not 'scheduled' - it's '{$assignment->status}'");
        }
        
        if ($assignment->defense_date > now()) {
            $this->warn("WARNING: Defense date is in the future!");
        }
    }
}
