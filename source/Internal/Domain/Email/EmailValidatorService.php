<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Domain\Email;

use InvalidArgumentException;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\UserName;

/**
 * Class EmailValidatorService
 * @package OxidEsales\EshopCommunity\Internal\Domain\Email
 */
class EmailValidatorService implements EmailValidatorServiceInterface
{
    /**
     * @param mixed $email
     *
     * @return bool
     */
    public function isEmailValid($email): bool
    {
        try {
            Username::fromString((string) $email);

            return true;
        } catch (InvalidArgumentException $e) {
            return false;
        }
    }
}
