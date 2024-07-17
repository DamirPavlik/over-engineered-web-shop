<?php

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('products')]
#[HasLifecycleCallbacks]
class Product
{
    use HasTimestamps;

    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $description;

    #[Column]
    private int $price;

    #[Column(name: "stock_quantity")]
    private int $stockQuantity;

    #[ManyToOne(inversedBy: "products")]
    private Category $category;

    #[OneToMany(targetEntity: OrderItems::class, mappedBy: "products")]
    private Collection $orderItems;

    #[OneToMany(targetEntity: Review::class, mappedBy: "user")]
    private Collection $reviews;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
        $this->reviews    = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Product
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): Product
    {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): Product
    {
        $this->description = $description;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): Product
    {
        $this->price = $price;
        return $this;
    }

    public function getStockQuantity(): int
    {
        return $this->stockQuantity;
    }

    public function setStockQuantity(int $stockQuantity): Product
    {
        $this->stockQuantity = $stockQuantity;
        return $this;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function setCategory(Category $category): Product
    {
        $category->addProducts($this);
        $this->category = $category;
        return $this;
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItems(OrderItems $orderItems): Product
    {
        $this->orderItems->add($orderItems);
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReviews(Review $reviews): Product
    {
        $this->reviews->add($reviews);
        return $this;
    }
}