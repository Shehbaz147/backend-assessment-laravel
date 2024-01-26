<?php

namespace Database\Factories;

use App\Models\Lesson;
use App\Models\User;
use App\Models\UserLesson;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserLesson>
 */
class UserLessonFactory extends Factory
{

    protected $model = UserLesson::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'lesson_id' => Lesson::factory(),
            'user_id' => User::factory(),
            'watched' => false
        ];
    }
}
