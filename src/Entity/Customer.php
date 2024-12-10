<?php

declare(strict_types=1);

namespace App\Entity;

use App\ValueObject\Address;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
class Customer
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;

    #[ORM\Column(length: 20)]
    private string $phone;

    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\OneToOne(targetEntity: User::class, cascade: ['persist'], orphanRemoval: true)]
    private User $user;

    #[ORM\Column]
    private array $addresses = [];

    public function __construct(User $user)
    {
        $this->id = $user->getId();
        $this->user = $user;
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function getAddresses(): array
    {
        return array_map(
            fn (array $data) => Address::fill($data),
            $this->addresses
        );
    }

    public function addAddress(Address $address): void
    {
        $this->addresses[] = $address->toArray();
    }
}
