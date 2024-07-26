<?php

namespace App\Controllers;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\Entity\Category;
use App\ResponseFormatter;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Validators\CategoryValidator;
use App\Validators\ProductValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class CategoryController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ResponseFormatter $responseFormatter,
        private readonly CategoryService $categoryService,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly EntityManagerServiceInterface $entityManager,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/category/category.twig');
    }

    public function load(Response $response): Response
    {
        $data = $this->categoryService->getAll();
        return $this->responseFormatter->asJson($response, $data);
    }

    public function addCategory(Request $request, Response $response): Response
    {
        $data = $this->validatorFactory->make(CategoryValidator::class)->validate($request->getParsedBody());
        $category = $this->categoryService->create($data);

        $this->entityManager->sync($category);

        return $response->withHeader("Location", "/admin-dashboard/categories")->withStatus(302);
    }

}