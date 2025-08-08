<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'message',
        'data',
        'related_model_type',
        'related_model_id',
        'priority',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'data' => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    /**
     * Get the user this notification belongs to
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model (polymorphic relationship)
     */
    public function relatedModel()
    {
        if ($this->related_model_type && $this->related_model_id) {
            return $this->related_model_type::find($this->related_model_id);
        }
        return null;
    }

    /**
     * Create notification for user
     */
    public static function createForUser(
        int $userId,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $relatedModelType = null,
        ?int $relatedModelId = null,
        string $priority = 'normal'
    ): self {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'related_model_type' => $relatedModelType,
            'related_model_id' => $relatedModelId,
            'priority' => $priority,
        ]);
    }

    /**
     * Create notification for multiple users
     */
    public static function createForUsers(
        array $userIds,
        string $type,
        string $title,
        string $message,
        ?array $data = null,
        ?string $relatedModelType = null,
        ?int $relatedModelId = null,
        string $priority = 'normal'
    ): void {
        foreach ($userIds as $userId) {
            self::createForUser(
                $userId,
                $type,
                $title,
                $message,
                $data,
                $relatedModelType,
                $relatedModelId,
                $priority
            );
        }
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(): void
    {
        $this->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }

    /**
     * Scope for unread notifications
     */
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    /**
     * Scope for specific priority
     */
    public function scopeWithPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for urgent notifications
     */
    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    /**
     * Get priority color for UI display
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            'urgent' => 'red',
            'high' => 'orange',
            'normal' => 'blue',
            'low' => 'gray',
            default => 'gray'
        };
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimeAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }
}
