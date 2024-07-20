<?php

namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use App\Entity\Category;
use App\Entity\Product;

class ProductService
{
    public function __construct(private readonly EntityManagerServiceInterface $entityManager)
    {
    }

    public function create(array $data): Product
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($data['category']);

        if (!$category) {
            throw new \Exception("Category not found");
        }

        $product = new Product();
        $product->setName($data['title']);
        $product->setDescription($data['description']);
        $product->setPrice($data['price']);
        $product->setStockQuantity($data['stockQuantity']);
        $product->setCategory($category);

        return $product;
    }
}