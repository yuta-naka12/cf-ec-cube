<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\DeliveryCalculation;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DeliveryCalculationRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, DeliveryCalculation::class);
    }
} 