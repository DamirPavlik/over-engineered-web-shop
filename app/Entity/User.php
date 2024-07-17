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
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('users')]
#[HasLifecycleCallbacks]
class User
{
    use HasTimestamps;

    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private string $name;

    #[Column]
    private string $email;

    #[Column]
    private string $password;

    #[OneToMany(targetEntity: Order::class, mappedBy: "user")]
    private Collection $orders;

    #[OneToMany(targetEntity: Review::class, mappedBy: "user")]
    private Collection $reviews;

    public function __construct()
    {
        $this->orders  = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getOrders(): Collection
    {
        return $this->orders;
    }

    public function addOrders(Order $orders): User
    {
        $this->orders->add($orders);
        return $this;
    }

    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReviews(Review $reviews): User
    {
        $this->reviews->add($reviews);
        return $this;
    }
}