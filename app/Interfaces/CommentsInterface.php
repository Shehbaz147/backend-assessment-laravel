<?php

namespace App\Interfaces;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Http\JsonResponse;

interface CommentsInterface
{
    public function createComment(mixed $payload): JsonResponse | null;
}
