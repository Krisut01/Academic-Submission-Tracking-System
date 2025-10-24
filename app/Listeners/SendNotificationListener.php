<?php

namespace App\Listeners;

use App\Events\FormSubmitted;
use App\Events\ThesisSubmitted;
use App\Events\ThesisStatusUpdated;
use App\Events\UserRoleChanged;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationListener
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
        // Notify faculty and admin about new form submission
        $facultyAndAdmins = User::whereIn('role', ['faculty', 'admin'])->pluck('id')->toArray();
        
        $title = 'New Form Submission';
        $message = "Student {$event->form->user->name} submitted a new {$event->form->form_type} form: {$event->form->title}";
        
        Notification::createForUsers(
            $facultyAndAdmins,
            'form_submitted',
            $title,
            $message,
            [
                'form_id' => $event->form->id,
                'student_name' => $event->form->user->name,
                'form_type' => $event->form->form_type,
                'url' => route('admin.records.show-form', $event->form)
            ],
            get_class($event->form),
            $event->form->id,
            'normal'
        );
    }

    /**
     * Handle thesis submitted events
     */
    public function handleThesisSubmitted(ThesisSubmitted $event): void
    {
        // Notify faculty and admin about new thesis submission
        $facultyAndAdmins = User::whereIn('role', ['faculty', 'admin'])->pluck('id')->toArray();
        
        $title = 'New Thesis Document Submitted';
        $message = "Student {$event->document->user->name} submitted a new {$event->document->document_type}: {$event->document->title}";
        
        Notification::createForUsers(
            $facultyAndAdmins,
            'thesis_submitted',
            $title,
            $message,
            [
                'document_id' => $event->document->id,
                'student_name' => $event->document->user->name,
                'document_type' => $event->document->document_type,
                'url' => route('admin.records.show-document', $event->document)
            ],
            get_class($event->document),
            $event->document->id,
            'normal'
        );
    }

    /**
     * Handle thesis status updated events
     */
    public function handleThesisStatusUpdated(ThesisStatusUpdated $event): void
    {
        // Notify student about thesis status change
        $student = $event->document->user;
        $reviewer = $event->reviewer;
        
        $title = match($event->newStatus) {
            'approved' => 'Thesis Document Approved! ✅',
            'returned_for_revision' => 'Thesis Document Returned for Revision 📝',
            'under_review' => 'Thesis Document Under Review 🔍',
            default => 'Thesis Document Status Updated'
        };
        
        $message = "Your thesis document '{$event->document->title}' has been {$event->newStatus}";
        if ($reviewer) {
            $message .= " by {$reviewer->name}";
        }
        
        $priority = match($event->newStatus) {
            'approved' => 'high',
            'returned_for_revision' => 'urgent',
            default => 'normal'
        };
        
        Notification::createForUser(
            $student->id,
            'thesis_status_updated',
            $title,
            $message,
            [
                'document_id' => $event->document->id,
                'old_status' => $event->oldStatus,
                'new_status' => $event->newStatus,
                'reviewer_name' => $reviewer?->name,
                'url' => route('student.thesis.show', $event->document)
            ],
            get_class($event->document),
            $event->document->id,
            $priority
        );

        // Also notify admin about the review completion
        $admins = User::where('role', 'admin')->pluck('id')->toArray();
        
        Notification::createForUsers(
            $admins,
            'thesis_reviewed',
            'Thesis Document Reviewed',
            "Faculty {$reviewer?->name} reviewed thesis document: {$event->document->title} (Status: {$event->newStatus})",
            [
                'document_id' => $event->document->id,
                'student_name' => $student->name,
                'reviewer_name' => $reviewer?->name,
                'status' => $event->newStatus,
                'url' => route('admin.records.show-document', $event->document)
            ],
            get_class($event->document),
            $event->document->id,
            'normal'
        );
    }

    /**
     * Handle user role changed events
     */
    public function handleUserRoleChanged(UserRoleChanged $event): void
    {
        // Notify the user about role change
        $title = 'Account Role Updated';
        $message = "Your account role has been changed from {$event->oldRole} to {$event->newRole}";
        
        if ($event->changedBy) {
            $message .= " by {$event->changedBy->name}";
        }
        
        Notification::createForUser(
            $event->user->id,
            'role_changed',
            $title,
            $message,
            [
                'old_role' => $event->oldRole,
                'new_role' => $event->newRole,
                'changed_by_name' => $event->changedBy?->name,
            ],
            get_class($event->user),
            $event->user->id,
            'high'
        );

        // Also notify admins about the role change
        $admins = User::where('role', 'admin')->where('id', '!=', $event->changedBy?->id)->pluck('id')->toArray();
        
        if (!empty($admins)) {
            Notification::createForUsers(
                $admins,
                'user_role_changed',
                'User Role Changed',
                "User {$event->user->name} role changed from {$event->oldRole} to {$event->newRole}",
                [
                    'user_id' => $event->user->id,
                    'user_name' => $event->user->name,
                    'old_role' => $event->oldRole,
                    'new_role' => $event->newRole,
                    'changed_by_name' => $event->changedBy?->name,
                ],
                get_class($event->user),
                $event->user->id,
                'normal'
            );
        }
    }
}
