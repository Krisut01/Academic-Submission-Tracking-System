<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
        'defense_type',
        'status',
        // 'result', 'panel_feedback', 'final_grade' - Faculty only fields
        'student_notified',
        'panel_notified',
        'notification_sent_at',
        'completed_at',
        'completed_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'panel_members' => 'array',
        'defense_date' => 'datetime',
        'notification_sent_at' => 'datetime',
        'completed_at' => 'datetime',
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
     * Get the user who marked this defense as completed
     */
    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get all reviews for this panel assignment
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(PanelAssignmentReview::class);
    }

    /**
     * Get all evaluations for this panel assignment
     */
    public function evaluations(): HasMany
    {
        return $this->hasMany(PanelEvaluation::class);
    }

    /**
     * Get panel member IDs as array
     */
    public function getPanelMemberIdsAttribute(): array
    {
        return $this->panel_members ?? [];
    }

    /**
     * Get all panel members (users)
     */
    public function panelMembers(): HasMany
    {
        return $this->hasMany(User::class, 'id', 'panel_member_ids');
    }

    /**
     * Check if all panel members have completed their reviews
     */
    public function allReviewsCompleted(): bool
    {
        $totalPanelMembers = count($this->panel_member_ids ?? []);
        $completedReviews = $this->reviews()->whereIn('status', ['approved', 'rejected', 'needs_revision'])->count();
        
        return $totalPanelMembers > 0 && $completedReviews >= $totalPanelMembers;
    }

    /**
     * Get overall approval status
     */
    public function getOverallApprovalStatusAttribute(): string
    {
        if (!$this->allReviewsCompleted()) {
            return 'pending';
        }

        $approvals = $this->reviews()->where('status', 'approved')->count();
        $total = $this->reviews()->whereIn('status', ['approved', 'rejected', 'needs_revision'])->count();

        if ($approvals === $total) {
            return 'approved';
        } elseif ($approvals > 0) {
            return 'conditional';
        } else {
            return 'rejected';
        }
    }

    /**
     * Get defense type label
     */
    public function getDefenseTypeLabelAttribute(): string
    {
        return match($this->defense_type) {
            'proposal_defense' => 'Proposal Defense',
            'final_defense' => 'Final Defense',
            'redefense' => 'Re-defense',
            'oral_defense' => 'Oral Defense',
            'thesis_defense' => 'Thesis Defense',
            default => ucfirst(str_replace('_', ' ', $this->defense_type ?? 'Defense'))
        };
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
     * Get status label for display
     */
    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'completed' && $this->completed_at) {
            return $this->defense_type_label . ' Defended';
        }
        
        return match($this->status) {
            'scheduled' => 'Scheduled',
            'completed' => 'Completed',
            'postponed' => 'Postponed',
            'cancelled' => 'Cancelled',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get formatted completion date
     */
    public function getFormattedCompletedAtAttribute(): ?string
    {
        if (!$this->completed_at) {
            return null;
        }
        
        try {
            return $this->completed_at->format('M j, Y g:i A');
        } catch (\Exception $e) {
            return 'Unknown';
        }
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
     * Get comprehensive approval status for students
     */
    public function getApprovalStatus(): array
    {
        $approvalStatus = [
            'adviser' => [
                'name' => $this->thesisDocument?->adviser?->name ?? 'Not Assigned',
                'email' => $this->thesisDocument?->adviser?->email ?? null,
                'status' => 'pending',
                'approved_at' => null,
                'comments' => null,
                'role' => 'Adviser'
            ],
            'panel_chair' => [
                'name' => $this->panelChair?->name ?? 'Not Assigned',
                'email' => $this->panelChair?->email ?? null,
                'status' => 'pending',
                'approved_at' => null,
                'comments' => null,
                'role' => 'Panel Chair'
            ],
            'secretary' => [
                'name' => $this->secretary?->name ?? 'Not Assigned',
                'email' => $this->secretary?->email ?? null,
                'status' => 'pending',
                'approved_at' => null,
                'comments' => null,
                'role' => 'Secretary'
            ],
            'panel_members' => [],
            'overall_status' => 'pending',
            'completion_percentage' => 0
        ];

        // Check adviser approval (check both systems)
        if ($this->thesisDocument?->adviser_id) {
            // First check panel assignment reviews
            $adviserReview = \App\Models\PanelAssignmentReview::where('panel_assignment_id', $this->id)
                ->where('reviewer_id', $this->thesisDocument->adviser_id)
                ->where('reviewer_role', 'adviser')
                ->first();
            
            if ($adviserReview) {
                $approvalStatus['adviser']['status'] = $adviserReview->status;
                $approvalStatus['adviser']['approved_at'] = $adviserReview->reviewed_at;
                $approvalStatus['adviser']['comments'] = $adviserReview->review_comments;
            } elseif ($this->thesisDocument->reviewed_by == $this->thesisDocument->adviser_id) {
                // Check thesis document approval if no panel review exists
                $approvalStatus['adviser']['status'] = $this->thesisDocument->status === 'approved' ? 'approved' : 'pending';
                $approvalStatus['adviser']['approved_at'] = $this->thesisDocument->reviewed_at;
                $approvalStatus['adviser']['comments'] = $this->thesisDocument->review_comments;
            }
        }

        // Check panel chair approval (check both systems)
        if ($this->panel_chair_id) {
            $chairReview = \App\Models\PanelAssignmentReview::where('panel_assignment_id', $this->id)
                ->where('reviewer_id', $this->panel_chair_id)
                ->where('reviewer_role', 'panel_chair')
                ->first();
            
            if ($chairReview) {
                $approvalStatus['panel_chair']['status'] = $chairReview->status;
                $approvalStatus['panel_chair']['approved_at'] = $chairReview->reviewed_at;
                $approvalStatus['panel_chair']['comments'] = $chairReview->review_comments;
            } elseif ($this->thesisDocument?->reviewed_by == $this->panel_chair_id) {
                // Check thesis document approval if no panel review exists
                $approvalStatus['panel_chair']['status'] = $this->thesisDocument->status === 'approved' ? 'approved' : 'pending';
                $approvalStatus['panel_chair']['approved_at'] = $this->thesisDocument->reviewed_at;
                $approvalStatus['panel_chair']['comments'] = $this->thesisDocument->review_comments;
            }
        }

        // Check secretary approval (check both systems)
        if ($this->secretary_id) {
            $secretaryReview = \App\Models\PanelAssignmentReview::where('panel_assignment_id', $this->id)
                ->where('reviewer_id', $this->secretary_id)
                ->where('reviewer_role', 'panel_member')
                ->first();
            
            if ($secretaryReview) {
                $approvalStatus['secretary']['status'] = $secretaryReview->status;
                $approvalStatus['secretary']['approved_at'] = $secretaryReview->reviewed_at;
                $approvalStatus['secretary']['comments'] = $secretaryReview->review_comments;
            } elseif ($this->thesisDocument?->reviewed_by == $this->secretary_id) {
                // Check thesis document approval if no panel review exists
                $approvalStatus['secretary']['status'] = $this->thesisDocument->status === 'approved' ? 'approved' : 'pending';
                $approvalStatus['secretary']['approved_at'] = $this->thesisDocument->reviewed_at;
                $approvalStatus['secretary']['comments'] = $this->thesisDocument->review_comments;
            }
        }

        // Check panel members approval (check both systems)
        if (!empty($this->panel_member_ids)) {
            $panelMemberReviews = \App\Models\PanelAssignmentReview::where('panel_assignment_id', $this->id)
                ->whereIn('reviewer_id', $this->panel_member_ids)
                ->where('reviewer_role', 'panel_member')
                ->get();

            foreach ($this->panel_member_ids as $memberId) {
                $member = \App\Models\User::find($memberId);
                $memberReview = $panelMemberReviews->where('reviewer_id', $memberId)->first();
                
                // Check if member approved via thesis document system
                $thesisApproval = null;
                if ($this->thesisDocument?->reviewed_by == $memberId) {
                    $thesisApproval = [
                        'status' => $this->thesisDocument->status === 'approved' ? 'approved' : 'pending',
                        'approved_at' => $this->thesisDocument->reviewed_at,
                        'comments' => $this->thesisDocument->review_comments
                    ];
                }
                
                $approvalStatus['panel_members'][] = [
                    'name' => $member?->name ?? 'Unknown',
                    'email' => $member?->email ?? null,
                    'status' => $memberReview?->status ?? $thesisApproval['status'] ?? 'pending',
                    'approved_at' => $memberReview?->reviewed_at ?? $thesisApproval['approved_at'] ?? null,
                    'comments' => $memberReview?->review_comments ?? $thesisApproval['comments'] ?? null,
                    'role' => 'Panel Member'
                ];
            }
        }

        // Calculate overall status and completion percentage
        $allApprovals = array_merge(
            [$approvalStatus['adviser'], $approvalStatus['panel_chair'], $approvalStatus['secretary']],
            $approvalStatus['panel_members']
        );

        $approvedCount = 0;
        $totalCount = count($allApprovals);
        
        foreach ($allApprovals as $approval) {
            if ($approval['status'] === 'approved') {
                $approvedCount++;
            }
        }

        $approvalStatus['completion_percentage'] = $totalCount > 0 ? round(($approvedCount / $totalCount) * 100, 1) : 0;
        
        if ($approvedCount === $totalCount && $totalCount > 0) {
            $approvalStatus['overall_status'] = 'approved';
        } elseif ($approvedCount > 0) {
            $approvalStatus['overall_status'] = 'partial';
        } else {
            $approvalStatus['overall_status'] = 'pending';
        }

        return $approvalStatus;
    }

    /**
     * Get approval status color for UI display
     */
    public function getApprovalStatusColorAttribute(): string
    {
        $status = $this->getApprovalStatus();
        return match($status['overall_status']) {
            'approved' => 'green',
            'partial' => 'yellow',
            'pending' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Send notifications to student and panel members
     */
    public function sendNotifications(): void
    {
        $secretaryInfo = $this->secretary ? " with {$this->secretary->name} as secretary" : "";
        $defenseTypeLabel = $this->defense_type_label;
        
        $studentNotification = [
            'title' => "{$defenseTypeLabel} Scheduled",
            'message' => "🎓 Your {$defenseTypeLabel} has been scheduled for {$this->defense_date->format('F j, Y \a\t g:i A')} at {$this->defense_venue}.{$secretaryInfo}",
            'data' => [
                'panel_assignment_id' => $this->id,
                'defense_date' => $this->defense_date,
                'venue' => $this->defense_venue,
                'defense_type' => $this->defense_type,
                'defense_type_label' => $defenseTypeLabel,
                'secretary_name' => $this->secretary?->name,
                'panel_chair_name' => $this->panelChair?->name,
                'url' => route('student.thesis.defense'),
                'type' => 'defense_scheduled'
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

        // Notify all panel members (including chair and secretary)
        $allPanelMembers = array_merge(
            $this->panel_member_ids ?? [],
            [$this->panel_chair_id, $this->secretary_id]
        );
        $allPanelMembers = array_filter(array_unique($allPanelMembers));

        if (!empty($allPanelMembers)) {
            $secretaryInfo = $this->secretary ? " Secretary: {$this->secretary->name}." : "";
            $defenseDateInfo = $this->defense_date ? " Defense scheduled for {$this->defense_date->format('F j, Y \a\t g:i A')} at {$this->defense_venue}." : "";
            
            // Get panel members with their roles for URL generation
            $panelMemberUsers = User::whereIn('id', $allPanelMembers)->get();
            
            foreach ($panelMemberUsers as $member) {
                $panelNotification = [
                    'title' => 'Panel Assignment - Review Required',
                    'message' => "You have been assigned to review {$this->student->name}'s {$this->defense_type_label}.{$defenseDateInfo}{$secretaryInfo}",
                    'data' => [
                        'panel_assignment_id' => $this->id,
                        'student_name' => $this->student->name,
                        'thesis_title' => $this->thesis_title,
                        'defense_date' => $this->defense_date,
                        'venue' => $this->defense_venue,
                        'defense_type' => $this->defense_type,
                        'defense_type_label' => $this->defense_type_label,
                        'secretary_name' => $this->secretary?->name,
                        'panel_chair_name' => $this->panelChair?->name,
                        'url' => $this->generatePanelAssignmentUrl($member->role),
                    ]
                ];

                Notification::createForUser(
                    $member->id,
                    'panel_assignment_review',
                    $panelNotification['title'],
                    $panelNotification['message'],
                    $panelNotification['data'],
                    get_class($this),
                    $this->id,
                    'high'
                );
            }
        }

        $this->update([
            'student_notified' => true,
            'panel_notified' => true,
            'notification_sent_at' => now(),
        ]);
    }

    /**
     * Generate role-appropriate URL for panel assignment
     */
    private function generatePanelAssignmentUrl(string $role): string
    {
        return match($role) {
            'faculty' => route('faculty.panel-assignments.show', $this),
            'admin' => route('admin.panel.show', $this),
            'student' => route('student.thesis.panel-assignment.show', $this),
            default => route('faculty.panel-assignments.show', $this) // Default to faculty view
        };
    }
}
