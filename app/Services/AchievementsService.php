<?php

namespace App\Services;

use App\Models\CommentAchievements;
use App\Models\LessonAchievements;
use App\Models\User;

class AchievementsService
{
    public function getUnlockedAchievements(User $user)
    {
        return $user->achievements->map(function ($achievement) {
            return [
                'name' => $achievement->achievement_name
            ];
        });
    }

    private function getAchievementCount(User $user)
    {
        return $user->achievements->count();
    }


    public function getCurrentBadge(User $user)
    {
        $current_badge = '';
        $total_achievements_unlocked = $this->getAchievementCount($user);

        if ($total_achievements_unlocked >= 10) {
            $current_badge = 'Master';
        } else if ($total_achievements_unlocked >= 8 && $total_achievements_unlocked < 10) {
            $current_badge = 'Advanced';
        } else if ($total_achievements_unlocked >= 4 && $total_achievements_unlocked < 8) {
            $current_badge = 'Intermediate';
        } else {
            $current_badge = 'Beginner';
        }

        return $current_badge;
    }

    public function getNextBadge(User $user)
    {
        $next_badge = '';
        $total_achievements_unlocked = $this->getAchievementCount($user);

        if ($total_achievements_unlocked >= 10) {
            $next_badge = '';
        } else if ($total_achievements_unlocked >= 8 && $total_achievements_unlocked < 10) {
            $next_badge = 'Master';
        } else if ($total_achievements_unlocked >= 4 && $total_achievements_unlocked < 8) {
            $next_badge = 'Advanced';
        } else {
            $next_badge = 'Intermediate';
        }

        return $next_badge;
    }

    public function getNextAvailableAchievements(User $user)
    {
        // get groups of achievements
        $lessonAchievements = LessonAchievements::pluck('name')->toArray();
        

        $commentAchievements = CommentAchievements::pluck('name')->toArray();


        // Get the achievements already unlocked by the user
        $unlockedAchievements = $user->achievements->pluck('achievement_name')->toArray();

        // Find the next available achievements for each group
        $nextLessonAchievements = array_diff($lessonAchievements, $unlockedAchievements);
        $nextCommentAchievements = array_diff($commentAchievements, $unlockedAchievements);

        // get first item from each array
        $nextLessonAchievement = reset($nextLessonAchievements);
        $nextCommentAchievement = reset($nextCommentAchievements);    

        // next available achievements
        return [$nextLessonAchievement, $nextCommentAchievement];
    }

    public function getAchievementsCountToNextBadge(User $user){
        $total_achievements_unlocked = $this->getAchievementCount($user);
        $total_achievements_to_next_badge = null;

        if($total_achievements_unlocked >= 10){
            $total_achievements_to_next_badge = 0;
        } else if($total_achievements_unlocked >= 8 && $total_achievements_to_next_badge < 10){
            $total_achievements_to_next_badge = 10 - $total_achievements_unlocked;
        } else if($total_achievements_unlocked >=4 && $total_achievements_unlocked < 8){
            $total_achievements_to_next_badge = 8 - $total_achievements_unlocked;
        } else{
            $total_achievements_to_next_badge = 4 - $total_achievements_unlocked;
        }

        return $total_achievements_to_next_badge;
    }
}
