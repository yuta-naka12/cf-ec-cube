<?php

namespace Customize\Entity\CallList;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\CallList\CallListGroup')) {
    /**
     * Contact
     *
     * @ORM\Table(name="dtb_call_list_group")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\CallList\CallListGroupRepository")
     */
    class CallListGroup extends \Eccube\Entity\AbstractEntity
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
         * @var \Customize\Entity\CallList\CallList
         *
         * @ORM\OneToMany(targetEntity="Customize\Entity\CallList\CallList", mappedBy="CallListGroup", cascade={"remove"})
         * @ORM\OrderBy({
         *     "id"="ASC"
         * })
         */
        private $CallLists;

        /**
         * @var \Eccube\Entity\Member
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member", inversedBy="Member")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
         * })
         */
        private $Member;

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
         * Get CallList.
         *
         * @return \Customize\Entity\CallList\CallList|null
         */
        public function getCallLists()
        {
            return $this->CallLists;
        }

        /**
         * Set customer.
         *
         * @param \Eccube\Entity\Member|null $member
         *
         * @return CallList
         */
        public function setMember(\Eccube\Entity\Member $member = null)
        {
            $this->Member = $member;

            return $this;
        }

        /**
         * Get customer.
         *
         * @return \Eccube\Entity\Member|null
         */
        public function getMember()
        {
            return $this->Member;
        }

        /**
         * Set member_id
         *
         * @param string|null $value
         *
         * @return CallList
         */
        public function setMemberId($value = null)
        {
            $this->member_id = $value;

            return $this;
        }

        /**
         * Get member_id.
         *
         * @return string|null
         */
        public function getMemberId()
        {
            return $this->member_id;
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
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return CallListGroup
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
         * @return CallListGroup
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
