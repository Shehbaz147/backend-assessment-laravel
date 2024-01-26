<?php

namespace App\Interfaces;

use Illuminate\Http\JsonResponse;

interface LessonsInterface
{
    public function markLessonAsWatched($lessonId): JsonResponse;
}
