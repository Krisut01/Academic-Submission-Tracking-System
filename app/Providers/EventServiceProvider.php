<?php

namespace App\Providers;

use App\Events\FormSubmitted;
use App\Events\ThesisStatusUpdated;
use App\Events\UserRoleChanged;
use App\Listeners\LogActivityListener;
use App\Listeners\SendNotificationListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        
        // Form submission events
        FormSubmitted::class => [
            LogActivityListener::class . '@handleFormSubmitted',
            SendNotificationListener::class . '@handleFormSubmitted',
        ],
        
        // Thesis status update events
        ThesisStatusUpdated::class => [
            LogActivityListener::class . '@handleThesisStatusUpdated',
            SendNotificationListener::class . '@handleThesisStatusUpdated',
        ],
        
        // User role change events
        UserRoleChanged::class => [
            LogActivityListener::class . '@handleUserRoleChanged',
            SendNotificationListener::class . '@handleUserRoleChanged',
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
} 