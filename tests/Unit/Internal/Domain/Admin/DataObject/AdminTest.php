<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Domain\Admin\DataObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\Admin;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\PasswordHash;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\Rights;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\UserName;
use OxidEsales\EshopCommunity\Tests\Integration\Internal\ContainerTrait;

class AdminTest extends TestCase
{
    use ContainerTrait;

    public function testFromUserInput()
    {
        $testPassword = 'somePassword';

        $admin = Admin::withValidation(
            '550e8400e29b11d4a716446655440000',
            UserName::fromString('test@oxideshop.de'),
            PasswordHash::fromPassword($testPassword, PASSWORD_ARGON2I),
            Rights::fromString('malladmin'),
            1
        );

        $this->assertEquals('550e8400e29b11d4a716446655440000', $admin->getId());
        $this->assertEquals('test@oxideshop.de', $admin->getUserName());
        $this->assertNotEquals($testPassword, $admin->getPasswordHash());
        $this->assertEquals('malladmin', $admin->getRights());
        $this->assertEquals('1', $admin->getShopId());

        $this->assertTrue(
            $admin->getPasswordHash()->verify($testPassword)
        );
    }

    public function testFailsWithWrongUUID()
    {
        $this->expectException(InvalidArgumentException::class);

        Admin::withValidation(
            '550e8400e29b11d4a716446655440000asdasdasd',
            UserName::fromString('test@oxideshop.de'),
            PasswordHash::fromPassword('somePassword', PASSWORD_ARGON2I),
            Rights::fromString('malladmin'),
            1
        );
    }

    public function testFailsWithWrongShopId()
    {
        $this->expectException(InvalidArgumentException::class);

        Admin::withValidation(
            '550e8400e29b11d4a716446655440000',
            UserName::fromString('test@oxideshop.de'),
            PasswordHash::fromPassword('somePassword', PASSWORD_ARGON2I),
            Rights::fromString('malladmin'),
            0
        );
    }

    public function checkChangeToAdmin()
    {
        $admin = Admin::withValidation(
            '550e8400e29b11d4a716446655440000',
            UserName::fromString('test@oxideshop.de'),
            PasswordHash::fromPassword('test1234', PASSWORD_ARGON2I),
            Rights::fromString('1'),
            1
        );

        $newAdmin = $admin->withNewRights(Rights::fromString('malladmin'));

        $this->assertSame('malladmin', (string) $newAdmin->getRights());
        $this->assertNotSame($admin, $newAdmin);
    }
}
