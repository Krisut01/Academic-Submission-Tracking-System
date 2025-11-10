<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Notification;

class FixNotificationMessages extends Command
{
    protected $signature = 'fix:notification-messages';
    protected $description = 'Fix notification messages that contain invalid date data';

    public function handle()
    {
        $this->info('Checking notifications for invalid messages...');

        $notifications = Notification::where('message', 'like', '%days away%')
            ->orWhere('message', 'like', '%-%.%')
            ->get();
        
        $fixed = 0;

        foreach ($notifications as $notification) {
            // Check if message contains invalid date/number data
            if (preg_match('/-?\d+\.\d+\s*days?\s*(away)?/', $notification->message)) {
                $this->warn("Found invalid message in notification ID {$notification->id}");
                $this->line("Original: " . substr($notification->message, 0, 150));
                
                // Clean up the message by removing the invalid date text
                $cleanMessage = preg_replace('/Description\s+-?\d+\.\d+\s+days\s+away\s*/i', '', $notification->message);
                $cleanMessage = preg_replace('/-?\d+\.\d+\s+days\s+away/i', '', $cleanMessage);
                $cleanMessage = preg_replace('/:\s+$/', '', $cleanMessage); // Remove trailing colon and spaces
                $cleanMessage = trim($cleanMessage);
                
                $notification->message = $cleanMessage;
                $notification->save();
                
                $fixed++;
                $this->info("✓ Fixed to: " . substr($cleanMessage, 0, 150));
                $this->line('');
            }
        }

        $this->info("\nTotal notifications checked: {$notifications->count()}");
        $this->info("Total notifications fixed: {$fixed}");
        
        return 0;
    }
}

