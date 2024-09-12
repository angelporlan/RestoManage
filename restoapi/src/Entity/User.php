<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $address = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?float $totalSpent = null;

    #[ORM\Column]
    private ?float $counterMonneyOffer = null;

    #[ORM\Column]
    private ?int $totalOrders = null;

    #[ORM\Column]
    private ?int $ordersForDiscount = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $discountCode = [];

    #[ORM\Column(type: Types::ARRAY)]
    private array $orders = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getTotalSpent(): ?float
    {
        return $this->totalSpent;
    }

    public function setTotalSpent(float $totalSpent): static
    {
        $this->totalSpent = $totalSpent;

        return $this;
    }

    public function getCounterMonneyOffer(): ?float
    {
        return $this->counterMonneyOffer;
    }

    public function setCounterMonneyOffer(float $counterMonneyOffer): static
    {
        $this->counterMonneyOffer = $counterMonneyOffer;

        return $this;
    }

    public function getTotalOrders(): ?int
    {
        return $this->totalOrders;
    }

    public function setTotalOrders(int $totalOrders): static
    {
        $this->totalOrders = $totalOrders;

        return $this;
    }

    public function getOrdersForDiscount(): ?int
    {
        return $this->ordersForDiscount;
    }

    public function setOrdersForDiscount(int $ordersForDiscount): static
    {
        $this->ordersForDiscount = $ordersForDiscount;

        return $this;
    }

    public function getDiscountCode(): array
    {
        return $this->discountCode;
    }

    public function setDiscountCode(array $discountCode): static
    {
        $this->discountCode = $discountCode;

        return $this;
    }

    public function getOrders(): array
    {
        return $this->orders;
    }

    public function setOrders(array $orders): static
    {
        $this->orders = $orders;

        return $this;
    }
}
