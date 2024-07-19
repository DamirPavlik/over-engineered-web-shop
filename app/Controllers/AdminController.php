<?php

namespace App\Controllers;

use App\Contracts\AdminInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\Enum\LoginAttemptStatus;
use App\Exception\ValidationException;
use App\Validators\LoginValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class AdminController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly AdminInterface $admin
    ) {
    }

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/index.twig', ['username' => 'admin']);
    }

    public function renderLogin(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/login.twig');
    }

    public function renderUsers(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/users.twig');
    }

    public function renderProducts(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/products.twig');
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
}