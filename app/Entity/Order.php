<?php

namespace App\Entity;

use App\Entity\Traits\HasTimestamps;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('orders')]
class Order
{
    use HasTimestamps;

    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column(name: "total_amount")]
    private int $totalAmount;

    #[Column(name: "credit_card_number")]
    private int $creditCardNumber;

    #[Column(name: "shipping_address")]
    private string $shippingAddress;

    #[Column(name: "order_date")]
    private \DateTime $orderDate;

    #[ManyToOne(inversedBy: "orders")]
    private User $user;

    #[OneToMany(targetEntity: OrderItems::class, mappedBy: "orders")]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Order
    {
        $this->id = $id;
        return $this;
    }

    public function getTotalAmount(): int
    {
        return $this->totalAmount;
    }

    public function setTotalAmount(int $totalAmount): Order
    {
        $this->totalAmount = $totalAmount;
        return $this;
    }

    public function getCreditCardNumber(): int
    {
        return $this->creditCardNumber;
    }

    public function setCreditCardNumber(int $creditCardNumber): Order
    {
        $this->creditCardNumber = $creditCardNumber;
        return $this;
    }

    public function getShippingAddress(): string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(string $shippingAddress): Order
    {
        $this->shippingAddress = $shippingAddress;
        return $this;
    }

    public function getOrderDate(): \DateTime
    {
        return $this->orderDate;
    }

    public function setOrderDate(\DateTime $orderDate): Order
    {
        $this->orderDate = $orderDate;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): Order
    {
        $this->user = $user;
        return $this;
    }

    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItems(OrderItems $orderItems): Order
    {
        $this->orderItems->add($orderItems);
        return $this;
    }
}