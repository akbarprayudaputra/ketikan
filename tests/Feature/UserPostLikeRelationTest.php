<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Models\Post;
use App\Models\Like;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPostLikeRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_like_a_post()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $like = Like::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->assertTrue($user->likes->contains($like)); // cek relasi user -> likes
        $this->assertTrue($post->likes->contains($like)); // cek relasi post -> likes
        $this->assertTrue($like->user->is($user));        // cek belongsTo user
        $this->assertTrue($like->post->is($post));        // cek belongsTo post
    }

    public function test_post_can_have_many_likes_from_different_users()
    {
        $owner = User::factory()->create(); // pemilik post
        $post = Post::factory()->create([
            'user_id' => $owner->id,
        ]);

        // buat 3 user lain yang akan like post ini
        $users = User::factory()->count(3)->create();

        foreach ($users as $user) {
            Like::factory()->create([
                'user_id' => $user->id,
                'post_id' => $post->id,
            ]);
        }

        $this->assertCount(3, $post->likes); // post punya 3 likes
    }


    public function test_user_cannot_like_same_post_twice()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        Like::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Like::factory()->create([
            'user_id' => $user->id,
            'post_id' => $post->id,
        ]);
    }
}
