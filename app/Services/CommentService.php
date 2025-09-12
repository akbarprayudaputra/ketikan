<?php

namespace App\Services;

use App\Models\Comment;

class CommentService
{
  public function createComment(array $data)
  {
    return Comment::create($data);
  }

  public function deleteComment(Comment $comment)
  {
    return $comment->delete();
  }
}
