<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\FacultyApproval;

class ThesisDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'document_type',
        'student_id',
        'full_name',
        'course_program',
        'title',
        'description',
        'abstract',
        'research_area',
        'adviser_name',
        'adviser_id',
        'panel_members',
        'approval_date',
        'defense_date',
        'defense_type',
        'defense_venue',
        'requested_schedule',
        'final_revisions_completed',
        'has_plagiarism_report',
        'plagiarism_percentage',
        'status',
        'document_metadata',
        'uploaded_files',
        'comments',
        'remarks',
        'submission_date',
        'reviewed_at',
        'reviewed_by',
        'review_comments',
        'file_naming_prefix',
        'version_number',
        'status_history',
        'defense_status',
        'defense_notes',
        'defense_grade',
        'defense_confirmed_by',
        'defense_confirmed_at',
        'preferred_dates',
        'preferred_time',
        'preferred_venue',
        'special_requirements',
        'required_specializations',
        'panel_justification',
    ];

    protected $casts = [
        'document_metadata' => 'array',
        'uploaded_files' => 'array',
        'panel_members' => 'array',
        'status_history' => 'array',
        'requested_schedule' => 'array',
        'preferred_dates' => 'array',
        'submission_date' => 'date',
        'approval_date' => 'date',
        'defense_date' => 'date',
        'reviewed_at' => 'datetime',
        'defense_confirmed_at' => 'datetime',
        'final_revisions_completed' => 'boolean',
        'has_plagiarism_report' => 'boolean',
        'plagiarism_percentage' => 'decimal:2',
        'defense_grade' => 'decimal:2',
    ];

    /**
     * Get the user that owns the thesis document.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who reviewed the document.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    /**
     * Get the adviser assigned to this thesis document.
     */
    public function adviser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'adviser_id');
    }

    /**
     * Get panel assignments for this thesis document.
     */
    public function panelAssignments()
    {
        return $this->hasMany(PanelAssignment::class);
    }

    /**
     * Get all faculty approvals for this document
     */
    public function facultyApprovals()
    {
        return $this->hasMany(FacultyApproval::class);
    }

    /**
     * Get approved faculty approvals
     */
    public function approvedFacultyApprovals()
    {
        return $this->hasMany(FacultyApproval::class)->approved();
    }

    /**
     * Get pending faculty approvals
     */
    public function pendingFacultyApprovals()
    {
        return $this->hasMany(FacultyApproval::class)->pending();
    }

    /**
     * Create or update individual faculty approval
     */
    public function setFacultyApproval(int $facultyId, string $status, ?string $comments = null, ?string $role = null): FacultyApproval
    {
        return FacultyApproval::updateOrCreate(
            [
                'thesis_document_id' => $this->id,
                'faculty_id' => $facultyId,
            ],
            [
                'approval_status' => $status,
                'approval_comments' => $comments,
                'approved_at' => $status === 'approved' ? now() : null,
                'faculty_role' => $role,
            ]
        );
    }

    /**
     * Get individual faculty approval status
     */
    public function getFacultyApprovalStatus(int $facultyId): ?FacultyApproval
    {
        return $this->facultyApprovals()->where('faculty_id', $facultyId)->first();
    }

    /**
     * Calculate overall approval status based on individual faculty approvals
     */
    public function calculateOverallApprovalStatus(): array
    {
        $approvals = $this->facultyApprovals()->with('faculty')->get();
        
        $statusCounts = [
            'approved' => 0,
            'returned_for_revision' => 0,
            'under_review' => 0,
            'pending' => 0,
        ];

        $totalApprovals = $approvals->count();
        $approvedCount = 0;
        $needsRevisionCount = 0;

        foreach ($approvals as $approval) {
            $statusCounts[$approval->approval_status]++;
            
            if ($approval->approval_status === 'approved') {
                $approvedCount++;
            } elseif ($approval->approval_status === 'returned_for_revision') {
                $needsRevisionCount++;
            }
        }

        // Determine overall status
        $overallStatus = 'pending';
        if ($needsRevisionCount > 0) {
            $overallStatus = 'returned_for_revision';
        } elseif ($approvedCount === $totalApprovals && $totalApprovals > 0) {
            $overallStatus = 'approved';
        } elseif ($approvedCount > 0) {
            $overallStatus = 'under_review';
        }

        return [
            'overall_status' => $overallStatus,
            'total_approvals' => $totalApprovals,
            'approved_count' => $approvedCount,
            'needs_revision_count' => $needsRevisionCount,
            'completion_percentage' => $totalApprovals > 0 ? round(($approvedCount / $totalApprovals) * 100, 1) : 0,
            'status_breakdown' => $statusCounts,
            'individual_approvals' => $approvals->map(function ($approval) {
                // Handle case where faculty relationship might be null or invalid
                $facultyName = 'Unknown Faculty';
                if ($approval->faculty && is_object($approval->faculty)) {
                    // Check if name exists and is not empty
                    if (isset($approval->faculty->name) && !empty($approval->faculty->name)) {
                        $facultyName = $approval->faculty->name;
                    } elseif (isset($approval->faculty->email) && !empty($approval->faculty->email)) {
                        // Fallback to email if name is not available
                        $facultyName = $approval->faculty->email;
                    }
                } elseif (is_string($approval->faculty)) {
                    $facultyName = $approval->faculty;
                }
                
                return [
                    'faculty_id' => $approval->faculty_id,
                    'faculty_name' => $facultyName,
                    'faculty_role' => $approval->faculty_role,
                    'status' => $approval->approval_status,
                    'status_label' => $approval->status_label,
                    'status_color' => $approval->status_color,
                    'comments' => $approval->approval_comments,
                    'approved_at' => $approval->approved_at,
                ];
            }),
        ];
    }

    /**
     * Get the user who confirmed the defense.
     */
    public function defenseConfirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'defense_confirmed_by');
    }

    /**
     * Get the status color for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'under_review' => 'blue',
            'approved' => 'green',
            'returned_for_revision' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the document type label
     */
    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'proposal' => 'Proposal Form',
            'approval_sheet' => 'Approval Sheet',
            'panel_assignment' => 'Panel Assignment',
            'final_manuscript' => 'Final Manuscript',
            default => ucfirst(str_replace('_', ' ', $this->document_type))
        };
    }

    /**
     * Scope for filtering by document type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('document_type', $type);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Generate file naming prefix for uploaded documents
     */
    public function generateFileNamingPrefix(): string
    {
        $date = $this->submission_date && $this->submission_date instanceof \Carbon\Carbon 
            ? $this->submission_date->format('Y-m-d') 
            : now()->format('Y-m-d');
        return "{$this->student_id}_{$this->document_type}_{$date}";
    }

    /**
     * Add status change to history
     */
    public function addStatusHistory(string $oldStatus, string $newStatus, ?User $changedBy = null, ?string $reason = null): void
    {
        $history = $this->status_history ?? [];
        $history[] = [
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'changed_by' => $changedBy ? $changedBy->name : 'System',
            'changed_by_id' => $changedBy?->id,
            'reason' => $reason,
            'changed_at' => now()->toISOString(),
        ];
        $this->update(['status_history' => $history]);
    }

    /**
     * Get the file naming convention for this document
     */
    public function getFileNamingConvention(): string
    {
        return $this->file_naming_prefix ?? $this->generateFileNamingPrefix();
    }

    /**
     * Check if document can be edited
     */
    public function canBeEdited(): bool
    {
        return in_array($this->status, ['pending', 'returned_for_revision']);
    }

    /**
     * Check if a faculty member has access to this document
     */
    public function facultyHasAccess(int $facultyId): bool
    {
        // Faculty has access if they are:
        // 1. The adviser
        // 2. The reviewer
        // 3. Part of any panel assignment (chair, secretary, or member)
        
        if ($this->adviser_id == $facultyId || $this->reviewed_by == $facultyId) {
            return true;
        }
        
        // Check panel assignments directly linked to this document
        foreach ($this->panelAssignments as $panel) {
            if ($panel->panel_chair_id == $facultyId || 
                $panel->secretary_id == $facultyId || 
                in_array($facultyId, $panel->panel_member_ids)) {
                return true;
            }
        }
        
        // For ALL documents, check panel assignments for the student
        $studentPanelAssignments = \App\Models\PanelAssignment::where('student_id', $this->user_id)
            ->where(function ($query) use ($facultyId) {
                $query->where('panel_chair_id', $facultyId)
                      ->orWhere('secretary_id', $facultyId)
                      ->orWhereJsonContains('panel_members', (string)$facultyId);
            })
            ->exists();
        
        if ($studentPanelAssignments) {
            return true;
        }
        
        return false;
    }

    /**
     * Get comprehensive approval status for thesis documents
     */
    public function getApprovalStatus(): array
    {
        $approvalStatus = [
            'adviser' => [
                'name' => $this->adviser?->name ?? 'Not Assigned',
                'email' => $this->adviser?->email ?? null,
                'status' => 'pending',
                'approved_at' => null,
                'comments' => null,
                'role' => 'Adviser'
            ],
            'reviewer' => [
                'name' => $this->reviewer?->name ?? 'Not Assigned',
                'email' => $this->reviewer?->email ?? null,
                'status' => 'pending',
                'approved_at' => null,
                'comments' => null,
                'role' => 'Reviewer'
            ],
            'panel_members' => [],
            'overall_status' => 'pending',
            'completion_percentage' => 0
        ];

        // Check adviser approval - look for individual review records first
        if ($this->adviser_id) {
            // Check if adviser has a specific review record (with or without panel assignment)
            $adviserReview = \App\Models\PanelAssignmentReview::where('thesis_document_id', $this->id)
                ->where('reviewer_id', $this->adviser_id)
                ->where('reviewer_role', 'adviser')
                ->first();
            
            if ($adviserReview) {
                $approvalStatus['adviser'] = [
                    'name' => $this->adviser?->name ?? 'Unknown Adviser',
                    'email' => $this->adviser?->email ?? null,
                    'status' => $adviserReview->status,
                    'approved_at' => $adviserReview->reviewed_at,
                    'comments' => $adviserReview->review_comments,
                    'role' => 'Adviser'
                ];
            } elseif ($this->reviewed_by == $this->adviser_id) {
                // Fallback to main document status if no specific review record
                $approvalStatus['adviser'] = [
                    'name' => $this->adviser?->name ?? 'Unknown Adviser',
                    'email' => $this->adviser?->email ?? null,
                    'status' => $this->status === 'approved' ? 'approved' : ($this->status === 'returned_for_revision' ? 'needs_revision' : 'pending'),
                    'approved_at' => $this->reviewed_at,
                    'comments' => $this->review_comments,
                    'role' => 'Adviser'
                ];
            }
        }

        // Check reviewer approval - look for individual review records first
        if ($this->reviewed_by && $this->reviewed_by != $this->adviser_id) {
            // Check if reviewer has a specific review record (with or without panel assignment)
            $reviewerReview = \App\Models\PanelAssignmentReview::where('thesis_document_id', $this->id)
                ->where('reviewer_id', $this->reviewed_by)
                ->where('reviewer_role', 'reviewer')
                ->first();
            
            if ($reviewerReview) {
                $approvalStatus['reviewer'] = [
                    'name' => $this->reviewer?->name ?? 'Unknown Reviewer',
                    'email' => $this->reviewer?->email ?? null,
                    'status' => $reviewerReview->status,
                    'approved_at' => $reviewerReview->reviewed_at,
                    'comments' => $reviewerReview->review_comments,
                    'role' => 'Reviewer'
                ];
            } else {
                // Fallback to main document status if no specific review record
                $approvalStatus['reviewer'] = [
                    'name' => $this->reviewer?->name ?? 'Unknown Reviewer',
                    'email' => $this->reviewer?->email ?? null,
                    'status' => $this->status === 'approved' ? 'approved' : ($this->status === 'returned_for_revision' ? 'needs_revision' : 'pending'),
                    'approved_at' => $this->reviewed_at,
                    'comments' => $this->review_comments,
                    'role' => 'Reviewer'
                ];
            }
        }

        // Check panel member approvals through PanelAssignmentReview
        $panelAssignments = \App\Models\PanelAssignment::where('student_id', $this->user_id)
            ->where(function ($query) {
                $query->where('thesis_document_id', $this->id)
                      ->orWhere('defense_type', 'final_defense');
            })
            ->get();

        foreach ($panelAssignments as $panelAssignment) {
            $panelMemberReviews = \App\Models\PanelAssignmentReview::where('panel_assignment_id', $panelAssignment->id)
                ->where('thesis_document_id', $this->id)
                ->where('reviewer_role', 'panel_member')
                ->get();

            foreach ($panelMemberReviews as $review) {
                $approvalStatus['panel_members'][] = [
                    'name' => $review->reviewer?->name ?? 'Unknown Panel Member',
                    'email' => $review->reviewer?->email ?? null,
                    'status' => $review->status,
                    'approved_at' => $review->reviewed_at,
                    'comments' => $review->review_comments,
                    'role' => 'Panel Member'
                ];
            }
        }

        // Also check for general review records (without panel assignments)
        $generalReviews = \App\Models\PanelAssignmentReview::where('thesis_document_id', $this->id)
            ->whereNull('panel_assignment_id')
            ->where('reviewer_role', 'panel_member')
            ->get();

        foreach ($generalReviews as $review) {
            $approvalStatus['panel_members'][] = [
                'name' => $review->reviewer?->name ?? 'Unknown Panel Member',
                'email' => $review->reviewer?->email ?? null,
                'status' => $review->status,
                'approved_at' => $review->reviewed_at,
                'comments' => $review->review_comments,
                'role' => 'Panel Member'
            ];
        }

        // Calculate overall status and completion percentage
        $allApprovals = array_merge(
            [$approvalStatus['adviser'], $approvalStatus['reviewer']],
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
     * Get panel members as formatted list
     */
    public function getFormattedPanelMembersAttribute(): string
    {
        if (!$this->panel_members || empty($this->panel_members)) {
            return 'Not assigned';
        }
        
        if (is_array($this->panel_members)) {
            return implode(', ', $this->panel_members);
        }
        
        return (string) $this->panel_members;
    }

    /**
     * Get research progress percentage
     */
    public function getProgressPercentageAttribute(): float
    {
        $stages = ['proposal', 'panel_assignment', 'approval_sheet', 'final_manuscript'];
        $currentIndex = array_search($this->document_type, $stages);
        
        if ($currentIndex === false) return 0;
        
        $baseProgress = ($currentIndex / count($stages)) * 100;
        
        // Add status-based progress within the stage
        $statusProgress = match($this->status) {
            'pending' => 0.25,
            'under_review' => 0.5,
            'returned_for_revision' => 0.75,
            'approved' => 1.0,
            default => 0
        };
        
        $stageProgress = (1 / count($stages)) * 100 * $statusProgress;
        
        return min(100, $baseProgress + $stageProgress);
    }
}
