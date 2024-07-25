<?php

namespace App\Contracts;

use App\DataObjects\RegisterUserData;
use App\Entity\Admin;
use App\Enum\LoginAttemptStatus;

interface AdminServiceInterface
{
    public function getByCredentials(array $credentials);

    public function create(RegisterUserData $data);

    public function findByEmail(string $email): Admin | null;
}