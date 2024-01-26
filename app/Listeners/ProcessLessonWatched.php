<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\LessonWatched;
use App\Models\User;
use App\Models\UserAchievements;
use App\Repositories\LessonRepository;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ProcessLessonWatched
{
    protected LessonRepository $lessonRepository;
    /**
     * Create the event listener.
     */
    public function __construct(LessonRepository $lessonRepository)
    {
        $this->lessonRepository = $lessonRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(LessonWatched $event): void
    {
        $lesson = $event->lesson;
        $user = $event->user;

        $lessonsWatched = $this->lessonRepository->getWatchedCount($user);

        $this->dispatchEvent($lessonsWatched, $user);
    }

    private function dispatchEvent(int $lessonWatched, User $user)
    {
        $lessonAchievements = [
            1 => "First Lesson Watched",
            5 => "5 Lessons Watched",
            25 => "25 Lessons Watched",
            50 => "50 Lessons Watched",
        ];

        foreach ($lessonAchievements as $count => $achievement) {
            if ($lessonWatched >= $count) {
                if (!$user->achievements->where('achievement_name', $achievement)->count()) {
                    $this->createUserAchievement($achievement, $user);
                    event(new AchievementUnlocked($achievement, $user));
                }
            }
        }

        $this->unlockBadges($user);
    }

    private function createUserAchievement(string $achievementName, User $user)
    {
        $userAchievement = new UserAchievements();
        $userAchievement->user_id = $user->id;
        $userAchievement->achievement_name = $achievementName;
        $userAchievement->save();

        return [
            "user_id" => $userAchievement->user_id,
            "achievement_name" => $userAchievement->achievement_name
        ];
    }

    private function unlockBadges($user)
    {
        $userAchievements = $user->achievements->count();

        switch ($userAchievements) {
            case 4:
                if (!$user->badges->where('name', "Intermediate")->count()) {
                    event(new BadgeUnlocked("Intermediate", $user));
                }
                break;
            case 8:
                if (!$user->badges->where('name', "Advanced")->count()) {
                    event(new BadgeUnlocked("Advanced", $user));
                }
                break;
            case 10:
                if (!$user->badges->where('name', "Master")->count()) {
                    event(new BadgeUnlocked("Master", $user));
                }
                break;
            default:
                break;
        }
    }
}
