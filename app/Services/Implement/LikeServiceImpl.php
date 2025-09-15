<?php

namespace App\Services\Implement;

use App\Models\Like;
use App\Services\LikeService;

class LikeServiceImpl implements LikeService
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
