<?php

namespace App\Services;

use App\Models\User;

class UserService
{
  public function getAllUsers()
  {
    return User::all();
  }

  public function getUserbyId(int $id)
  {
    return User::find($id);
  }

  public function getUserByEmail(string $email)
  {
    return User::where('email', $email)->first();
  }

  public function createUser(array $data)
  {
    return User::create($data);
  }

  public function updateUser(int $id, array $data)
  {
    $user = User::find($id);
    if ($user) {
      $user->update($data);
      return $user;
    }
    return null;
  }

  public function deleteUser(int $id)
  {
    $user = User::find($id);
    if ($user) {
      return $user->delete();
    }
    return false;
  }
}
