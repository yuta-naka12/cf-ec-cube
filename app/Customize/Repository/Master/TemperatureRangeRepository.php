<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\TemperatureRange;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class TemperatureRangeRepository extends AbstractRepository
{
    /**
     * TemperatureRangeRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TemperatureRange::class);
    }
}
