<?php

namespace Customize\Repository\Master;

use Customize\Entity\Master\IntroduceGood;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class IntroduceGoodRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
{
    parent::__construct($registry, IntroduceGood::class);
    }
} 