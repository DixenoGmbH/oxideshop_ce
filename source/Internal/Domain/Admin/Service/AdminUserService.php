<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\EshopCommunity\Internal\Domain\Admin\Service;

use OxidEsales\EshopCommunity\Internal\Domain\Admin\Dao\AdminDaoInterface;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\Admin;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\PasswordHash;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\Rights;
use OxidEsales\EshopCommunity\Internal\Domain\Admin\DataObject\UserName;
use OxidEsales\EshopCommunity\Internal\Domain\Authentication\Service\PasswordHashServiceInterface;
use OxidEsales\EshopCommunity\Internal\Transition\Adapter\ShopAdapterInterface;

class AdminUserService
{
    /**
     * @var AdminDaoInterface
     */
    private $adminDao;

    /**
     * @var ShopAdapterInterface
     */
    private $shopAdapter;

    /**
     * @var PasswordHashServiceInterface
     */
    private $passwordHashService;

    /**
     * AdminUserService constructor.
     *
     * @param AdminDaoInterface $adminDao
     * @param ShopAdapterInterface $shopAdapter
     * @param PasswordHashServiceInterface $passwordHashService
     *
     */
    public function __construct(
        AdminDaoInterface $adminDao,
        ShopAdapterInterface $shopAdapter,
        PasswordHashServiceInterface $passwordHashService
    ) {
        $this->adminDao = $adminDao;
        $this->shopAdapter = $shopAdapter;
        $this->passwordHashService = $passwordHashService;
    }

    public function createAdmin(
        string $userName,
        string $password,
        string $rights = Rights::MALL_ADMIN,
        ?int $shopId = null
    ) {
        $this->adminDao->create(Admin::withValidation(
            $this->shopAdapter->generateUniqueId(),
            UserName::fromString($userName),
            PasswordHash::fromHash($this->passwordHashService->hash($password)),
            Rights::fromString($rights),
            $shopId ?? 1
        ));
    }

    public function getAdminByEmail(string $email): Admin
    {
        return $this->adminDao->findByEmail(
            UserName::fromString($email)
        );
    }

    /**
     * @param string $userName aka email
     * @param string $rights shopId of the shop the user should be admin or malladmin
     */
    public function updateToAdmin(
        string $userName,
        string $rights = Rights::MALL_ADMIN
    ) {
        $newAdmin = $this->getAdminByEmail($userName);

        $this->adminDao->update(
            $newAdmin->withNewRights(Rights::fromString($rights))
        );
    }
}
