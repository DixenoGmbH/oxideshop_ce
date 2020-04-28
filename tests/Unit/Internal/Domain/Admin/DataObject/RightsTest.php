<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Tests\Unit\Internal\Domain\Admin\DataObject;

use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\Rights;

class RightsTest extends TestCase
{
    public function rightsProvider()
    {
        return [
            ['malladmin'],
            ['1'],
            ['254'],
        ];
    }
    /**
     * @dataProvider rightsProvider
     */
    public function testFromUserInput($testRights)
    {
        $testRights = 'malladmin';
        $rights = Rights::fromString($testRights);

        $this->assertEquals($testRights, $rights);
    }

    public function testFailsFromUserInput()
    {
        $this->expectException(InvalidArgumentException::class);

        Rights::fromString('0');

        $this->expectException(InvalidArgumentException::class);

        Rights::fromString('asdasd');
    }
}
