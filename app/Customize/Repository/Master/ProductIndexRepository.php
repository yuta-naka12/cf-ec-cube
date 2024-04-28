<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\ProductIndex;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductIndexRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, ProductIndex::class);
    }
} 