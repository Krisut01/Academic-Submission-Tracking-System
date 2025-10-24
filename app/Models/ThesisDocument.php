<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        
        // For final manuscripts, also check panel assignments for the student
        if ($this->document_type === 'final_manuscript') {
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
        }
        
        return false;
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
