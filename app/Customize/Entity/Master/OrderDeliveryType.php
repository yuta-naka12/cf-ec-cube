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

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists(OrderDeliveryType::class, false)) {
    /**
     * CallListStatus
     *
     * @ORM\Table(name="mtb_order_delevery_type")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    class OrderDeliveryType extends \Eccube\Entity\Master\AbstractMasterEntity
    {
        /**
         * 自社便
         */
        const SELF = '1';

        /**
         * 他社便
         */
        const OTHER = '2';
    }
}
