<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\MiddleCategory;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class MiddleCategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, MiddleCategory::class);
    }
} 