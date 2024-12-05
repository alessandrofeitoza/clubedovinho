<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class CategoryFixtures extends Fixture
{
    public const string REFERENCE_PREFIX = 'category';
    public const string ID_1 = '8daefa17-f1d9-46ee-9b70-4843db40376b';
    public const string ID_2 = 'fc67ad15-358d-4a9a-9f17-cc7eed16f703';
    public const string ID_3 = '29f1d93a-2333-437e-8c2d-b64d07fdd26b';
    public const string ID_4 = 'a83231fe-57d3-4be0-b9d2-ca7d9d9be9a7';

    public const DATA = [
        [
            'id' => self::ID_1,
            'name' => 'Vinho',
            'description' => '',
        ],
        [
            'id' => self::ID_2,
            'name' => 'Cachaça',
            'description' => null,
        ],
        [
            'id' => self::ID_3,
            'name' => 'Whisky',
            'description' => 'destilated...',
        ],
        [
            'id' => self::ID_4,
            'name' => 'Refrigerante',
            'description' => null,
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::DATA as $data) {
            $item = new Category(new Uuid($data['id']), $data['name']);
            $item->setDescription($data['description']);

            $this->setReference(self::REFERENCE_PREFIX . '-' . $data['id'], $item);
            $manager->persist($item);
        }

        $manager->flush();
    }
}
