<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\ProductShortName;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductShortNameRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, ProductShortName::class);
    }
} 