<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FacultyApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'thesis_document_id',
        'faculty_id',
        'approval_status',
        'approval_comments',
        'approved_at',
        'faculty_role',
        'approval_metadata',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'approval_metadata' => 'array',
    ];

    /**
     * Get the thesis document that this approval belongs to
     */
    public function thesisDocument(): BelongsTo
    {
        return $this->belongsTo(ThesisDocument::class);
    }

    /**
     * Get the faculty member who made this approval
     */
    public function faculty(): BelongsTo
    {
        return $this->belongsTo(User::class, 'faculty_id');
    }

    /**
     * Check if this approval is approved
     */
    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    /**
     * Check if this approval needs revision
     */
    public function needsRevision(): bool
    {
        return $this->approval_status === 'returned_for_revision';
    }

    /**
     * Check if this approval is pending
     */
    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    /**
     * Get approval status with human-readable format
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->approval_status) {
            'approved' => 'Approved',
            'returned_for_revision' => 'Needs Revision',
            'under_review' => 'Under Review',
            'pending' => 'Pending',
            default => 'Unknown'
        };
    }

    /**
     * Get approval status color for UI
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->approval_status) {
            'approved' => 'green',
            'returned_for_revision' => 'red',
            'under_review' => 'yellow',
            'pending' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Scope for approved status
     */
    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    /**
     * Scope for pending status
     */
    public function scopePending($query)
    {
        return $query->where('approval_status', 'pending');
    }

    /**
     * Scope for needs revision status
     */
    public function scopeNeedsRevision($query)
    {
        return $query->where('approval_status', 'returned_for_revision');
    }

    /**
     * Scope for specific faculty role
     */
    public function scopeByRole($query, string $role)
    {
        return $query->where('faculty_role', $role);
    }
}

