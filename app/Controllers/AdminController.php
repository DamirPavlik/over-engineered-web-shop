<?php

namespace App\Controllers;

use App\Contracts\AdminInterface;
use App\Contracts\AdminServiceInterface;
use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\DataObjects\AdminData;
use App\DataObjects\UserData;
use App\Entity\Admin;
use App\Enum\LoginAttemptStatus;
use App\Exception\ValidationException;
use App\ResponseFormatter;
use App\Services\EntityManagerService;
use App\Validators\EditUserValidator;
use App\Validators\LoginValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class AdminController
{
    public function __construct(
        private Twig                          $twig,
        private ValidatorFactoryInterface     $validatorFactory,
        private AdminInterface                $admin,
        private AdminServiceInterface         $adminService,
        private ResponseFormatter             $responseFormatter,
        private EntityManagerServiceInterface $entityManagerService
    ) {
    }

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/index.twig');
    }

    public function renderLogin(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/login.twig');
    }

    public function login(Request $request, Response $response): Response
    {
        $data = $this->validatorFactory->make(LoginValidator::class)->validate(
            $request->getParsedBody()
        );

        $loginStatus = $this->admin->attemptLogin($data);

        if ($loginStatus === LoginAttemptStatus::FAILED) {
            throw new ValidationException(['password' => ['You have entered an invalid username or password']]);
        }

        return $response->withHeader('Location', '/admin-dashboard')->withStatus(302);
    }

    public function logout(Request $request, Response $response): Response
    {
        $this->admin->logout();
        return $response->withHeader("Location", "/")->withStatus(302);
    }

    public function load(Response $response): Response
    {
        $data = $this->adminService->getAll();
        return $this->responseFormatter->asJson($response, $data);
    }

    public function delete(Response $response, Request $request, Admin $admin): Response
    {
        $this->entityManagerService->delete($admin, true);
        return $response;
    }

    public function getAdmin(Response $response, Request $request, Admin $admin): Response
    {
        $data = $this->admin->getAdmin($admin);
        return $this->responseFormatter->asJson($response, $data);
    }

    public function update(Response $response, Request $request, Admin $admin): Response
    {
        $data = $this->validatorFactory->make(EditUserValidator::class)->validate($request->getParsedBody());
        $this->entityManagerService->sync($this->adminService->update(
            $admin,
            new AdminData($data['name'], $data['email'])
        ));
        return $response->withHeader("Location", "/admin-dashboard/users")->withStatus(302);
    }
}