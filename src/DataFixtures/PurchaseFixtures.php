<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Product;
use App\Entity\Purchase;
use App\ValueObject\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class PurchaseFixtures extends Fixture implements DependentFixtureInterface
{
    public const string REFERENCE_PREFIX = 'purchase';
    public const string ID_1 = 'd7aefa19-11df-e1ee-179b-843c4a34ba76';
    public const string ID_2 = '8567ad5d-1583-a1a9-119f-c7ec1af4da70';
    public const string ID_3 = '3af1d933-1332-e1e7-129c-64dcfad47a26';
    public const string ID_4 = 'de323173-17d5-010e-1d99-a7dc9ae4da9a';

    public const DATA = [
        [
            'id' => self::ID_1,
            'products' => [
                ProductFixtures::ID_1,
            ],
            'customer' => UserFixtures::ID_1,
            'deliveryCost' => 10.19,
            'totalPrice' => 200.99,
            'address' => [
                'street' => 'Rua das Carmelias',
                'number' => '342',
                'district' => 'Aldeota',
                'complement' => 'Casa',
                'city' => 'Fortaleza',
                'state' => 'CE',
                'zip' => '60352590',
            ],
            'distance' => 200.41,
        ],
        [
            'id' => self::ID_2,
            'products' => [
                ProductFixtures::ID_2,
            ],
            'customer' => UserFixtures::ID_1,
            'deliveryCost' => 10.19,
            'totalPrice' => 100.99,
            'address' => null,
            'distance' => 100.41,
        ],
        [
            'id' => self::ID_3,
            'products' => [
                ProductFixtures::ID_1,
                ProductFixtures::ID_2,
            ],
            'customer' => UserFixtures::ID_2,
            'deliveryCost' => 10.19,
            'totalPrice' => 100.99,
            'address' => null,
            'distance' => 100.41,
        ],
        [
            'id' => self::ID_4,
            'products' => [
                ProductFixtures::ID_1,
                ProductFixtures::ID_2,
                ProductFixtures::ID_3,
            ],
            'customer' => UserFixtures::ID_3,
            'deliveryCost' => 10.19,
            'totalPrice' => 100.99,
            'address' => [
                'street' => 'Rua das Carmelias',
                'number' => '342',
                'district' => 'Aldeota',
                'complement' => 'Casa',
                'city' => 'Fortaleza',
                'state' => 'CE',
                'zip' => '60352590',
            ],
            'distance' => 90.99,
        ],
    ];

    public function getDependencies(): array
    {
        return [
            CustomerFixtures::class,
            ProductFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $data) {
            $customer = $this->getReference(
                name: CustomerFixtures::REFERENCE_PREFIX . '-' . $data['customer'],
                class: Customer::class
            );

            $address = $customer->getAddresses()[0] ?? null;
            if (null !== $data['address']) {
                $address = Address::fill($data['address']);
            }

            $item = new Purchase(new Uuid($data['id']));
            $item->setAddress($address);
            $item->setCustomer($customer);
            $item->setDistance($data['distance']);
            $item->setDeliveryCost($data['deliveryCost']);
            $item->setTotalPrice($data['totalPrice']);

            foreach ($data['products'] as $product) {
                $item->addProduct($this->getReference(
                    name: ProductFixtures::REFERENCE_PREFIX . '-' . $product,
                    class: Product::class
                ));
            }

            $this->setReference(self::REFERENCE_PREFIX . '-' . $data['id'], $item);
            $manager->persist($item);
        }

        $manager->flush();
    }
}
