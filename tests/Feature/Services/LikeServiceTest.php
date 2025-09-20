<?php

namespace Tests\Feature\Services;

use App\Models\Like;
use App\Models\Post;
use App\Models\User;
use App\Services\Implement\LikeServiceImpl;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LikeServiceTest extends TestCase
{
    use RefreshDatabase;

    private LikeServiceImpl $likeService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->likeService = new LikeServiceImpl();
    }

    public function test_create_like()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $like = $this->likeService->createLike([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('likes', [
            'id' => $like->id,
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);
    }


    public function test_delete_like()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $like = Like::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $result = $this->likeService->deleteLike($like);

        $this->assertTrue($result);
        $this->assertDatabaseMissing('likes', [
            'id' => $like->id,
        ]);
    }


    public function test_get_like_count_by_post_id()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        Like::factory()->count(3)->create([
            'post_id' => $post->id,
            'user_id' => $user->id,
        ]);

        $count = $this->likeService->getLikeCountByPostId($post->id);

        $this->assertEquals(3, $count);
    }
}
