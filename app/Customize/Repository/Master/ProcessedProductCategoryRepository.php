<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\ProcessedProductCategory;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ProcessedProductCategoryRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, ProcessedProductCategory::class);
    }
} 