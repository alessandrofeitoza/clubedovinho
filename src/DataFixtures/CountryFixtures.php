<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Country;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class CountryFixtures extends Fixture
{
    public const string REFERENCE_PREFIX = 'country';
    public const string ID_1 = 'b2aefa17-d1d9-45ee-7b70-4843db40376a';
    public const string ID_2 = 'b267ad15-d18d-459a-7f17-cc7eed16f70a';
    public const string ID_3 = 'b2f1d93a-d133-457e-7c2d-b64d07fdd26a';
    public const string ID_4 = 'b23231fe-d1d3-45e0-79d2-ca7d9d9be9aa';
    public const string ID_5 = '8f8a0091-d6d1-4c83-8168-5bf3a0241c68';
    public const string ID_6 = 'af8666ac-f2ee-46a8-80aa-47258953a8ae';

    public const DATA = [
        [
            'id' => self::ID_1,
            'name' => 'Argentina',
        ],
        [
            'id' => self::ID_2,
            'name' => 'Brasil',
        ],
        [
            'id' => self::ID_3,
            'name' => 'Chile',
        ],
        [
            'id' => self::ID_4,
            'name' => 'França',
        ],
        [
            'id' => self::ID_5,
            'name' => 'Escócia',
        ],
        [
            'id' => self::ID_6,
            'name' => 'Itália',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $data) {
            $item = new Country(new Uuid($data['id']), $data['name']);

            $this->setReference(self::REFERENCE_PREFIX . '-' . $data['id'], $item);
            $manager->persist($item);
        }

        $manager->flush();
    }
}
