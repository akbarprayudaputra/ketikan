<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Implement\UserServiceImpl;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
  use RefreshDatabase;

  protected UserServiceImpl $service;

  protected function setUp(): void
  {
    parent::setUp();
    $this->service = new UserServiceImpl();
  }

  public function test_user_service_is_singleton(): void
  {
    $firstInstance = app(UserService::class);
    $secondInstance = app(UserService::class);

    $this->assertSame($firstInstance, $secondInstance);
  }


  public function test_can_get_user_by_id(): void
  {
    $user = User::factory()->create();

    $result = $this->service->getUserById($user->id);

    $this->assertNotNull($result);
    $this->assertEquals($user->id, $result->id);
    $this->assertEquals($user->email, $result->email);
  }

  public function test_returns_null_if_user_not_found(): void
  {
    $result = $this->service->getUserById(999);

    $this->assertNull($result);
  }

  public function test_can_create_user_without_image(): void
  {
    $data = [
      'name' => 'Asus',
      'email' => 'asus@example.com',
      'password' => 'secret123',
    ];

    $user = $this->service->createUser($data);

    $this->assertInstanceOf(User::class, $user);
    $this->assertNull($user->image);
    $this->assertDatabaseHas('users', ['email' => $data['email']]);
  }

  public function test_can_create_user_with_image(): void
  {
    $image = UploadedFile::fake()->image('avatar.jpg');

    $data = [
      'name' => 'Asus',
      'email' => 'asus@example.com',
      'password' => 'secret123',
      'photo_path' => $image,
    ];

    $user = $this->service->createUser($data);

    $this->assertNotNull($user->photo_path);
    Storage::disk('public')->assertExists($user->image);
  }
  public function test_can_update_user_without_changing_image(): void
  {
    $image = UploadedFile::fake()->image('avatar.jpg');
    $user = User::factory()->create([
      'photo_path' => $image->store('avatars', 'public'),
    ]);

    $updated = $this->service->updateUser($user->id, ['name' => 'Updated Asus']);

    $updated = $this->service->updateUser($user->id, ['name' => 'updated Asus']);

    $this->assertNotNull($updated);
    $this->assertEquals('updated Asus', $user->fresh()->name);
    $this->assertEquals($user->photo_path, $user->fresh()->photo_path);
    Storage::disk('public')->assertExists($user->photo_path);

  }

  public function test_can_update_user_with_new_image(): void
  {
    $oldImage = UploadedFile::fake()->image('old.jpg');
    $user = User::factory()->create([
      'photo_path' => $oldImage->store('avatars', 'public'),
    ]);

    $newImage = UploadedFile::fake()->image('new.jpg');

    $updated = $this->service->updateUser($user->id, [
      'name' => 'Updated Asus',
      'photo_path' => $newImage,
    ]);

    $this->assertNotNull($updated);
    $this->assertEquals('Updated Asus', $user->fresh()->name);
    Storage::disk('public')->assertExists($user->fresh()->image);
    $this->assertNotEquals($user->photo_path, $user->fresh()->photo_path);
  }

}
