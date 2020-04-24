<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject;

class UserName
{
    /**
     * @var string
     */
    private $userName;

    private function __construct(string $userName)
    {
        if (filter_var($userName, FILTER_VALIDATE_EMAIL) === false) {
            throw new \InvalidArgumentException();
        }

        $this->userName = $userName;
    }

    public function fromString(string $userName): self
    {
        return new self($userName);
    }


    public function __toString()
    {
        return $this->userName;
    }
}
