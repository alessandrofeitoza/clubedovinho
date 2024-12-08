<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\User;
use App\ValueObject\Address;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CustomerFixtures extends Fixture implements DependentFixtureInterface
{
    public const string REFERENCE_PREFIX = 'customer';

    public const DATA = [
        [
            'user' => UserFixtures::ID_1,
            'phone' => '85987651234',
            'addresses' => [
                [
                    'street' => 'Av Augusto dos Anjos',
                    'number' => '123',
                    'complement' => 'Casa A',
                    'district' => 'Aldeota',
                    'city' => 'Fortaleza',
                    'zip' => '60150161',
                    'state' => 'CE',
                ],
                [
                    'street' => 'Rua Vicente Leite',
                    'number' => '2024',
                    'complement' => null,
                    'district' => 'Meireles',
                    'city' => 'Fortaleza',
                    'state' => 'CE',
                    'zip' => '60430120',
                ],
            ],
        ],
        [
            'user' => UserFixtures::ID_2,
            'phone' => '85987653444',
            'addresses' => [
                [
                    'street' => 'Rua dos Jardins',
                    'number' => '12',
                    'complement' => null,
                    'district' => 'Jurema',
                    'city' => 'Caucaia',
                    'state' => 'CE',
                    'zip' => '60452590',
                ],
            ]
        ],
        [
            'user' => UserFixtures::ID_3,
            'phone' => '85982334233',
            'addresses' => [],
        ],
    ];

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $data) {
            $user = $this->getReference(
                name: UserFixtures::REFERENCE_PREFIX . '-' . $data['user'],
                class: User::class
            );

            $customer = new Customer($user);
            $customer->setPhone($data['phone']);

            foreach ($data['addresses'] as $item) {
                $address = new Address($item['zip']);
                $address->setStreet($item['street']);
                $address->setNumber($item['number']);
                $address->setComplement($item['complement']);
                $address->setDistrict($item['district']);
                $address->setCity($item['city']);
                $address->setState($item['state']);

                $customer->addAddress($address);
            }

            $this->setReference(self::REFERENCE_PREFIX . '-' . $data['user'], $customer);
            $manager->persist($customer);
        }

        $manager->flush();
    }
}
