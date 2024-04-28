<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\CourseMaster;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class CourseMasterRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, CourseMaster::class);
    }
} 