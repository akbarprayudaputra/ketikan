<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

interface UserService
{
  public function createUser(array $data): User;
  public function getUserById(int $id): Builder|User|null;
  public function updateUser(int $id, array $data): Builder|User|null;
  public function deleteUser(int $id): mixed;
}
