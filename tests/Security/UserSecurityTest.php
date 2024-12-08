<?php

declare(strict_types=1);

namespace App\Tests\Security;

use App\Security\UserSecurity;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserSecurityTest extends KernelTestCase
{
    public function testEncryptPassword(): void
    {
        $hash = UserSecurity::encryptPassword('123456');

        $this->assertNotEquals('123456', $hash);
        $this->assertTrue(UserSecurity::verifyPassword('123456'));
    }
}
