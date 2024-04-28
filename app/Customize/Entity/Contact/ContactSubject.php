<?php

namespace Customize\Entity\Contact;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Contact\ContactSubject')) {
    /**
     * ProductBrand
     *
     * @ORM\Table(name="dtb_contact_subject")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ContactSubjectRepository")
     */
    class ContactSubject extends \Eccube\Entity\AbstractEntity
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
         * 件名
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * メール送信先
         * @var string
         *
         * @ORM\Column(name="send_address", type="string", nullable=true, length=64)
         */
        private $send_address;

        /**
         * メール返信元
         * @var string
         *
         * @ORM\Column(name="reply_address", type="string", nullable=true, length=64)
         */
        private $reply_address;

        /**
         * メール返信時Bcc
         * @var boolean
         *
         * @ORM\Column(name="reply_bcc_address", type="boolean", nullable=true, length=64)
         */
        private $reply_bcc_address;

        /**
         * ステータス
         * @var integer
         *
         * @ORM\Column(name="status", type="integer", nullable=true)
         */
        private $status;

        /**
         * 索引
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

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
         * Set name
         *
         * @param string|null $name
         *
         * @return ContactSubject
         */
        public function setName($value = null)
        {
            $this->name = $value;

            return $this;
        }

        /**
         * Get name.
         *
         * @return string|null
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Set send_address
         *
         * @param string|null $send_address
         *
         * @return ContactSubject
         */
        public function setSendAddress($name = null)
        {
            $this->send_address = $name;

            return $this;
        }

        /**
         * Get send_address.
         *
         * @return string|null
         */
        public function getSendAddress()
        {
            return $this->send_address;
        }

        /**
         * Set reply_address
         *
         * @param string|null $send_address
         *
         * @return ContactSubject
         */
        public function setReplyAddress($name = null)
        {
            $this->reply_address = $name;

            return $this;
        }

        /**
         * Get reply_address.
         *
         * @return string|null
         */
        public function getReplyAddress()
        {
            return $this->reply_address;
        }

        /**
         * Set reply_bcc_address
         *
         * @param string|null $reply_bcc_address
         *
         * @return ContactSubject
         */
        public function setReplyBccAddress($name = null)
        {
            $this->reply_bcc_address = $name;

            return $this;
        }

        /**
         * Get reply_bcc_address.
         *
         * @return string|null
         */
        public function getReplyBccAddress()
        {
            return $this->reply_bcc_address;
        }

        /**
         * Set status
         *
         * @param integer|null $status
         *
         * @return ContactSubject
         */
        public function setStatus($value = null)
        {
            $this->status = $value;

            return $this;
        }

        /**
         * Get status.
         *
         * @return integer|null
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ContactSubject
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
    }
}
