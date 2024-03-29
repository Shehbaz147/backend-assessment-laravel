<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Lesson;
use App\Models\UserLesson;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $lessons = Lesson::factory()
            ->count(20)
            ->create();

        $comments = Comment::factory()
        ->count(1)
        ->create();

        $userLessons = UserLesson::factory()->count(3)->create();
    }
}
