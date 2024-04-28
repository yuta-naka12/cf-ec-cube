<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\PackingManagement;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class PackingManagementRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, PackingManagement::class);
    }
} 