<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\EcLinkage;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class EcLinkageRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, EcLinkage::class);
    }
} 