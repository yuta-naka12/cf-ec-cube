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

namespace Eccube\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists(CsvType::class, false)) {
    /**
     * CsvType
     *
     * @ORM\Table(name="mtb_csv_type")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\Master\CsvTypeRepository")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    class CsvType extends \Eccube\Entity\Master\AbstractMasterEntity
    {
        /**
         * @var integer
         */
        const CSV_TYPE_PRODUCT = 1;

        /**
         * @var integer
         */
        const CSV_TYPE_CUSTOMER = 2;

        /**
         * @var integer
         */
        const CSV_TYPE_ORDER = 3;

        /**
         * @var integer
         */
        const CSV_TYPE_SHIPPING = 4;

        /**
         * @var integer
         */
        const CSV_TYPE_CATEGORY = 5;

        /**
         * @var integer
         */
        const CSV_TYPE_BRAND = 6;

        /**
         * @var integer
         */
        const CSV_TYPE_GIFT = 7;

        /**
         * @var integer
         */
        const CSV_TYPE_MAKER = 8;

        /**
         * @var integer
         */
        const CSV_TYPE_TAG = 9;

        /**
         * @var integer
         */
        const CSV_TYPE_EVENT = 10;

        /**
         * @var integer
         */
        const CSV_TYPE_GENRE = 11;

        /**
         * @var integer
         */
        const CSV_TYPE_SUPPLIER = 12;

        /**
         * @var integer
         */
        const CSV_TYPE_PRODUCT_CLASS = 13;
    }
}
