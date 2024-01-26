<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AchievementsController extends Controller
{
    public function index(User $user)
    {
        $user_achievements = $user->achievements->map(function ($achievement) {
            return [
                'name' => $achievement->achievement_name
            ];
        });

        return response()->json([
            'unlocked_achievements' => $user_achievements,
            'next_available_achievements' => [],
            'current_badge' => '',
            'next_badge' => '',
            'remaing_to_unlock_next_badge' => 0
        ]);
    }
}
