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

namespace Eccube\Entity;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Eccube\Entity\GiftAddress')) {
    /**
     * Customer
     *
     * @ORM\Table(name="dtb_gift_address")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\GiftAddressRepository")
     */
    class GiftAddress extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var string|null
         *
         * @ORM\Column(name="postal_code", type="string", length=8, nullable=true)
         */
        private $postal_code;

        /**
         * @var string|null
         *
         * @ORM\Column(name="addr01", type="string", length=255, nullable=true)
         */
        private $addr01;

        /**
         * @var string|null
         *
         * @ORM\Column(name="addr02", type="string", length=255, nullable=true)
         */
        private $addr02;

        /**
         * @var string|null
         *
         * @ORM\Column(name="addr03", type="string", length=255, nullable=true)
         */
        private $addr03;


        /**
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

        /**
         * @var \Eccube\Entity\Master\Pref
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\Pref")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="pref_id", referencedColumnName="id")
         * })
         */
        private $Pref;

        /**
         * @var \Eccube\Entity\Customer
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="GiftAddresses")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
         * })
         */
        private $Customer;

        /**
         * Constructor
         */
        public function __construct()
        {
        }

        /**
         * Get id.
         *
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set postal_code.
         *
         * @param string|null $postal_code
         *
         * @return GiftAddress
         */
        public function setPostalCode($postal_code = null)
        {
            $this->postal_code = $postal_code;

            return $this;
        }

        /**
         * Get postal_code.
         *
         * @return string|null
         */
        public function getPostalCode()
        {
            return $this->postal_code;
        }

        /**
         * Set addr01.
         *
         * @param string|null $addr01
         *
         * @return GiftAddress
         */
        public function setAddr01($addr01 = null)
        {
            $this->addr01 = $addr01;

            return $this;
        }

        /**
         * Get addr01.
         *
         * @return string|null
         */
        public function getAddr01()
        {
            return $this->addr01;
        }

        /**
         * Set addr02.
         *
         * @param string|null $addr02
         *
         * @return GiftAddress
         */
        public function setAddr02($addr02 = null)
        {
            $this->addr02 = $addr02;

            return $this;
        }


        /**
         * Get addr02.
         *
         * @return string|null
         */
        public function getAddr02()
        {
            return $this->addr02;
        }

        /**
         * Set addr03.
         *
         * @param string|null $addr03
         *
         * @return GiftAddress
         */
        public function setAddr03($addr03 = null)
        {
            $this->addr03 = $addr03;

            return $this;
        }


        /**
         * Get addr03.
         *
         * @return string|null
         */
        public function getAddr03()
        {
            return $this->addr03;
        }

        /**
         * Set sort_no.
         *
         * @param integer|null $sort_no
         *
         * @return GiftAddress
         */
        public function setSortNo($sort_no)
        {
            $this->sort_no = $sort_no;

            return $this;
        }

        /**
         * Get sort_no.
         *
         * @return integer|null
         */
        public function getSortNo()
        {
            return $this->sort_no;
        }

        /**
         * Set pref.
         *
         * @param \Eccube\Entity\Master\Pref|null $pref
         *
         * @return GiftAddress
         */
        public function setPref(Master\Pref $pref = null)
        {
            $this->Pref = $pref;

            return $this;
        }

        /**
         * Get pref.
         *
         * @return \Eccube\Entity\Master\Pref|null
         */
        public function getPref()
        {
            return $this->Pref;
        }

        /**
         * Set customer.
         *
         * @param \Eccube\Entity\Customer $customer
         *
         * @return GiftAddress
         */
        public function setCustomer(Customer $customer)
        {
            $this->Customer = $customer;

            return $this;
        }

        /**
         * Get customer.
         *
         * @return \Eccube\Entity\Customer|null
         */
        public function getCustomer()
        {
            return $this->Customer;
        }
    }
}
