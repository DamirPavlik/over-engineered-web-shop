<?php

namespace App\Controllers;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\Services\CategoryService;
use App\Services\ProductService;
use App\Validators\ProductValidator;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;

class ProductController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly ValidatorFactoryInterface $validatorFactory,
        private readonly EntityManagerServiceInterface $entityManagerService,
        private readonly ProductService $productService,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/products.twig');
    }

    public function addProduct(Request $request, Response $response): Response
    {
        $data = $this->validatorFactory->make(ProductValidator::class)->validate($request->getParsedBody());
        $product = $this->productService->create($data);

        $this->entityManagerService->sync($product);

        return $response->withHeader("Location",  '/admin-dashboard/products')->withStatus(302);
    }
}