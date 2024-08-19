<?php

namespace App\DataObjects;

use App\Entity\Category;

class ProductData
{
    public function __construct(
        public readonly string $name,
        public readonly int $categoryId,
        public readonly string $description,
        public readonly int $price,
        public readonly int $stockQuantity,
    )
    {
    }
}