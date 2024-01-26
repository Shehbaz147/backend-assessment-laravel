<?php

namespace App\Repositories;

use App\Models\Lesson;
use App\Models\UserLesson;

class LessonRepository
{

    public function getWatchedCount($user){
        return $user->watched->count();
    }

    public function markLessonWatched($lessonId){
        $lesson = UserLesson::where('lesson_id', $lessonId)->first();

        if($lesson){
            $lesson->watched = true;
            $lesson->save();
    
            return $lesson;
        }else{
            return null;
        }
    }
    
}
