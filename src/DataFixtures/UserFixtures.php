<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use App\Security\UserSecurity;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\Uuid;

class UserFixtures extends Fixture
{
    public const string REFERENCE_PREFIX = 'user';
    public const string ID_1 = '1d17aefa-1f9d-3ee1-a79b-84c34a4b6137';
    public const string ID_2 = '581567ad-53d8-39a1-a19f-c7ce1a4d01f7';
    public const string ID_3 = '333af1d9-3233-37e1-a29c-64cdfa4761d2';

    public const DATA = [
        [
            'id' => self::ID_1,
            'name' => 'Alessandro Feitoza',
            'email' => 'alessandro@email.com',
        ],
        [
            'id' => self::ID_2,
            'name' => 'Chico Caucaia',
            'email' => 'chico@email.com',
        ],
        [
            'id' => self::ID_3,
            'name' => 'Maria das Dores',
            'email' => 'maria@email.com',
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        $password = UserSecurity::encryptPassword('123456');

        foreach (self::DATA as $data) {
            $item = new User(new Uuid($data['id']), $data['email']);
            $item->setName($data['name']);
            $item->setPassword($password);

            $this->setReference(self::REFERENCE_PREFIX . '-' . $data['id'], $item);
            $manager->persist($item);
        }

        $manager->flush();
    }
}
