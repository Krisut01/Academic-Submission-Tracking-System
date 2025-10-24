<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PanelAssignment;

class UpdateDefenseDate extends Command
{
    protected $signature = 'update:defense-date {id} {days_ago}';
    protected $description = 'Update defense date to X days ago';

    public function handle()
    {
        $id = $this->argument('id');
        $daysAgo = $this->argument('days_ago');
        
        $assignment = PanelAssignment::find($id);
        if (!$assignment) {
            $this->error("Panel assignment with ID {$id} not found");
            return;
        }
        
        $newDate = now()->subDays($daysAgo);
        $assignment->defense_date = $newDate;
        $assignment->save();
        
        $this->info("Updated defense date for assignment {$id} to: {$newDate->format('Y-m-d H:i:s')}");
        $this->info("Now: " . now()->format('Y-m-d H:i:s'));
        $this->info("Defense date is " . ($newDate <= now() ? 'in the past' : 'in the future'));
    }
}
