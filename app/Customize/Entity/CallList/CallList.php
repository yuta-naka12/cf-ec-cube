<?php

namespace Customize\Entity\CallList;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\CallList\CallList')) {
    /**
     * Contact
     *
     * @ORM\Table(name="dtb_call_list")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\CallList\CallListRepository")
     */
    class CallList extends \Eccube\Entity\AbstractEntity
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
         * @var \Eccube\Entity\Customer
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="Customer")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
         * })
         */
        private $Customer;

        /**
         * @var \Customize\Entity\CallList\CallListGroup
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\CallList\CallListGroup", inversedBy="CallLists")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="call_list_group_id", referencedColumnName="id")
         * })
         */
        private $CallListGroup;

        /**
         * メモ
         * @var string
         *
         * @ORM\Column(name="memo", type="string", nullable=true)
         */
        private $memo;

        /**
         * status_id
         * @var string
         *
         * @ORM\Column(name="status_id", type="integer", nullable=true)
         */
        private $status_id;

        /**
         * 索引
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
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
         * Get id.
         *
         * @return int
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set customer.
         *
         * @param \Eccube\Entity\Customer|null $customer
         *
         * @return CallList
         */
        public function setCustomer(\Eccube\Entity\Customer $customer = null)
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

        /**
         * Set CallListGroup.
         *
         * @param \Customize\Entity\CallList\CallListGroup|null $group
         *
         * @return CallListGroup
         */
        public function setCallListGroup(\Customize\Entity\CallList\CallListGroup $group = null)
        {
            $this->CallListGroup = $group;

            return $this;
        }

        /**
         * Get CallListGroup.
         *
         * @return \Customize\Entity\CallList\CallListGroup|null
         */
        public function getCallListGroup()
        {
            return $this->CallListGroup;
        }


        /**
         * Set memo
         *
         * @param string|null $value
         *
         * @return CallList
         */
        public function setMemo($value = null)
        {
            $this->memo = $value;

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
         * Set customer_id
         *
         * @param string|null $value
         *
         * @return CallList
         */
        public function setCustomerId($value = null)
        {
            $this->customer_id = $value;

            return $this;
        }

        /**
         * Get customer_id.
         *
         * @return string|null
         */
        public function getCustomerId()
        {
            return $this->customer_id;
        }

        /**
         * Set call_list_group_id
         *
         * @param string|null $value
         *
         * @return CallList
         */
        public function setCallListGroupId($value = null)
        {
            $this->call_list_group_id = $value;

            return $this;
        }

        /**
         * Get call_list_group_id.
         *
         * @return string|null
         */
        public function getCallListGroupId()
        {
            return $this->call_list_group_id;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return CallList
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
         * Set status_id
         *
         * @param integer|null $status_id
         *
         * @return CallList
         */
        public function setStatusId($value = null)
        {
            $this->status_id = $value;

            return $this;
        }

        /**
         * Get status_id.
         *
         * @return integer|null
         */
        public function getStatusId()
        {
            return $this->status_id;
        }

        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return CallList
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
         * @return CallList
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
    }
}
