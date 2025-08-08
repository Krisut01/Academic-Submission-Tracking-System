<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'notification_preferences',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'notification_preferences' => 'array',
        ];
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is a student
     */
    public function isStudent(): bool
    {
        return $this->hasRole('student');
    }

    /**
     * Check if user is faculty
     */
    public function isFaculty(): bool
    {
        return $this->hasRole('faculty');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Get the thesis documents for the user.
     */
    public function thesisDocuments()
    {
        return $this->hasMany(ThesisDocument::class);
    }

    /**
     * Get the academic forms for the user.
     */
    public function academicForms(): HasMany
    {
        return $this->hasMany(AcademicForm::class);
    }

    /**
     * Get the academic forms reviewed by this user (for faculty)
     */
    public function reviewedForms(): HasMany
    {
        return $this->hasMany(AcademicForm::class, 'reviewed_by');
    }

    /**
     * Get the thesis documents reviewed by this user (for faculty)
     */
    public function reviewedThesis(): HasMany
    {
        return $this->hasMany(ThesisDocument::class, 'reviewed_by');
    }

    /**
     * Get the notifications for this user
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get unread notifications for this user
     */
    public function unreadNotifications(): HasMany
    {
        return $this->hasMany(Notification::class)->where('is_read', false);
    }

    /**
     * Get activity logs for this user
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    /**
     * Get recent activity logs for this user
     */
    public function recentActivities(int $days = 7): HasMany
    {
        return $this->hasMany(ActivityLog::class)
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc');
    }

    public function panelAssignments(): HasMany
    {
        return $this->hasMany(PanelAssignment::class, 'student_id');
    }

    /**
     * Get unread notification count
     */
    public function getUnreadNotificationCountAttribute(): int
    {
        return $this->unreadNotifications()->count();
    }

    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsAsRead(): void
    {
        $this->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now(),
        ]);
    }
}
