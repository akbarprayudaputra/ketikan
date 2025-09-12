<?php

namespace App\Services;

use App\Models\Like;

class LikeService
{
  public function createLike(array $data)
  {
    return Like::create($data);
  }

  public function deleteLike(Like $like)
  {
    return $like->delete();
  }

  public function getLikeCountByPostId(int $postId)
  {
    $count = Like::query()->where('post_id', '=', $postId)->count();
  }
}
