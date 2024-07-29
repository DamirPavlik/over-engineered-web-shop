<?php

namespace App\Controllers;

use App\Contracts\EntityManagerServiceInterface;
use App\Contracts\ValidatorFactoryInterface;
use App\DataObjects\ProductData;
use App\Entity\Product;
use App\ResponseFormatter;
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
        private readonly ResponseFormatter $responseFormatter,
    ) {}

    public function index(Request $request, Response $response): Response
    {
        return $this->twig->render($response, 'dashboard/products/products.twig');
    }

    public function addProduct(Request $request, Response $response): Response
    {
        $data = $this->validatorFactory->make(ProductValidator::class)->validate($request->getParsedBody());
        $product = $this->productService->create(new ProductData(
            name: $data['name'],
            categoryId: $data['category'],
            description: $data['description'],
            price: $data['price'],
            stockQuantity: $data['stockQuantity']
        ));

        $this->entityManagerService->sync($product);

        return $response->withHeader("Location",  '/admin-dashboard/products')->withStatus(302);
    }

    public function load(Response $response): Response
    {
        $data = $this->productService->getAll();
        return $this->responseFormatter->asJson($response, $data);
    }

    public function delete(Response $response, Request $request, Product $product): Response
    {
        $this->entityManagerService->delete($product, true);
        return $response;
    }

    public function update(Response $response, Request $request, Product $product): Response
    {
        $data = $this->validatorFactory->make(ProductValidator::class)->validate($request->getParsedBody());
        $this->entityManagerService->sync($this->productService->update(
            $product,
            new ProductData(
                name: $data['name'],
                categoryId: (int) $data['category'],
                description: $data['description'],
                price: $data['price'],
                stockQuantity: $data['stockQuantity'],
            )
        ));

        return $response->withHeader('Location',  '/admin-dashboard/products')->withStatus(302);
    }

}