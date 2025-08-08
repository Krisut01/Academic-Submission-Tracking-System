<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AcademicForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'form_type',
        'student_id',
        'title',
        'description',
        'status',
        'form_data',
        'uploaded_files',
        'remarks',
        'submission_date',
        'reviewed_at',
        'reviewed_by',
        'review_comments',
    ];

    protected $casts = [
        'form_data' => 'array',
        'uploaded_files' => 'array',
        'submission_date' => 'date',
        'reviewed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the academic form.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who reviewed the form.
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
            'rejected' => 'red',
            default => 'gray'
        };
    }

    /**
     * Get the form type label
     */
    public function getFormTypeLabelAttribute(): string
    {
        return match($this->form_type) {
            'registration' => 'Registration Form',
            'clearance' => 'Clearance Form',
            'evaluation' => 'Evaluation Form',
            default => ucfirst($this->form_type)
        };
    }

    /**
     * Scope for filtering by form type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('form_type', $type);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
