<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\BroadCategory;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class BroadCategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, BroadCategory::class);
    }
} 