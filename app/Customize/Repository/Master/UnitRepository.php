<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\Unit;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class UnitRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, Unit::class);
    }
} 