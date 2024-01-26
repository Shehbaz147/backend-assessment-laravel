<?php
use Tests\TestCase;
use App\Events\CommentWritten;
use App\Listeners\ProcessCommentWritten;
use App\Models\User;
use App\Repositories\CommentsRepository;
use App\Services\CommentsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Mockery;

class CommentServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_comment(): void
    {
        // faker for event
        // Event::fake();


        // Create a mock instance of CommentsRepository
        $commentsRepositoryMock = Mockery::mock(CommentsRepository::class);

        // Create a mock instance of CommentsService with a partial mock
        $commentServiceMock = Mockery::mock(CommentsService::class, [$commentsRepositoryMock])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        // Bind the mock instance to the service container
        $this->app->instance(CommentsService::class, $commentServiceMock);

        // Create a fake user using the factory
        $user = User::factory()->create();

        // Set expectations on the mocked CommentService
        $commentData = [
            'user_id' => $user->id,
            'body' => 'This is a test comment',
        ];

        // Expect the createComment method to be called
        $commentServiceMock->shouldReceive('createComment')->once()->with($commentData)->andReturn(
            new JsonResponse(['comment' => $commentData], Response::HTTP_CREATED)
        );

        // Call the createComment method on the mocked CommentService
        $response = $commentServiceMock->createComment($commentData);

        $comment = $response->getContent();

        $comment = json_decode($comment, true)['comment'];

        // Assert the response is a JSON response with HTTP status 201
        $this->assertEquals($commentData['user_id'], $comment['user_id']);

        // Event::assertListening(
        //     ProcessCommentWritten::class,
        //     CommentWritten::class
        // );

        // Assert that the CommentWritten event was dispatched with the correct arguments
        // Event::assertDispatched(CommentWritten::class);

        // Clean up Mockery
        Mockery::close();
    }
}
