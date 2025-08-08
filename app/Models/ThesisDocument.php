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
        'title',
        'description',
        'status',
        'document_metadata',
        'uploaded_files',
        'comments',
        'submission_date',
        'reviewed_at',
        'reviewed_by',
        'review_comments',
    ];

    protected $casts = [
        'document_metadata' => 'array',
        'uploaded_files' => 'array',
        'submission_date' => 'date',
        'reviewed_at' => 'datetime',
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
}
