<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\Repack;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RepackRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, Repack::class);
    }
} 