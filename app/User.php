<?php

namespace App;

use App\Contracts\AdminServiceInterface;
use App\DataObjects\RegisterUserData;
use App\Exception\ValidationException;
use App\Services\UserService;

class User
{
    public function __construct(
        private readonly AdminServiceInterface $adminService,
        private readonly UserService $userService,
    ) {
    }

    public function addUserWithAdmin(RegisterUserData $data)
    {
        if ($data->userType === 'admin') {
            $this->addAdmin($data);
        } else if ($data->userType === 'user') {
            $this->addUser($data);
        } else {
            throw new ValidationException(['userType' => ['Invalid user type']]);
        }
    }

    private function addAdmin(RegisterUserData $data): void
    {
        if ($this->adminService->findByEmail($data->email)) {
            throw new ValidationException(['email' => ['Admin with this email already exists']]);
        }
        $this->adminService->create($data);
    }

    private function addUser(RegisterUserData $data): void
    {
        if ($this->userService->findByEmail($data->email)) {
            throw new ValidationException(['email' => ['User with this email already exists']]);
        }
        $this->userService->create($data);
    }
}
