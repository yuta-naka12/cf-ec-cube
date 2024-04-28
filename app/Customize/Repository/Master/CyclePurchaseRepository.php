<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\CyclePurchase;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CyclePurchaseRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, CyclePurchase::class);
    }
} 