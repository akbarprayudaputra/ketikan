<?php

namespace App\Services\Implement;

use App\Models\Post;
use App\Services\PostService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class PostServiceImpl implements PostService
{
    public function getAllPosts()
    {
        return Post::with(['user', 'likes', 'comments'])->get();
    }

    public function getPostById(int $id)
    {
        return Post::find($id);
    }

    public function createPost(array $data, UploadedFile $image = null): Post
    {
        if ($image) {
            $data['path_image'] = $this->handleStorage($image);
        }

        return Post::create($data);
    }

    protected function handleStorage(UploadedFile $image): string
    {
        // konsisten pakai folder "posts"
        return $image->store('posts', 'public');
    }

    protected function deleteOldImage(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function updatePost(Post $post, array $data, UploadedFile $image = null): Post
    {
        if ($image) {
            $this->deleteOldImage($post->path_image);
            $data['path_image'] = $this->handleStorage($image);
        }

        $post->update($data);

        return $post->fresh(); // biar return selalu data terbaru
    }

    public function deletePost(Post $post): bool
    {
        $this->deleteOldImage($post->path_image);
        return $post->delete();
    }

    public function getLikesCount(Post $post): int
    {
        return $post->likes()->count();
    }

    public function getAllComments(Post $post)
    {
        return $post->comments;
    }
}
