<?php 
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\UserCreationException;
use App\Services\Contracts\UserServiceInterface;

class UserService implements UserServiceInterface
{
    public function __construct(protected UserRepository $repository) {}

    public function create(string $name, string $email, string $password): User
    {
        $this->validateBeforeCreating($name, $email, $password);

        return $this->repository->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password)
        ]);
    }

    public function findByEmail($email): ?User 
    {
        return $this->repository->findByEmail($email);
    }

    protected function validateBeforeCreating(string $name, string $email, string $password): void
    {
        if(empty($name)) {
            throw new UserCreationException('The name parameter cannot be empty');
        }

        if(empty($email) || filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
            throw new UserCreationException('The email parameter must contain a valid email address.');
        }

        if(empty($password)) {
            throw new UserCreationException('The password parameter cannot be empty');
        }
    }
}