<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserPostCommentRelationTest extends TestCase
{
    use RefreshDatabase;

    public function user_can_create_comment_on_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        $comment = Comment::factory()->create([
            'body'    => 'Komentar pertama saya',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->assertDatabaseHas('comments', [
            'id'      => $comment->id,
            'body'    => 'Komentar pertama saya',
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }

    public function post_can_have_many_comments()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create(['user_id' => $user->id]);

        Comment::factory()->count(3)->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->assertCount(3, $post->comments);
    }

    public function user_can_have_many_comments_across_posts()
    {
        $user = User::factory()->create();
        $posts = Post::factory()->count(2)->create(['user_id' => $user->id]);

        foreach ($posts as $post) {
            Comment::factory()->create([
                'body'    => 'Komentar di post ' . $post->id,
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        $this->assertCount(2, $user->comments);
    }
}
