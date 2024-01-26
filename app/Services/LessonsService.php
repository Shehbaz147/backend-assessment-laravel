<?php

namespace App\Services;

use App\Events\LessonWatched;
use App\Interfaces\LessonsInterface;
use App\Models\Lesson;
use App\Models\User;
use App\Models\UserLesson;
use App\Repositories\LessonRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class LessonsService implements LessonsInterface
{
    protected LessonRepository $lessonRepository;

    public function __construct(LessonRepository $lessonRepository){
        $this->lessonRepository = $lessonRepository;
    }

    public function markLessonAsWatched($lessonId): JsonResponse
    {

        // get updated lesson from repository
        $lesson = $this->lessonRepository->markLessonWatched($lessonId);

        // get user from lesson
        $userLesson = UserLesson::where('lesson_id', $lessonId);

        $userLesson->watched = true;
        $userLesson->update();

        try{
            event(new LessonWatched($lesson, $userLesson->user));
            return response()->json([
                "lesson" => $lesson
            ], Response::HTTP_OK);

        }catch(\Exception $e){
            return response()->json([
                "lesson" => []
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        
    }
    
}
