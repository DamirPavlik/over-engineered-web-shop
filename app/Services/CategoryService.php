<?php

namespace App\Services;

use App\Contracts\EntityManagerServiceInterface;
use App\DataObjects\CategoryData;
use App\DataObjects\ProductData;
use App\Entity\Category;

class CategoryService
{
    public function __construct(
        private readonly EntityManagerServiceInterface $entityManager
    ) {}

    public function getAll(): array
    {
        try {
            $queryBuilder = $this->entityManager->getRepository(Category::class)
                ->createQueryBuilder('c')
                ->select('c')
                ->getQuery();

            return $queryBuilder->getArrayResult();
        } catch (\Exception $e) {
            return ["error" => $e];
        }
    }

    public function create(CategoryData $categoryData): Category
    {
        $category = new Category();

        $category->setName($categoryData->name);

        return $category;
    }

    public function update(Category $category, CategoryData $categoryData): Category
    {
        $category->setName($categoryData->name);
        return $category;
    }
}