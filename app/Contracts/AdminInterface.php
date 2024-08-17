<?php

namespace App\Contracts;

use App\Entity\Admin;
use App\Enum\LoginAttemptStatus;

interface AdminInterface
{
    public function attemptLogin(array $credentials): LoginAttemptStatus;

    public function logout(): void;

    public function getAdmin(Admin $admin);
}
