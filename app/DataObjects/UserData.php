<?php

namespace App\DataObjects;
class UserData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {}
}