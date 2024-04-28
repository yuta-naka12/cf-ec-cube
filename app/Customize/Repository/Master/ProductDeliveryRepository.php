<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\ProductDelivery;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProductDeliveryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, ProductDelivery::class);
    }
} 