<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject;

class Admin
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var UserName
     */
    private $userName;

    /**
     * @var PasswordHash
     */
    private $passwordHash;

    /**
     * @var Rights
     */
    private $rights;

    /**
     * @var int
     */
    private $shopId;

    private function __construct(
        $id,
        UserName $userName,
        PasswordHash $passwordHash,
        Rights $rights,
        int $shopId
    ) {
        $this->id   = $id;
        $this->userName = $userName;
        $this->passwordHash = $passwordHash;
        $this->rights   = $rights;
        $this->shopId   = $shopId;
    }

    public static function withValidation(
        string $userId,
        UserName $userName,
        PasswordHash $passwordHash,
        Rights $rights,
        int $shopId
    ): self {

        if (strlen($userId) !== 32) {
            throw new \InvalidArgumentException();
        }

        if ($shopId <= 0) {
            throw new \InvalidArgumentException();
        }

        return new self(
            $userId,
            $userName,
            $passwordHash,
            $rights,
            $shopId
        );
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getUserName(): UserName
    {
        return $this->userName;
    }

    public function getPasswordHash(): PasswordHash
    {
        return $this->passwordHash;
    }

    public function getRights(): Rights
    {
        return $this->rights;
    }

    public function getShopId(): int
    {
        return $this->shopId;
    }

    public function withNewRights(Rights $rights): self
    {
        $admin = clone $this;
        $admin->rights = $rights;
        return $admin;
    }
}
