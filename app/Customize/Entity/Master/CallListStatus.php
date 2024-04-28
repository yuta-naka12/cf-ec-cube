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

if (!class_exists(CallListStatus::class, false)) {
    /**
     * CallListStatus
     *
     * @ORM\Table(name="mtb_calllist_status")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repositor\Admin\Master\CallListStatusRepository")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    class CallListStatus extends \Eccube\Entity\Master\AbstractMasterEntity
    {
        /**
         * 未処理
         * @deprecated
         */
        const UNPROCESSED = 1;

        /**
         * 処理済み
         * @deprecated
         */
        const PROCESSED = 2;

        /**
         * 処理済み(休み処理)
         * @deprecated
         */
        const NON_ATTENDANCE_PROCESSED = 3;

        /**
         * キャンセル.
         */
        const CANCEL = 4;

        /**
         * 事務所受け.
         */
        const OFFICE_OFFERED = 5;
    }
}
