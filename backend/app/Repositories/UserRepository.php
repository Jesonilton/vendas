<?php 
namespace App\Repositories;

use App\Models\User;

class UserRepository extends AbstractRepository
{
    public function create(array $data): User
    {
        return $this->getModelClass()
                    ::create($data);
    }

    protected function getModelClass(): string
    {
        return User::class;
    }

    public function findByEmail(string $email): ?User
    {
        return $this->getModelClass()
                    ::where('email', $email)
                    ->first();
    }
}