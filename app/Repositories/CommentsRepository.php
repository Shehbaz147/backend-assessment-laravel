<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Comment;
use App\Models\User;

class CommentsRepository
{
    
    public function create(array $data): Comment
    {
        $comment = new Comment($data);

        return $comment;
    }


    public function getCommentCountByUserId(int $userId): int
    {
        return Comment::where("user_id", $userId)->count();
    }    
    
}
