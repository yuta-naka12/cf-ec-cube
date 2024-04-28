<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\Status;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class StatusRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, Status::class);
    }
} 