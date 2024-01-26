<?php

namespace App\Providers;

use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Events\LessonWatched;
use App\Listeners\ProcessBadges;
use App\Listeners\ProcessCommentWritten;
use App\Listeners\ProcessLessonWatched;
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

        LessonWatched::class => [
            ProcessLessonWatched::class,
        ],

        CommentWritten::class => [
            ProcessCommentWritten::class,
        ],

        BadgeUnlocked::class => [
            ProcessBadges::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        Event::listen(CommentWritten::class, ProcessCommentWritten::class);
        parent::boot();
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
