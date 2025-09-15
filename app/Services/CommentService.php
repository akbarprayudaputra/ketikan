<?php

namespace App\Services;

use App\Models\Comment;

interface CommentService
{
  public function createComment(array $data);
  public function deleteComment(Comment $comment);
  public function getCommentsByPostId(int $postId);
}
