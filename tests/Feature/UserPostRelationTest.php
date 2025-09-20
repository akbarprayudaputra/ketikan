<?php

namespace Tests\Feature\Services;

use App\Models\User;
use App\Models\Post;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPostRelationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_have_many_posts()
    {
        $user = User::factory()->create();

        $posts = Post::factory()->count(3)->create([
            'user_id' => $user->id,
        ]);

        $this->assertCount(3, $user->posts); // cek relasi hasMany
        $this->assertTrue($posts->first()->user->is($user)); // cek belongsTo
    }

    public function test_post_belongs_to_a_user()
    {
        $user = User::factory()->create();
        $post = Post::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertEquals($user->id, $post->user->id);
    }
}
