<?php

namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use App\DataObjects\ProductData;
use App\Entity\Category;
use App\Entity\Product;

class ProductService
{
    public function __construct(private readonly EntityManagerServiceInterface $entityManager)
    {
    }

    public function create(ProductData $productData): Product
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($productData->categoryId);

        if (!$category) {
            throw new \Exception("Category not found");
        }

        $product = new Product();
        $product->setName($productData->name);
        $product->setDescription($productData->description);
        $product->setPrice($productData->price);
        $product->setStockQuantity($productData->stockQuantity);
        $product->setCategory($category);

        return $product;
    }

    public function getAll(): array
    {
        try {
            $queryBuilder = $this->entityManager->getRepository(Product::class)
                ->createQueryBuilder('p')
                ->leftJoin('p.category', 'c')
                ->select('p.id', 'p.name', 'p.description', 'p.price', 'p.stockQuantity', 'p.createdAt', 'p.updatedAt', 'c.id AS category_id')
                ->getQuery();

            $results = $queryBuilder->getArrayResult();

            return array_map(function($product) {
                return [
                    'id' => $product['id'],
                    'name' => $product['name'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stockQuantity' => $product['stockQuantity'],
                    'createdAt' => $product['createdAt'],
                    'updatedAt' => $product['updatedAt'],
                    'category_id' => $product['category_id'],
                ];
            }, $results);
        } catch (\Exception $e) {
            return ["error" => $e->getMessage()];
        }
    }

    public function update(Product $product, ProductData $productData): Product
    {
        $categoryRepository = $this->entityManager->getRepository(Category::class);
        $category = $categoryRepository->find($productData->categoryId);
        $product->setName($productData->name);
        $product->setDescription($productData->description);
        $product->setPrice($productData->price);
        $product->setStockQuantity($productData->stockQuantity);
        $product->setCategory($category);

        return $product;
    }


}