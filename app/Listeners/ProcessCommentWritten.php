<?php

namespace App\Listeners;

use App\Events\AchievementUnlocked;
use App\Events\BadgeUnlocked;
use App\Events\CommentWritten;
use App\Models\User;
use App\Models\UserAchievements;
use App\Repositories\CommentsRepository;

class ProcessCommentWritten
{
    protected CommentsRepository $commentRepository;

    public function __construct(CommentsRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    /**
     * Handle the event.
     */
    public function handle(CommentWritten $event): void
    {
        $comment = $event->comment;
        $user = User::find($comment['user_id']);

        $commentRepository = new CommentsRepository();
        $commentCount = $commentRepository->getCommentCountByUserId($comment['user_id']);

        $this->dispatchEvent($commentCount, $user);
    }

    

    private function dispatchEvent(int $commentCount, User $user)
    {
        $commentAchievements = [
            1 => "First Comment Written",
            3 => "3 Comments Written",
            5 => "5 Comments Written",
            10 => "10 Comments Written",
            20 => "20 Comments Written",
        ];

        foreach ($commentAchievements as $count => $achievement) {
            if ($commentCount >= $count) {
                if (!$user->achievements->where('achievement_name', $achievement)->count()) {
                    $this->createUserAchievement($achievement, $user);
                    event(new AchievementUnlocked($achievement, $user));
                }
            }
        }

        $this->unlockBadges($user);
    }

    private function createUserAchievement(string $achievementName, User $user){
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
