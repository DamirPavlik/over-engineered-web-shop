<?php

namespace App\DataObjects;
class AdminData
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
    ) {}
}