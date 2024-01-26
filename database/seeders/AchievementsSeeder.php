<?php

namespace Database\Seeders;

use App\Models\CommentAchievements;
use App\Models\LessonAchievements;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lessonAchievements = [
            "First Lesson Watched",
            "5 Lessons Watched",
            "10 Lessons Watched",
            "25 Lessons Watched",
            "50 Lessons Watched",
        ];

        $commentAchievements = [
            "First Comment Written",
            "3 Comments Written",
            "5 Comments Written",
            "10 Comments Written",
            "20 Comments Written",
        ];

        foreach ($lessonAchievements as $lessonAchievement) {
            $newLessonAchievement = new LessonAchievements();
            $newLessonAchievement->name = $lessonAchievement;
            $newLessonAchievement->save();
        }

        foreach ($commentAchievements as $commentAchievement) {
            $newCommentAchievement = new CommentAchievements();
            $newCommentAchievement->name = $commentAchievement;
            $newCommentAchievement->save();
        }
    }
}
