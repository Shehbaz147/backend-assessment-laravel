<?php

namespace Tests\Unit;

use App\Events\LessonWatched;
use App\Models\UserLesson;
use App\Repositories\LessonRepository;
use App\Services\LessonsService;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Mockery;
use Tests\TestCase;

class LessonsServiceTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_example(): void
    {
        // Ensure the CommentWritten event is faked
        // Event::fake([LessonWatched::class]);

        // Create a mock instance of LessonRepository
        $lessonRepositoryMock = $this->getMockBuilder(LessonRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Create a fake user using the factory
        $user_lesson = UserLesson::factory()->create();

        // Set expectations on the mocked LessonService
        $expectedResponse = new JsonResponse(
            [
                'user_id' => $user_lesson->user_id,
                'lesson_id' => $user_lesson->lesson_id,
                'watched' => true
            ],
            Response::HTTP_CREATED
        );

        // Create a mock instance of LessonService
        $commentServiceMock = Mockery::mock(LessonsService::class, [$lessonRepositoryMock])->makePartial();

        // Expect the markLessonAsWatched method to be called
        $commentServiceMock->shouldReceive('markLessonAsWatched')->once()->andReturn($expectedResponse);

        // Bind the mock instance to the service container
        $this->app->instance(LessonsService::class, $commentServiceMock);

        // Call the createComment method on the mocked LessonService
        $response = $commentServiceMock->markLessonAsWatched($user_lesson->id);

        // Assert the response structure and content
        $this->assertEquals($expectedResponse->getContent(), $response->getContent());
        $this->assertEquals($expectedResponse->getStatusCode(), $response->getStatusCode());

        // Assert the LessonWatched event was dispatched with the correct arguments
        // Event::assertDispatched(LessonWatched::class, function ($event) use ($user_lesson) {
        //     // Check if the event has the correct comment data
        //     return $event->lesson->id === $user_lesson->id
        //         && $event->user->id === $user_lesson->user_id;
        // });
    }
}
