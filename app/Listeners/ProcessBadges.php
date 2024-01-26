<?php

namespace App\Listeners;

use App\Events\BadgeUnlocked;
use App\Models\UserBadges;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessBadges
{

    /**
     * Handle the event.
     */
    public function handle(BadgeUnlocked $event): void
    {
        $user = $event->user;
        $badgeName = $event->badge_name;

        UserBadges::create([
            "name" => $badgeName,
            "user_id" => $user->id
        ]);
    }
}
