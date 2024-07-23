<?php

namespace App\Contracts;

use App\Enum\LoginAttemptStatus;

interface AdminEntityInterface
{
    public function getId(): int;

    public function setId(int $id): void;

    public function getName(): string;

    public function setName(string $name): void;

    public function getEmail(): string;

    public function setEmail(string $email): void;

    public function getPassword(): string;

    public function setPassword(string $password): void;
}
