<?php

namespace App;

use App\Contracts\AdminEntityInterface;
use App\Contracts\AdminInterface;
use App\Contracts\AdminServiceInterface;
use App\Contracts\SessionInterface;
use App\Enum\LoginAttemptStatus;
use App\Entity\Admin as AdminEntity;

class Admin implements AdminInterface
{
    public function __construct(
        private readonly AdminServiceInterface $adminService,
        private readonly SessionInterface $session
    ) {
    }

    public function attemptLogin(array $credentials): LoginAttemptStatus
    {
        $admin = $this->adminService->getByCredentials($credentials);
        
        if (!$admin || !$this->checkCredentials($admin,  $credentials)) {
            return LoginAttemptStatus::FAILED;
        }
        
        $this->login($admin);
        
        return LoginAttemptStatus::SUCCESS;
    }

    private function login(AdminEntityInterface $admin): void
    {
        $this->session->regenerate();
        $this->session->put('admin_user', $admin->getId());
    }

    private function checkCredentials(AdminEntityInterface $admin, array $credentials): bool
    {
        return password_verify($credentials['password'], $admin->getPassword());
    }

    public function logout(): void
    {
        $this->session->forget('admin_user');
        $this->session->regenerate();
    }

    public function getAdmin(AdminEntity $admin): array
    {
        return [
            'id' => $admin->getId(),
            'name' => $admin->getName(),
            'email' => $admin->getEmail()
        ];
    }
}