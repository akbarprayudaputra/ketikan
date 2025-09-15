<?php

namespace App\Services\Implement;

use App\Models\Post;
use App\Services\PostService;
use File;
use Illuminate\Http\UploadedFile;

class PostServiceImpl implements PostService
{
  public function getAllPosts()
  {
    return Post::with('user', 'likes', 'comments')->get();
  }

  public function getPostById(int $id)
  {
    return Post::find($id);
  }

  public function createPost(array $data, UploadedFile $image = null)
  {
    if ($image != null) {
      $data['image_path'] = $this->handleStorage($image);
    }

    return Post::create($data);
  }

  public function handleStorage(UploadedFile $image)
  {
    $filename = time() . '.' . $image->getClientOriginalExtension();
    $image->move(public_path('images'), $filename);
    return "images/{$filename}";
  }

  public function deleteOldImage(?string $path): void
  {
    if ($path && File::exists(public_path($path))) {
      File::delete(public_path($path));
    }
  }


  public function updatePost(Post $post, array $data, UploadedFile $image = null)
  {
    if (!$image) {
      return tap($post)->update($data);
    }

    $this->deleteOldImage($post->image_path);
    $data['image_path'] = $this->handleStorage($image);

    return tap($post)->update($data);
  }

  public function deletePost(Post $post)
  {
    $this->deleteOldImage($post->image_path);
    return $post->delete();
  }

  public function getLikesCount(Post $post)
  {
    return $post->likes()->count();
  }

  public function getAllComments(Post $post)
  {
    return $post->comments;
  }
}
