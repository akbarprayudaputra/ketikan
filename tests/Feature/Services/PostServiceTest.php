<?php

namespace Tests\Feature\Services;

use App\Models\Post;
use App\Models\User;
use App\Services\Implement\PostServiceImpl;
use App\Services\PostService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PostServiceTest extends TestCase
{
  use RefreshDatabase;

  protected PostServiceImpl $service;

  protected function setUp(): void
  {
    parent::setUp();
    $this->service = new PostServiceImpl();
  }

  public function test_post_service_is_singleton(): void
  {
    $firstInstance = app(PostService::class);
    $secondInstance = app(PostService::class);

    $this->assertSame($firstInstance, $secondInstance);
  }

  public function test_create_post_without_image(): void
  {
    $user = User::factory()->create();

    $data = [
      'title' => 'Tanpa Gambar',
      'body' => 'Konten teks saja',
      'user_id' => $user->id
    ];

    $post = $this->service->createPost($data);

    $this->assertInstanceOf(Post::class, $post);
    $this->assertNull($post->path_image);
    $this->assertDatabaseHas('posts', ['title' => 'Tanpa Gambar']);
  }

  public function test_create_post_with_image(): void
  {
    Storage::fake('public');

    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('gambar.jpg');

    $data = [
      'title' => 'Dengan Gambar',
      'body' => 'Konten dengan gambar',
      'user_id' => $user->id
    ];

    $post = $this->service->createPost($data, $image);

    $this->assertInstanceOf(Post::class, $post);
    $this->assertNotNull($post->path_image);
    Storage::disk('public')->assertExists($post->path_image);
  }

  public function test_update_post_without_image(): void
  {
    $user = User::factory()->create();
    $image = UploadedFile::fake()->image('lama.jpg');
    $path = $image->store('posts', 'public');

    $post = Post::factory()->create([
      'user_id' => $user->id,
      'title' => 'Judul Lama',
      'body' => 'Konten lama',
      'path_image' => $path,
    ]);

    $updated = $this->service->updatePost($post, [
      'title' => 'Judul Baru',
      'body' => 'Konten baru',
    ]);

    $this->assertNotNull($updated);
    $this->assertEquals('Judul Baru', $post->fresh()->title);
    $this->assertEquals($path, $post->fresh()->path_image);
    Storage::disk('public')->assertExists($path);
  }

  public function test_update_post_with_new_image(): void
  {
      Storage::fake('public');

      $user = User::factory()->create();

      // Simpan gambar lama
      $oldImage = UploadedFile::fake()->image('lama.jpg');
      $oldPath = $oldImage->store('posts', 'public');

      $post = Post::factory()->create([
          'user_id' => $user->id,
          'title' => 'Judul Lama',
          'body' => 'Konten lama',
          'path_image' => $oldPath,
      ]);

      // Gambar baru
      $newImage = UploadedFile::fake()->image('baru.jpg');

      // Update lewat service (bukan langsung lewat model)
      $updated = $this->service->updatePost($post, [
          'title' => 'Judul Baru',
          'body' => 'Konten baru',
      ], $newImage);

      // Assertions
      $this->assertInstanceOf(Post::class, $updated);
      $this->assertEquals('Judul Baru', $updated->title);
      $this->assertNotEquals($oldPath, $updated->path_image);

      Storage::disk('public')->assertExists($updated->path_image); // file baru ada
      Storage::disk('public')->assertMissing($oldPath); // file lama kehapus
  }

  public function test_delete_post_with_image(): void
  {
      Storage::fake('public');

      $user = User::factory()->create();

      // bikin post dengan gambar
      $image = UploadedFile::fake()->image('hapus.jpg');
      $path = $image->store('posts', 'public');

      $post = Post::factory()->create([
          'user_id' => $user->id,
          'title' => 'Post Akan Dihapus',
          'body' => 'Konten untuk dihapus',
          'path_image' => $path,
      ]);

      // pastikan file awalnya ada
      Storage::disk('public')->assertExists($path);

      // panggil service delete
      $result = $this->service->deletePost($post);

      // assertions
      $this->assertTrue($result);
      $this->assertDatabaseMissing('posts', ['id' => $post->id]);
      Storage::disk('public')->assertMissing($path); // gambar lama terhapus
  }

}
