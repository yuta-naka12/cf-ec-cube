<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\Import;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ImportRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, Import::class);
    }
} 