<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PanelAssignmentReview extends Model
{
    protected $fillable = [
        'panel_assignment_id',
        'thesis_document_id',
        'reviewer_id',
        'reviewer_role',
        'status',
        'review_comments',
        'recommendations',
        'review_criteria',
        'can_download_files',
        'reviewed_at',
    ];

    protected $casts = [
        'review_criteria' => 'array',
        'reviewed_at' => 'datetime',
        'can_download_files' => 'boolean',
    ];

    /**
     * Get the panel assignment for this review
     */
    public function panelAssignment(): BelongsTo
    {
        return $this->belongsTo(PanelAssignment::class);
    }

    /**
     * Get the thesis document for this review
     */
    public function thesisDocument(): BelongsTo
    {
        return $this->belongsTo(ThesisDocument::class);
    }

    /**
     * Get the reviewer (faculty member)
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    /**
     * Get the status color for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'needs_revision' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Get the reviewer role label
     */
    public function getReviewerRoleLabelAttribute(): string
    {
        return match($this->reviewer_role) {
            'panel_chair' => 'Panel Chair',
            'panel_member' => 'Panel Member',
            'adviser' => 'Adviser',
            default => ucfirst($this->reviewer_role)
        };
    }

    /**
     * Check if the review is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->status, ['approved', 'rejected', 'needs_revision']);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for filtering by reviewer role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('reviewer_role', $role);
    }
}
