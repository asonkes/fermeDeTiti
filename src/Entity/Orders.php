<?php

namespace App\Entity;

use App\Entity\Trait\CreatedAtTrait;
use App\Repository\OrdersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: OrdersRepository::class)]
class Orders
{
    use CreatedAtTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30, unique: true)]
    private ?string $reference = null;

    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $users = null;

    /**
     * @var Collection<int, OrdersDetails>
     */
    #[ORM\OneToMany(targetEntity: OrdersDetails::class, mappedBy: 'orders', orphanRemoval: true, cascade: ['persist'])]
    private Collection $ordersDetails;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $subtotal = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\Column(nullable: true)]
    private ?int $deliveryFee = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $Total = null;

    public function __construct()
    {
        $this->ordersDetails = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReference(): ?string
    {
        return $this->reference;
    }

    public function setReference(string $reference): static
    {
        $this->reference = $reference;

        return $this;
    }

    public function getUsers(): ?Users
    {
        return $this->users;
    }

    public function setUsers(?Users $users): static
    {
        $this->users = $users;

        return $this;
    }

    /**
     * @return Collection<int, OrdersDetails>
     */
    public function getOrdersDetails(): Collection
    {
        return $this->ordersDetails;
    }

    public function addOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if (!$this->ordersDetails->contains($ordersDetail)) {
            $this->ordersDetails->add($ordersDetail);
            $ordersDetail->setOrders($this);
        }

        return $this;
    }

    public function removeOrdersDetail(OrdersDetails $ordersDetail): static
    {
        if ($this->ordersDetails->removeElement($ordersDetail)) {
            // set the owning side to null (unless already changed)
            if ($ordersDetail->getOrders() === $this) {
                $ordersDetail->setOrders(null);
            }
        }

        return $this;
    }

    public function getSubtotal(): ?string
    {
        return $this->subtotal;
    }

    public function setSubtotal(string $subtotal): static
    {
        $this->subtotal = $subtotal;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDeliveryFee(): ?int
    {
        return $this->deliveryFee;
    }

    public function setDeliveryFee(?int $deliveryFee): static
    {
        $this->deliveryFee = $deliveryFee;

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->Total;
    }

    public function setTotal(?string $Total): static
    {
        $this->Total = $Total;

        return $this;
    }
}
