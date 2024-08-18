<?php

namespace App\Traits\TestHelpers;

use App\Models\User;

trait CreateUserTrait
{
    protected function createUser(bool $isAdmin = false): User
    {
        return User::factory()->create([
            'is_admin' => $isAdmin
        ]);
    }
}
