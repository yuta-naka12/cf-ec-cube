<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\OrderTimeZone;use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class OrderTimeZoneRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, OrderTimeZone::class);
    }
}
