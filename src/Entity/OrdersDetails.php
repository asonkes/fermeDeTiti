<?php

namespace App\Entity;

use App\Repository\OrdersDetailsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersDetailsRepository::class)]
class OrdersDetails
{
    #[ORM\Column]
    private ?int $quantity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ordersDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?orders $orders = null;

    #[ORM\Id]
    #[ORM\ManyToOne(inversedBy: 'ordersDetails')]
    #[ORM\JoinColumn(nullable: false)]
    private ?products $products = null;

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): static
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getOrders(): ?orders
    {
        return $this->orders;
    }

    public function setOrders(?orders $orders): static
    {
        $this->orders = $orders;

        return $this;
    }

    public function getProducts(): ?products
    {
        return $this->products;
    }

    public function setProducts(?products $products): static
    {
        $this->products = $products;

        return $this;
    }
}
