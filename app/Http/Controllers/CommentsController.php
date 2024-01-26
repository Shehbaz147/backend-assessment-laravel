<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Services\CommentsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentsController extends Controller
{
    private CommentsService $commentService;
    
    public function __construct(CommentsService $commentsService){
        $this->commentService = $commentsService;
    }

    public function store(CommentRequest $commentRequest){
        return $this->commentService->createComment($commentRequest->all());
    }
}
