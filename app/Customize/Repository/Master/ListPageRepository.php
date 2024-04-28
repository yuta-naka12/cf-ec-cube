<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\ListPage;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ListPageRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, ListPage::class);
    }
} 