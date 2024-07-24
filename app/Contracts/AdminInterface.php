<?php

namespace App\Contracts;

use App\Enum\LoginAttemptStatus;

interface AdminInterface
{
    public function attemptLogin(array $credentials): LoginAttemptStatus;

    public function logout(): void;

}
