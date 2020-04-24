<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Domain\Admin\DataObject;

use PHPUnit\Framework\TestCase;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\PasswordHash;
use OxidEsales\EshopCommunity\Internal\Domain\Authentication\Exception\PasswordHashException;

class PasswordHashTest extends TestCase
{
    public function hashAlgorithmProvider()
    {
        return [
            [PASSWORD_ARGON2I],
            [PASSWORD_BCRYPT],
        ];
    }

    /**
     * @dataProvider hashAlgorithmProvider
     */
    public function testPasswordHashGeneration($algo)
    {
        $testPassword = 'test1234';
        $passwordHash = PasswordHash::fromPassword(
            $testPassword,
            $algo
        );

        $this->assertTrue($passwordHash->verify($testPassword, $passwordHash));

        $newPasswordHash = PasswordHash::fromHash((string) $passwordHash);

        $this->assertNotSame($passwordHash, $newPasswordHash);

        $this->assertTrue($newPasswordHash->verify($testPassword, $newPasswordHash));

        $this->assertFalse($passwordHash->needsRehash($algo));
    }

    public function testFailsFromWrongInput()
    {
        $testPassword = 'test1234';
        $this->expectException(PasswordHashException::class);

        PasswordHash::fromHash($testPassword);
    }
}
