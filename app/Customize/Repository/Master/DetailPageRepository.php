<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\DetailPage;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DetailPageRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, DetailPage::class);
    }
} 