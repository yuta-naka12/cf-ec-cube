<?php

namespace Customize\Entity\Customer;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\Pref;

if (!class_exists('\Customize\Entity\Customer\ApplyPamphlet')) {
    /**
     * ApplyPamphlet
     *
     * @ORM\Table(name="dtb_apply_pamphlet")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ApplyPamphletRepository")
     */
    class ApplyPamphlet extends \Eccube\Entity\AbstractEntity
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
         * @var string
         *
         * @ORM\Column(name="name01", type="string", length=255)
         */
        private $name01;

        /**
         * @var string
         *
         * @ORM\Column(name="name02", type="string", length=255)
         */
        private $name02;

        /**
         * @var string|null
         *
         * @ORM\Column(name="kana01", type="string", length=255, nullable=true)
         */
        private $kana01;

        /**
         * @var string|null
         *
         * @ORM\Column(name="kana02", type="string", length=255, nullable=true)
         */
        private $kana02;


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
         * @var string
         *
         * @ORM\Column(name="email", type="string", length=255)
         */
        private $email;

        /**
         * @var string|null
         *
         * @ORM\Column(name="phone_number", type="string", length=14, nullable=true)
         */
        private $phone_number;

        /**
         * @var string|null
         *
         * @ORM\Column(name="memo", type="string", length=255, nullable=true)
         */
        private $memo;

        /**
         * 索引
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

        // /**
        //  * Constructor
        //  */
        // public function __construct()
        // {
        //     $this->Product = new \Doctrine\Common\Collections\ArrayCollection();
        // }

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
         * Set name01.
         *
         * @param string $name01
         *
         * @return ApplyPamphlet
         */
        public function setName01($name01)
        {
            $this->name01 = $name01;

            return $this;
        }

        /**
         * Get name01.
         *
         * @return string
         */
        public function getName01()
        {
            return $this->name01;
        }

        /**
         * Set name02.
         *
         * @param string $name02
         *
         * @return ApplyPamphlet
         */
        public function setName02($name02)
        {
            $this->name02 = $name02;

            return $this;
        }

        /**
         * Get name02.
         *
         * @return string
         */
        public function getName02()
        {
            return $this->name02;
        }

        /**
         * Set kana01.
         *
         * @param string|null $kana01
         *
         * @return ApplyPamphlet
         */
        public function setKana01($kana01 = null)
        {
            $this->kana01 = $kana01;

            return $this;
        }

        /**
         * Get kana01.
         *
         * @return string|null
         */
        public function getKana01()
        {
            return $this->kana01;
        }

        /**
         * Set kana02.
         *
         * @param string|null $kana02
         *
         * @return ApplyPamphlet
         */
        public function setKana02($kana02 = null)
        {
            $this->kana02 = $kana02;

            return $this;
        }

        /**
         * Get kana02.
         *
         * @return string|null
         */
        public function getKana02()
        {
            return $this->kana02;
        }

        /**
         * Set postal_code.
         *
         * @param string|null $postal_code
         *
         * @return ApplyPamphlet
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
         * @return ApplyPamphlet
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
         * @return ApplyPamphlet
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
         * @return ApplyPamphlet
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
         * Set email.
         *
         * @param string $email
         *
         * @return ApplyPamphlet
         */
        public function setEmail($email)
        {
            $this->email = $email;

            return $this;
        }

        /**
         * Get email.
         *
         * @return string
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set phone_number.
         *
         * @param string|null $phone_number
         *
         * @return ApplyPamphlet
         */
        public function setPhoneNumber($phone_number = null)
        {
            $this->phone_number = $phone_number;

            return $this;
        }

        /**
         * Get phone_number.
         *
         * @return string|null
         */
        public function getPhoneNumber()
        {
            return $this->phone_number;
        }

        /**
         * Set memo.
         *
         * @param string|null $memo
         *
         * @return ApplyPamphlet
         */
        public function setMemo($memo = null)
        {
            $this->memo = $memo;

            return $this;
        }

        /**
         * Get memo.
         *
         * @return string|null
         */
        public function getMemo()
        {
            return $this->memo;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductBrand
         */
        public function setSortNo($value = null)
        {
            $this->sort_no = $value;

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
         * @return ApplyPamphlet
         */
        public function setPref(Pref $pref = null)
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
    }
}
