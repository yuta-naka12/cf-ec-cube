<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\RegularPurchaseCategory;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RegularPurchaseCategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, RegularPurchaseCategory::class);
    }
}