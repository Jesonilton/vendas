<?php

namespace App\Services\Contracts;

use App\Models\User;

interface UserServiceInterface
{
    public function create(string $name, string $email, string $password): User;
    public function findByEmail(string $email): ?User;
}