<?php

namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use App\DataObjects\RegisterUserData;
use App\DataObjects\UserData;
use App\Entity\Category;
use App\Entity\User;

class UserService
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManager,
        private readonly HashService $hashService
    ) {}

    public function create(RegisterUserData $data): User
    {
        $user = new User();

        $user->setName($data->name);
        $user->setEmail($data->email);
        $user->setPassword($this->hashService->hashPassword($data->password));

        $this->entityManager->sync($user);

        return $user;
    }

    public function findByEmail(string $email): User | null
    {
        return $this->entityManager->getRepository(User::class)->findBy(['email' => $email])[0] ?? null;
    }

    public function getAll(): array
    {
        try {
            $queryBuilder = $this->entityManager->getRepository(User::class)
                ->createQueryBuilder('u')
                ->select('u.id, u.name, u.email', 'u.createdAt', 'u.updatedAt')
                ->getQuery();

            return $queryBuilder->getArrayResult();
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function getUser(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
        ];
    }

    public function update(User $user, UserData $userData): User
    {
        $user->setName($userData->name);
        $user->setEmail($userData->email);
        return $user;
    }
}