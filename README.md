## About Assessment

I tried to keep code very clean and well structured using design patterns i.e. RepositoryPattern

- Created user_achievements, user_badges, lesson_achievements, comment_achievements for testing only.
- Attached Listeners to [CommentWritten](app/Listeners/ProcessCommentWritten.php) and [LessonWatched](app/Listeners/ProcessLessonWatched.php).
- Created [CommentsService](app/Services/CommentsService.php) & [LessonsService](app/Services/LessonsService.php)
- Created [AchievementsService](app/Services/AchievementsService.php).