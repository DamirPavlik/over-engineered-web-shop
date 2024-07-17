<?php

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('reviews')]
#[HasLifecycleCallbacks]
class Review
{
    use HasTimestamps;

    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private int $rating;

    #[ManyToOne(inversedBy: "reviews")]
    private Product $product;

    #[ManyToOne(inversedBy: "reviews")]
    private User $user;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Review
    {
        $this->id = $id;
        return $this;
    }

    public function getRating(): int
    {
        return $this->rating;
    }

    public function setRating(int $rating): Review
    {
        $this->rating = $rating;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): Review
    {
        $product->addReviews($this);
        $this->product = $product;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Review
    {
        $this->user = $user;
        return $this;
    }


}