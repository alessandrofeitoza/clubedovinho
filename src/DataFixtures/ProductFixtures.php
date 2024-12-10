<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Country;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{
    public const string REFERENCE_PREFIX = 'product';
    public const string ID_1 = '17aefa1d-1df9-31ee-ab79-c8434b4a3761';
    public const string ID_2 = '1567ad58-583d-319a-af19-cc7e4d1af701';
    public const string ID_3 = '3af1d933-3323-317e-ac29-c64d47fad261';
    public const string ID_4 = 'fe32317d-7d53-31e0-a9d9-ca7d4d9ae9a1';

    public const DATA = [
        [
            'id' => self::ID_1,
            'name' => 'Quinta dos Bons Ventos',
            'country' => CountryFixtures::ID_1,
            'category' => CategoryFixtures::ID_1,
            'weight' => 700,
            'price' => 30.90,
            'additionalInfo' => 'Vinho tinto seco',
        ],
        [
            'id' => self::ID_2,
            'name' => 'Casillero del Diablo',
            'country' => CountryFixtures::ID_3,
            'category' => CategoryFixtures::ID_1,
            'weight' => 650,
            'price' => 29.99,
            'additionalInfo' => 'Vinho Branco',
        ],
        [
            'id' => self::ID_3,
            'name' => 'Marques de casa concha',
            'country' => CountryFixtures::ID_3,
            'category' => CategoryFixtures::ID_1,
            'weight' => 780,
            'price' => 109.01,
            'additionalInfo' => 'vinho rosé',
        ],
        [
            'id' => self::ID_4,
            'name' => 'São Braz',
            'country' => CountryFixtures::ID_2,
            'category' => CategoryFixtures::ID_1,
            'weight' => 1500,
            'price' => 5.89,
            'additionalInfo' => 'O vinho do santo forte',
        ],
    ];

    public function getDependencies(): array
    {
        return [
            CategoryFixtures::class,
            CountryFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $data) {
            $item = new Product(new Uuid($data['id']), $data['name']);
            $item->setCategory($this->getReference(
                name: CategoryFixtures::REFERENCE_PREFIX . '-' . $data['category'],
                class: Category::class
            ));
            $item->setCountry($this->getReference(
                name: CountryFixtures::REFERENCE_PREFIX . '-' . $data['country'],
                class: Country::class
            ));
            $item->setWeight($data['weight']);
            $item->setAdditionalInfo($data['additionalInfo']);
            $item->setPrice($data['price']);

            $this->setReference(self::REFERENCE_PREFIX . '-' . $data['id'], $item);
            $manager->persist($item);
        }

        $manager->flush();
    }
}
