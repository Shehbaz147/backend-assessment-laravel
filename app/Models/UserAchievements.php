<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAchievements extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "achievement_name",
    ];
}
