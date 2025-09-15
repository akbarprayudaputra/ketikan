<?php

namespace App\Services\Implement;

use App\Models\User;
use App\Services\UserService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;

class UserServiceImpl implements UserService
{
  public function getAllUsers()
  {
    return User::all();
  }

  public function getUserById(int $id): Builder|User|null
  {
    return User::find($id);
  }

  public function getUserByIdWithPosts(int $id)
  {
    return User::with('posts')->find($id);
  }

  public function getUserByEmail(string $email)
  {
    return User::where('email', $email)->first();
  }

  public function createUser(array $data): User
  {
    $data['password'] = Hash::make($data['password']);
    return User::create($data);
  }

  public function updateUser(int $id, array $data): Builder|User|null
  {
    $user = User::find($id);
    if ($user) {
      $user->update($data);
      return $user;
    }
    return null;
  }

  public function deleteUser(int $id): mixed
  {
    $user = User::find($id);
    if ($user) {
      return $user->delete();
    }
    return false;
  }
}
