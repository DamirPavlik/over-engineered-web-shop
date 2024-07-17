<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('order_items')]
class OrderItems
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column]
    private int $quantity;

    #[Column]
    private int $price;

    #[ManyToOne(inversedBy: "order_items")]
    private Order $order;

    #[ManyToOne(inversedBy: "order_items")]
    private Product $product;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): OrderItems
    {
        $this->id = $id;
        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): OrderItems
    {
        $this->quantity = $quantity;
        return $this;
    }

    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): OrderItems
    {
        $this->price = $price;
        return $this;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }

    public function setOrder(Order $order): OrderItems
    {
        $order->addOrderItems($this);
        $this->order = $order;
        return $this;
    }

    public function getProduct(): Product
    {
        return $this->product;
    }

    public function setProduct(Product $product): OrderItems
    {
        $product->addOrderItems($this);
        $this->product = $product;
        return $this;
    }

}