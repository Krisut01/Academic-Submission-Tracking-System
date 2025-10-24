<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'event_type',
        'action',
        'model_type',
        'model_id',
        'old_values',
        'new_values',
        'metadata',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'metadata' => 'array',
    ];

    /**
     * Get the user who performed the action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (polymorphic relationship)
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Log activity for any model change
     */
    public static function logActivity(
        string $eventType,
        string $action,
        Model $model,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?array $metadata = null,
        ?string $description = null,
        ?int $userId = null
    ): self {
        $description = $description ?? self::generateDescription($eventType, $action, $model);
        
        return self::create([
            'user_id' => $userId ?? auth()->id(),
            'event_type' => $eventType,
            'action' => $action,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'metadata' => $metadata,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Generate human-readable description
     */
    private static function generateDescription(string $eventType, string $action, Model $model): string
    {
        $modelName = class_basename($model);
        $userName = auth()->user()?->name ?? 'System';
        
        return match($eventType) {
            'form_submitted' => "{$userName} submitted a new {$model->form_type} form",
            'thesis_submitted' => self::generateThesisSubmissionDescription($userName, $model),
            'thesis_reviewed' => "{$userName} reviewed thesis document: {$model->title}",
            'user_updated' => "{$userName} updated user account: {$model->name}",
            'role_changed' => "{$userName} changed user role for: {$model->name}",
            'status_updated' => "{$userName} updated status of {$modelName} to: {$model->status}",
            default => "{$userName} {$action} {$modelName}"
        };
    }

    /**
     * Generate user-friendly thesis submission description
     */
    private static function generateThesisSubmissionDescription(string $userName, Model $model): string
    {
        $documentTypeLabels = [
            'proposal' => 'Thesis Proposal',
            'approval_sheet' => 'Approval Sheet',
            'panel_assignment' => 'Panel Assignment Request',
            'final_manuscript' => 'Final Manuscript'
        ];

        $documentType = $documentTypeLabels[$model->document_type] ?? ucfirst(str_replace('_', ' ', $model->document_type));
        $title = $model->title ? ": {$model->title}" : '';
        
        return "{$userName} submitted {$documentType}{$title}";
    }

    /**
     * Scope for filtering by event type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('event_type', $type);
    }

    /**
     * Scope for filtering by model
     */
    public function scopeForModel($query, string $modelType, int $modelId)
    {
        return $query->where('model_type', $modelType)->where('model_id', $modelId);
    }

    /**
     * Scope for recent activities
     */
    public function scopeRecent($query, int $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
