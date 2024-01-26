<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\AchievementsService;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    private AchievementsService $achievements;

    public function __construct(AchievementsService $achievementsService){
        $this->achievements = $achievementsService;
    }
    

    public function index(User $user)
    {
        // map through user achievements and return appropriate
        // response array with name
        
        $unlocked_achievements = $this->achievements->getUnlockedAchievements($user);


        return response()->json([
            'unlocked_achievements' => $unlocked_achievements,
            'next_available_achievements' => $this->achievements->getNextAvailableAchievements($user),
            'current_badge' => $this->achievements->getCurrentBadge($user),
            'next_badge' => $this->achievements->getNextBadge($user),
            'remaining_to_unlock_next_badge' => $this->achievements->getAchievementsCountToNextBadge($user)
        ]);
    }
}
