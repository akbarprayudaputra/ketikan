<?php

namespace App\Services;

use App\Models\Like;

interface LikeService
{
  public function createLike(array $data);
  public function deleteLike(Like $like);
  public function getLikeCountByPostId(int $postId);
}
