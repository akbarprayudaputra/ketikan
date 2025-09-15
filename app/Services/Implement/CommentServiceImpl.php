<?php

namespace App\Services\Implement;

use App\Models\Comment;

class CommentServiceImpl
{
  public function createComment(array $data)
  {
    return Comment::create($data);
  }

  public function deleteComment(Comment $comment)
  {
    return $comment->delete();
  }

  public function getAllCommentByPostId(int $id)
  {
    return Comment::with('post')->where('id', $id)->get();
  }
}
