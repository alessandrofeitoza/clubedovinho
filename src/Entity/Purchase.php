<?php

declare(strict_types=1);

namespace App\Entity;

use App\Enum\PurchaseStatusEnum;
use App\ValueObject\Address;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
class Purchase
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    private Uuid $id;


    #[ORM\JoinTable(name: 'purchases_products')]
    #[ORM\JoinColumn(name: 'purchase_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\InverseJoinColumn(name: 'product_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToMany(targetEntity: Product::class)]
    private Collection $products;

    #[ORM\JoinColumn(name: 'customer_id', referencedColumnName: 'id', nullable: false)]
    #[ORM\ManyToOne(targetEntity: Customer::class)]
    private Customer $customer;

    #[ORM\Column]
    private PurchaseStatusEnum $status;

    #[ORM\Column(type: 'json')]
    private null|array|Address $address = null;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $distance;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $deliveryCost;

    #[ORM\Column(type: 'decimal', precision: 10, scale: 2)]
    private float $totalPrice;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $updatedAt = null;

    public function __construct(Uuid $id)
    {
        $this->id = $id;
        $this->status = PurchaseStatusEnum::OPENED;
        $this->products = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): void
    {
        $this->products->add($product);
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getStatus(): PurchaseStatusEnum
    {
        return $this->status;
    }

    public function setStatus(PurchaseStatusEnum $status): void
    {
        $this->status = $status;
    }

    public function getAddress(): ?Address
    {
        if (null === $this->address) {
            return null;
        }

        return Address::fill($this->address);
    }

    public function setAddress(Address $address): void
    {
        $this->address = $address;
    }

    public function getDistance(): float
    {
        return $this->distance;
    }

    public function setDistance(float $distance): void
    {
        $this->distance = $distance;
    }

    public function getDeliveryCost(): float
    {
        return $this->deliveryCost;
    }

    public function setDeliveryCost(float $deliveryCost): void
    {
        $this->deliveryCost = $deliveryCost;
    }

    public function getTotalPrice(): float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): void
    {
        $this->totalPrice = $totalPrice;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeImmutable $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
