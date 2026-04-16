<?php

namespace Tests\Concerns;

use App\Models\User;

trait CreatesDemoUsers
{
    protected function createAdmin(): User
    {
        return User::factory()->create([
            'role' => 'admin',
        ]);
    }

    protected function createFinance(): User
    {
        return User::factory()->create([
            'role' => 'finance',
        ]);
    }

    protected function createValidator(): User
    {
        return User::factory()->create([
            'role' => 'validator',
        ]);
    }

    protected function createReader(): User
    {
        return User::factory()->create([
            'role' => 'reader',
        ]);
    }
}