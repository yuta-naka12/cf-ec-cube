<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\DecompressionMethod;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DecompressionMethodRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, DecompressionMethod::class);
    }
} 