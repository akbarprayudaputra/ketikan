<?php

namespace App\Services;

use App\Models\Post;
use File;
use Illuminate\Http\UploadedFile;

interface PostService
{
  public function getAllPosts();
  public function getPostById(int $id);
  public function createPost(array $data, UploadedFile $image = null);
  public function updatePost(Post $post, array $data, UploadedFile $image = null);
  public function deletePost(Post $post);
}
