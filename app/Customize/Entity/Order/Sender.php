<?php

namespace Customize\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Master\Country;
use Eccube\Entity\Master\Pref;
use Eccube\Entity\Order;

if (!class_exists('\Customize\Entity\Order\Sender')) {
    /**
     * Sender
     *
     * @ORM\Table(name="dtb_sender")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\SenderRepository")
     */
    class Sender extends \Eccube\Entity\AbstractEntity
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
         * @var string
         *
         * @ORM\Column(name="kana01", type="string", length=255, nullable=true)
         */
        private $kana01;

        /**
         * @var string
         *
         * @ORM\Column(name="kana02", type="string", length=255, nullable=true)
         */
        private $kana02;

        /**
         * @var string|null
         *
         * @ORM\Column(name="phone_number", type="string", length=14, nullable=true)
         */
        private $phone_number;

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
         * @var string|null
         *
         * @ORM\Column(name="company_name", type="string", length=255, nullable=true)
         */
        private $company_name;

        /**
         * @var string|null
         *
         * @ORM\Column(name="department", type="string", length=255, nullable=true)
         */
        private $department;

        /**
         * @var int|null
         *
         * @ORM\Column(name="sort_no", type="smallint", nullable=true, options={"unsigned":true})
         */
        private $sort_no;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="create_date", type="datetimetz")
         */
        private $create_date;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="update_date", type="datetimetz")
         */
        private $update_date;

        /**
         * @var \Doctrine\Common\Collections\Collection|Order[]
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Order", mappedBy="Sender", cascade={"persist","remove"})
         */
        private $Orders;

        /**
         * @var \Eccube\Entity\Master\Country
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\Country")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="country_id", referencedColumnName="id")
         * })
         */
        private $Country;

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
         * Constructor
         */
        public function __construct()
        {
            $this->Orders = new \Doctrine\Common\Collections\ArrayCollection();
        }

        /**
         * Set id.
         *
         * @param string $id
         *
         * @return Sender
         */
        public function setId($id)
        {
            $this->id = $id;

            return $this;
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
         * Set name01.
         *
         * @param string $name01
         *
         * @return Sender
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
         * @return Sender
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
         * @param string $kana01
         *
         * @return Sender
         */
        public function setKana01($kana01)
        {
            $this->kana01 = $kana01;

            return $this;
        }

        /**
         * Get kana01.
         *
         * @return string
         */
        public function getKana01()
        {
            return $this->kana01;
        }

        /**
         * Set kana02.
         *
         * @param string $kana02
         *
         * @return Sender
         */
        public function setKana02($kana02)
        {
            $this->kana02 = $kana02;

            return $this;
        }

        /**
         * Get kana02.
         *
         * @return string
         */
        public function getKana02()
        {
            return $this->kana02;
        }

        /**
         * Set companyName.
         *
         * @param string|null $companyName
         *
         * @return Sender
         */
        public function setCompanyName($companyName = null)
        {
            $this->company_name = $companyName;

            return $this;
        }

        /**
         * Get companyName.
         *
         * @return string|null
         */
        public function getCompanyName()
        {
            return $this->company_name;
        }

        /**
         * Set department.
         *
         * @param string|null $department
         *
         * @return Sender
         */
        public function setDepartment($department = null)
        {
            $this->department = $department;

            return $this;
        }

        /**
         * Get department.
         *
         * @return string|null
         */
        public function getDepartment()
        {
            return $this->department;
        }

        /**
         * Set phone_number.
         *
         * @param string|null $phone_number
         *
         * @return Sender
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
         * Set postal_code.
         *
         * @param string|null $postal_code
         *
         * @return Sender
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
         * @return Sender
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
         * @return Sender
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
         * @return Sender
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
         * Set sortNo.
         *
         * @param int|null $sortNo
         *
         * @return Sender
         */
        public function setSortNo($sortNo = null)
        {
            $this->sort_no = $sortNo;

            return $this;
        }

        /**
         * Get sortNo.
         *
         * @return int|null
         */
        public function getSortNo()
        {
            return $this->sort_no;
        }

        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return Sender
         */
        public function setCreateDate($createDate)
        {
            $this->create_date = $createDate;

            return $this;
        }

        /**
         * Get createDate.
         *
         * @return \DateTime
         */
        public function getCreateDate()
        {
            return $this->create_date;
        }

        /**
         * Set updateDate.
         *
         * @param \DateTime $updateDate
         *
         * @return Sender
         */
        public function setUpdateDate($updateDate)
        {
            $this->update_date = $updateDate;

            return $this;
        }

        /**
         * Get updateDate.
         *
         * @return \DateTime
         */
        public function getUpdateDate()
        {
            return $this->update_date;
        }

        /**
         * Set country.
         *
         * @param \Eccube\Entity\Master\Country|null $country
         *
         * @return Sender
         */
        public function setCountry(Country $country = null)
        {
            $this->Country = $country;

            return $this;
        }

        /**
         * Get country.
         *
         * @return \Eccube\Entity\Master\Country|null
         */
        public function getCountry()
        {
            return $this->Country;
        }

        /**
         * Set pref.
         *
         * @param \Eccube\Entity\Master\Pref|null $pref
         *
         * @return Sender
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

        /**
         * Add Orders.
         *
         * @param \Eccube\Entity\Order $order
         *
         * @return Sender
         */
        public function addOrder(Order $order)
        {
            $this->Orders[] = $order;

            return $this;
        }

        /**
         * Remove Orders.
         *
         * @param \Eccube\Entity\Order $order
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeOrder(Order $order)
        {
            return $this->Orders->removeElement($order);
        }

        /**
         * Get Orders.
         *
         * @return \Doctrine\Common\Collections\Collection|\Eccube\Entity\Order[]
         */
        public function getOrders()
        {
            return $this->Orders;
        }
    }
}
