<?php

namespace Customize\Entity\Contact;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Contact\Contact')) {
    /**
     * Contact
     *
     * @ORM\Table(name="dtb_contact")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ContactRepository")
     */
    class Contact extends \Eccube\Entity\AbstractEntity
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
         * 姓
         * @var string
         *
         * @ORM\Column(name="family_name", type="string", nullable=true)
         */
        private $family_name;

        /**
         * 名
         * @var string
         *
         * @ORM\Column(name="given_name", type="string", nullable=true)
         */
        private $given_name;

        /**
         * オーダーID
         * @var string
         *
         * @ORM\Column(name="order_id", type="string", nullable=true)
         */
        private $order_id;

        /**
         * @var \Eccube\Entity\Customer
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Customer", inversedBy="Contacts")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="customer_id", referencedColumnName="id")
         * })
         */
        private $Customer;

        /**
         * @var \Eccube\Entity\Product
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Product", inversedBy="Contacts")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
         * })
         */
        private $product_id;

        /**
         * メールアドレス
         * @var string
         *
         * @ORM\Column(name="email", type="string", nullable=true, length=64)
         */
        private $email;

        /**
         * 電話番号
         * @var string
         *
         * @ORM\Column(name="tel", type="string", nullable=true, length=64)
         */
        private $tel;

        /**
         * 状態
         * @var integer
         *
         * @ORM\Column(name="status", type="integer", nullable=true, length=64)
         */
        private $status;

        /**
         * お問合せ区分1
         * @var integer
         *
         * @ORM\Column(name="contact_class_1", type="integer", nullable=true)
         */
        private $contact_class_1;

        /**
         * お問合せ区分2
         * @var integer
         *
         * @ORM\Column(name="contact_class_2", type="integer", nullable=true)
         */
        private $contact_class_2;

        /**
         * 備考
         * @var string
         *
         * @ORM\Column(name="memo", type="string", nullable=true, length=1028)
         */
        private $memo;

        /**
         * 備考
         * @var string
         *
         * @ORM\Column(name="title", type="string", nullable=true, length=128)
         */
        private $title;

        /**
         * 内容
         * @var string
         *
         * @ORM\Column(name="content", type="string", nullable=true, length=1028)
         */
        private $content;

        /**
         * 分類
         * @var integer
         *
         * @ORM\Column(name="classification", type="integer", nullable=true, length=1028)
         */
        private $classification;

        /**
         * 表示
         * @var boolean
         *
         * @ORM\Column(name="is_display", type="boolean", nullable=true, length=1028)
         */
        private $is_display;

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
         * Set family_name
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setFamilyName($value = null)
        {
            $this->family_name = $value;

            return $this;
        }

        /**
         * Get family_name.
         *
         * @return string|null
         */
        public function getFamilyName()
        {
            return $this->family_name;
        }

        /**
         * Set given_name
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setGivenName($value = null)
        {
            $this->given_name = $value;

            return $this;
        }

        /**
         * Get given_name.
         *
         * @return string|null
         */
        public function getGivenName()
        {
            return $this->given_name;
        }

        /**
         * Set order_id
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setOrderId($value = null)
        {
            $this->order_id = $value;

            return $this;
        }

        /**
         * Get order_id.
         *
         * @return string|null
         */
        public function getOrderId()
        {
            return $this->order_id;
        }

        /**
         * Set customer_id
         *
         * @param string|null $value
         *
         * @return Contact
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
         * Set product_id
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setProductId($value = null)
        {
            $this->product_id = $value;

            return $this;
        }

        /**
         * Get product_id.
         *
         * @return string|null
         */
        public function getProductId()
        {
            return $this->product_id;
        }

        /**
         * Set email
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setEmail($value = null)
        {
            $this->email = $value;

            return $this;
        }

        /**
         * Get email.
         *
         * @return string|null
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set tel
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setTel($value = null)
        {
            $this->tel = $value;

            return $this;
        }

        /**
         * Get tel.
         *
         * @return string|null
         */
        public function getTel()
        {
            return $this->tel;
        }

        /**
         * Set status
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setStatus($value = null)
        {
            $this->status = $value;

            return $this;
        }

        /**
         * Get status.
         *
         * @return string|null
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * Set contact_class_1
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setContactClass1($value = null)
        {
            $this->contact_class_1 = $value;

            return $this;
        }

        /**
         * Get contact_class_1.
         *
         * @return string|null
         */
        public function getContactClass1()
        {
            return $this->contact_class_1;
        }

        /**
         * Set contact_class_2
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setContactClass2($value = null)
        {
            $this->contact_class_2 = $value;

            return $this;
        }

        /**
         * Get contact_class_2.
         *
         * @return string|null
         */
        public function getContactClass2()
        {
            return $this->contact_class_2;
        }

        /**
         * Set memo
         *
         * @param string|null $value
         *
         * @return Contact
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
         * Set title
         *
         * @param string|null $value
         *
         * @return Contact
         */
        public function setTitle($value = null)
        {
            $this->title = $value;

            return $this;
        }

        /**
         * Get title.
         *
         * @return string|null
         */
        public function getTitle()
        {
            return $this->title;
        }

        /**
         * Set content
         * @param string|null $value
         *
         * @return Contact
         */
        public function setContent($value = null)
        {
            $this->content = $value;

            return $this;
        }

        /**
         * Get classification.
         * @return string|null
         */
        public function getContent()
        {
            return $this->classification;
        }

        /**
         * Set classification
         * @param string|null $value
         *
         * @return Contact
         */
        public function setClassification($value = null)
        {
            $this->classification = $value;

            return $this;
        }

        /**
         * Get content.
         * @return string|null
         */
        public function getClassification()
        {
            return $this->content;
        }

        /**
         * Set is_display
         * @param string|null $value
         *
         * @return Contact
         */
        public function setIsDisplay($value = null)
        {
            $this->is_display = $value;

            return $this;
        }

        /**
         * Get is_display.
         * @return string|null
         */
        public function getIsDisplay()
        {
            return $this->is_display;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return Contact
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
