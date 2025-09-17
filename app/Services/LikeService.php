<?php

namespace App\Services;

use App\Models\Like;

interface LikeService
{
    public function createLike(array $data): Like;

    public function deleteLike(Like $like): bool;

    public function getLikeCountByPostId(int $postId): int;
}
