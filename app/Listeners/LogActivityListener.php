<?php

namespace App\Listeners;

use App\Events\FormSubmitted;
use App\Events\ThesisSubmitted;
use App\Events\ThesisStatusUpdated;
use App\Events\UserRoleChanged;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogActivityListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle form submitted events
     */
    public function handleFormSubmitted(FormSubmitted $event): void
    {
        ActivityLog::logActivity(
            'form_submitted',
            'created',
            $event->form,
            null,
            $event->form->toArray(),
            $event->metadata,
            null,
            $event->form->user_id
        );
    }

    /**
     * Handle thesis submitted events
     */
    public function handleThesisSubmitted(ThesisSubmitted $event): void
    {
        ActivityLog::logActivity(
            'thesis_submitted',
            'created',
            $event->document,
            null,
            $event->document->toArray(),
            $event->metadata,
            null,
            $event->document->user_id
        );
    }

    /**
     * Handle thesis status updated events
     */
    public function handleThesisStatusUpdated(ThesisStatusUpdated $event): void
    {
        ActivityLog::logActivity(
            'thesis_reviewed',
            'updated',
            $event->document,
            ['status' => $event->oldStatus],
            ['status' => $event->newStatus, 'reviewer' => $event->reviewer?->name],
            array_merge($event->metadata, [
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'reviewer_id' => $event->reviewer?->id,
            ]),
            null,
            $event->reviewer?->id
        );
    }

    /**
     * Handle user role changed events
     */
    public function handleUserRoleChanged(UserRoleChanged $event): void
    {
        ActivityLog::logActivity(
            'role_changed',
            'updated',
            $event->user,
            ['role' => $event->oldRole],
            ['role' => $event->newRole],
            array_merge($event->metadata, [
                'old_role' => $event->oldRole,
                'new_role' => $event->newRole,
                'changed_by_id' => $event->changedBy?->id,
            ]),
            null,
            $event->changedBy?->id
        );
    }
}
