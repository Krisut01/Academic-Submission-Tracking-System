<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PanelEvaluation extends Model
{
    protected $fillable = [
        'panel_assignment_id',
        'thesis_document_id',
        'evaluator_id',
        'evaluator_role',
        'overall_status',
        'evaluation_criteria',
        'evaluation_summary',
        'strengths',
        'weaknesses',
        'recommendations',
        'overall_score',
        'evaluated_at',
    ];

    protected $casts = [
        'evaluation_criteria' => 'array',
        'evaluated_at' => 'datetime',
        'overall_score' => 'decimal:2',
    ];

    /**
     * Get the panel assignment for this evaluation
     */
    public function panelAssignment(): BelongsTo
    {
        return $this->belongsTo(PanelAssignment::class);
    }

    /**
     * Get the thesis document for this evaluation
     */
    public function thesisDocument(): BelongsTo
    {
        return $this->belongsTo(ThesisDocument::class);
    }

    /**
     * Get the evaluator (faculty member)
     */
    public function evaluator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'evaluator_id');
    }

    /**
     * Get the status color for UI display
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->overall_status) {
            'pending' => 'yellow',
            'approved' => 'green',
            'rejected' => 'red',
            'conditional' => 'orange',
            default => 'gray'
        };
    }

    /**
     * Get the evaluator role label
     */
    public function getEvaluatorRoleLabelAttribute(): string
    {
        return match($this->evaluator_role) {
            'panel_chair' => 'Panel Chair',
            'panel_member' => 'Panel Member',
            'adviser' => 'Adviser',
            default => ucfirst($this->evaluator_role)
        };
    }

    /**
     * Check if the evaluation is completed
     */
    public function isCompleted(): bool
    {
        return in_array($this->overall_status, ['approved', 'rejected', 'conditional']);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('overall_status', $status);
    }

    /**
     * Scope for filtering by evaluator role
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('evaluator_role', $role);
    }
}
