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

if (!class_exists(Pref::class, false)) {
    /**
     * Pref
     *
     * @ORM\Table(name="mtb_pref")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\Master\PrefRepository")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    class Pref extends \Eccube\Entity\Master\AbstractMasterEntity
    {
        /**
         * @var string
         *
         * @ORM\Column(name="pref_number", type="string", length=255, nullable=true, unique=true)
         */
        private $pref_number;

        /**
         * Set pref_number.
         *
         * @param string $pref_number
         *
         * @return Pref
         */
        public function setPrefNumber($pref_number)
        {
            $this->pref_number = $pref_number;
        }

        /**
         * Get pref_number.
         *
         * @return string
         */
        public function getPrefNumber()
        {
            return $this->pref_number;
        }
    }
}
