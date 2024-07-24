<?php

namespace App\Services;

use App\Contracts\AdminServiceInterface;
use App\Contracts\EntityManagerServiceInterface;
use App\DataObjects\RegisterUserData;
use App\Entity\Admin;
use Doctrine\ORM\EntityManager;

class AdminService implements AdminServiceInterface
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManager,
        private readonly HashService $hashService
    )
    {
    }

    public function getByCredentials(array $credentials)
    {
        return $this->entityManager->getRepository(Admin::class)->findOneBy(['email' => $credentials['email']]);
    }

    public function create(RegisterUserData $data): Admin
    {
        $admin = new Admin();

        $admin->setName($data->name);
        $admin->setEmail($data->email);
        $admin->setPassword($this->hashService->hashPassword($data->password));

        $this->entityManager->sync($admin);

        return $admin;
    }

    public function findByEmail(string $email): Admin | null
    {
        return $this->entityManager->getRepository(Admin::class)->findBy(['email' => $email])[0] ?? null;
    }
}