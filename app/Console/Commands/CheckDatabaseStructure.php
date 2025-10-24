<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PanelAssignment;
use Illuminate\Support\Facades\Schema;

class CheckDatabaseStructure extends Command
{
    protected $signature = 'check:database-structure';
    protected $description = 'Check database structure for panel assignments';

    public function handle()
    {
        $this->info("=== DATABASE STRUCTURE CHECK ===");
        
        // Check if table exists
        if (!Schema::hasTable('panel_assignments')) {
            $this->error("panel_assignments table does not exist!");
            return;
        }
        
        $this->info("✓ panel_assignments table exists");
        
        // Get column information
        $columns = Schema::getColumnListing('panel_assignments');
        $this->info("Columns: " . implode(', ', $columns));
        
        // Check specific columns
        $requiredColumns = ['defense_type', 'defense_date', 'status', 'student_id'];
        foreach ($requiredColumns as $column) {
            if (in_array($column, $columns)) {
                $this->info("✓ {$column} column exists");
            } else {
                $this->error("✗ {$column} column missing!");
            }
        }
        
        // Check sample data
        $assignment = PanelAssignment::find(14);
        if ($assignment) {
            $this->info("=== SAMPLE DATA ===");
            $this->line("ID: {$assignment->id}");
            $this->line("Defense Type: " . ($assignment->defense_type ?? 'NULL'));
            $this->line("Defense Date: " . ($assignment->defense_date ?? 'NULL'));
            $this->line("Status: " . ($assignment->status ?? 'NULL'));
            $this->line("Student ID: " . ($assignment->student_id ?? 'NULL'));
            
            // Check if defense_type is in fillable
            $fillable = $assignment->getFillable();
            $this->line("Fillable fields: " . implode(', ', $fillable));
            $this->line("Defense type in fillable: " . (in_array('defense_type', $fillable) ? 'YES' : 'NO'));
        }
    }
}
