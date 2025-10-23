<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class PanelAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'thesis_document_id',
        'thesis_title',
        'thesis_description',
        'panel_members',
        'panel_chair_id',
        'secretary_id',
        'defense_date',
        'defense_venue',
        'defense_instructions',
        'status',
        'result',
        'panel_feedback',
        'final_grade',
        'student_notified',
        'panel_notified',
        'notification_sent_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'panel_members' => 'array',
        'defense_date' => 'datetime',
        'notification_sent_at' => 'datetime',
        'student_notified' => 'boolean',
        'panel_notified' => 'boolean',
        'final_grade' => 'decimal:2',
    ];

    /**
     * Get the student for this panel assignment
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the thesis document for this panel assignment
     */
    public function thesisDocument(): BelongsTo
    {
        return $this->belongsTo(ThesisDocument::class);
    }

    /**
     * Get the panel chair
     */
    public function panelChair(): BelongsTo
    {
        return $this->belongsTo(User::class, 'panel_chair_id');
    }

    /**
     * Get the secretary
     */
    public function secretary(): BelongsTo
    {
        return $this->belongsTo(User::class, 'secretary_id');
    }

    /**
     * Get the user who created this assignment
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated this assignment
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get panel members as User models
     */
    public function getPanelMembersAttribute()
    {
        if (empty($this->attributes['panel_members'])) {
            return collect();
        }

        $memberIds = json_decode($this->attributes['panel_members'], true);
        return User::whereIn('id', $memberIds)->get();
    }

    /**
     * Get panel member IDs as array
     */
    public function getPanelMemberIdsAttribute()
    {
        if (empty($this->attributes['panel_members'])) {
            return [];
        }

        return json_decode($this->attributes['panel_members'], true) ?? [];
    }

    /**
     * Get status color for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'blue',
            'completed' => 'green',
            'postponed' => 'yellow',
            'cancelled' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get result color for UI display
     */
    public function getResultColorAttribute(): string
    {
        return match($this->result) {
            'passed' => 'green',
            'failed' => 'red',
            'conditional' => 'yellow',
            'pending' => 'blue',
            default => 'gray'
        };
    }

    /**
     * Check if defense is upcoming (within 7 days)
     */
    public function getIsUpcomingAttribute(): bool
    {
        return $this->defense_date && 
               $this->defense_date->isFuture() && 
               $this->defense_date->diffInDays(now()) <= 7;
    }

    /**
     * Check if defense is overdue
     */
    public function getIsOverdueAttribute(): bool
    {
        return $this->defense_date && 
               $this->defense_date->isPast() && 
               $this->status !== 'completed';
    }

    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for upcoming defenses
     */
    public function scopeUpcoming($query)
    {
        return $query->where('defense_date', '>', now())
                    ->where('defense_date', '<=', now()->addDays(7))
                    ->where('status', 'scheduled');
    }

    /**
     * Scope for overdue defenses
     */
    public function scopeOverdue($query)
    {
        return $query->where('defense_date', '<', now())
                    ->where('status', '!=', 'completed');
    }

    /**
     * Send notifications to student and panel members
     */
    public function sendNotifications(): void
    {
        $secretaryInfo = $this->secretary ? " with {$this->secretary->name} as secretary" : "";
        $studentNotification = [
            'title' => 'Defense Scheduled',
            'message' => "Your thesis defense has been scheduled for {$this->defense_date->format('M d, Y h:i A')}{$secretaryInfo}",
            'data' => [
                'panel_assignment_id' => $this->id,
                'defense_date' => $this->defense_date,
                'venue' => $this->defense_venue,
                'secretary_name' => $this->secretary?->name,
                'url' => route('student.thesis.index'),
            ]
        ];

        Notification::createForUser(
            $this->student_id,
            'defense_scheduled',
            $studentNotification['title'],
            $studentNotification['message'],
            $studentNotification['data'],
            get_class($this),
            $this->id,
            'high'
        );

        // Notify panel members
        if (!empty($this->panel_members)) {
            $secretaryInfo = $this->secretary ? " Secretary: {$this->secretary->name}." : "";
            $panelNotification = [
                'title' => 'Panel Assignment',
                'message' => "You have been assigned to review {$this->student->name}'s thesis defense.{$secretaryInfo}",
                'data' => [
                    'panel_assignment_id' => $this->id,
                    'student_name' => $this->student->name,
                    'defense_date' => $this->defense_date,
                    'venue' => $this->defense_venue,
                    'secretary_name' => $this->secretary?->name,
                    'url' => route('faculty.thesis.reviews'),
                ]
            ];

            Notification::createForUsers(
                json_decode($this->attributes['panel_members'], true),
                'panel_assignment',
                $panelNotification['title'],
                $panelNotification['message'],
                $panelNotification['data'],
                get_class($this),
                $this->id,
                'high'
            );
        }

        $this->update([
            'student_notified' => true,
            'panel_notified' => true,
            'notification_sent_at' => now(),
        ]);
    }
}
