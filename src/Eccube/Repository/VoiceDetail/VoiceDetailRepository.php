<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Eccube\Repository\VoiceDetail;

use Customize\Entity\Voice\Voice;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Repository\AbstractRepository;
use Eccube\Event\EventArgs;
use Eccube\Repository\VoiceList\VoiceListRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;


class VoiceDetailRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, VoiceDetail::class);
    }

}