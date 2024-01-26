<?php

namespace App\Services;

use App\Events\CommentWritten;
use App\Interfaces\CommentsInterface;
use App\Models\Comment;
use App\Repositories\CommentsRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CommentsService implements CommentsInterface
{
    private CommentsRepository $commentRepository;

    public function __construct(CommentsRepository $commentsRepository)
    {
        $this->commentRepository = $commentsRepository;
    }

    public function createComment($payload): JsonResponse | null
    {
        try {
            $comment = $this->commentRepository->create($payload);

            CommentWritten::dispatch($comment);
            return response()->json([
                "comment" => [
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    "body" => $comment->body,
                ],
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                "comment" => [],
                "message" => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
