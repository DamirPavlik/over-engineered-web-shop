<?php

namespace App\Controllers;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\DataObjects\RegisterUserData;
use App\DataObjects\UserData;
use App\Entity\User as UserEntity;
use App\User;
use App\ResponseFormatter;
use App\Services\UserService;
use App\Validators\EditUserValidator;
use App\Validators\RegisterUserValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class UserController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly User $user,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly UserService $userService,
        private readonly ResponseFormatter $responseFormatter,
        private readonly EntityManagerServiceInterface $entityManagerService
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/users/users.twig');
    }

    public function addUser(Request $request, Response $response): Response
    {
        $data = $this->validatorFactory->make(RegisterUserValidator::class)->validate($request->getParsedBody());

        $this->user->addUserWithAdmin(
            new RegisterUserData($data['name'], $data['email'], $data['password'], $data['userType'])
        );

        return $response->withHeader("Location", "/admin-dashboard/users")->withStatus(302);
    }

    public function load(Response $response): Response
    {
        $data = $this->userService->getAll();
        return $this->responseFormatter->asJson($response, $data);
    }

    public function delete(Response $response, Request $request, UserEntity $user): Response
    {
        $this->entityManagerService->delete($user, true);
        return $response;
    }

    public function getUser(Response $response, Request $request, UserEntity $user): Response
    {
        $data = $this->userService->getUser($user);
        return $this->responseFormatter->asJson($response, $data);
    }

    public function update(Response $response, Request $request, UserEntity $user): Response
    {
        $data = $this->validatorFactory->make(EditUserValidator::class)->validate($request->getParsedBody());
        $this->entityManagerService->sync($this->userService->update(
            $user,
            new UserData($data['name'], $data['email'])
        ));
        return $response->withHeader("Location", "/admin-dashboard/users")->withStatus(302);
    }
}