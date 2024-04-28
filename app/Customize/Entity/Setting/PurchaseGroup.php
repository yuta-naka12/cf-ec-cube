<?php

namespace Customize\Entity\Setting;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Category;

if (!class_exists('\Customize\Entity\Setting\PurchaseGroup')) {
    /**
     * PurchaseGroup
     *
     * @ORM\Table(name="dtb_purchase_group")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\PurchaseGroupRepository")
     */
    class PurchaseGroup extends \Eccube\Entity\AbstractEntity
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
         * 購入グループ名
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true, length=32)
         */
        private $name;

        /**
         * 配送手段
         * @var integer
         *
         * @ORM\Column(name="delivery_method", type="integer", nullable=true, length=32)
         */
        private $delivery_method;

        /**
         * 配送分割有無
         * @var boolean
         *
         * @ORM\Column(name="is_delivery_separate", type="boolean", nullable=true)
         */
        private $is_delivery_separate;

        /**
         *  送り主変更有無
         * @var boolean
         *
         * @ORM\Column(name="is_change_sender", type="boolean", nullable=true)
         */
        private $is_change_sender;

        /**
         * 単品フラグ
         * @var boolean
         *
         * @ORM\Column(name="is_single", type="boolean", nullable=true)
         */
        private $is_single;

        /**
         * 指定可能な支払方法
         * Payments
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Payment", mappedBy="PurchaseGroup", cascade={"persist","remove"})
         */
        private $Payments;

        /**
         * 配送希望日指定可否
         * @var boolean
         *
         * @ORM\Column(name="is_specify_delivery_date", type="boolean", nullable=true)
         */
        private $is_specify_delivery_date;

        /**
         * リードタイム1
         * @var string
         *
         * @ORM\Column(name="lead_time_01", type="string", nullable=true)
         */
        private $lead_time_01;

        /**
         * リードタイム2
         * @var string
         *
         * @ORM\Column(name="lead_time_02", type="string", nullable=true)
         */
        private $lead_time_02;

        /**
         * 締め時間
         * @var \DateTime
         *
         * @ORM\Column(name="closing_time", type="datetimetz", nullable=true)
         */
        private $closing_time;

        /**
         *  アイコン１
         * @var integer
         *
         * @ORM\Column(name="icon_1", type="string", nullable=true)
         */
        private $icon_1;

        /**
         *  アイコン２
         * @var integer
         *
         * @ORM\Column(name="icon_2", type="string", nullable=true)
         */
        private $icon_2;

        /**
         *  アイコン３
         * @var integer
         *
         * @ORM\Column(name="icon_3", type="string", nullable=true)
         */
        private $icon_3;

        /**
         * 商品詳細コメント
         * @var string
         *
         * @ORM\Column(name="detail_comment", type="string", nullable=true)
         */
        private $detail_comment;

        /**
         * 完了コメント
         * @var string
         *
         * @ORM\Column(name="complete_comment", type="string", nullable=true)
         */
        private $complete_comment;

        /**
         * サンクスコメント
         * @var string
         *
         * @ORM\Column(name="thanks_comment", type="string", nullable=true)
         */
        private $thanks_comment;

        /**
         * 出荷メールコメント
         * @var string
         *
         * @ORM\Column(name="shipping_comment", type="string", nullable=true)
         */
        private $shipping_comment;

        /**
         * 備考(内部用)
         * @var string
         *
         * @ORM\Column(name="memo", type="string", nullable=true)
         */
        private $memo;

        /**
         * 並び順
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
         * @param string|null $value
         *
         * @return PurchaseGroup
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
         * Set delivery_method
         *
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setDeliveryMethod($value = null)
        {
            $this->delivery_method = $value;

            return $this;
        }

        /**
         * Get delivery_method.
         *
         * @return string|null
         */
        public function getDeliveryMethod()
        {
            return $this->delivery_method;
        }

        /**
         * Set is_delivery_separate
         *
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIsDeliverySeparate($value = null)
        {
            $this->is_delivery_separate = $value;

            return $this;
        }

        /**
         * Get is_delivery_separate.
         *
         * @return string|null
         */
        public function getIsDeliverySeparate()
        {
            return $this->is_delivery_separate;
        }

        /**
         * Set is_change_sender
         *
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIsChangeSender($value = null)
        {
            $this->is_change_sender = $value;

            return $this;
        }

        /**
         * Get is_change_sender.
         *
         * @return string|null
         */
        public function getIsChangeSender()
        {
            return $this->is_change_sender;
        }

        /**
         * Set is_single
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIsSingle($value = null)
        {
            $this->is_single = $value;

            return $this;
        }

        /**
         * Get is_single.
         *
         * @return string|null
         */
        public function getIsSingle()
        {
            return $this->is_single;
        }

        /**
         * Set Payments
         *
         * @param string|null $name
         *
         * @return Category
         */
        public function setPayments($value = null)
        {
            $this->Payments = $value;

            return $this;
        }

        /**
         * Get Payments.
         *
         * @return Payments|null
         */
        public function getPayments()
        {
            return $this->Payments;
        }

        /**
         * Set is_specify_delivery_date
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIsSpecifyDeliveryDate($value = null)
        {
            $this->is_specify_delivery_date = $value;

            return $this;
        }

        /**
         * Get is_specify_delivery_date.
         *
         * @return string|null
         */
        public function getIsSpecifyDeliveryDate()
        {
            return $this->is_specify_delivery_date;
        }

        /**
         * Set lead_time_01
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setLeadTime01($value = null)
        {
            $this->lead_time_01 = $value;

            return $this;
        }

        /**
         * Get lead_time_01.
         *
         * @return string|null
         */
        public function getLeadTime01()
        {
            return $this->lead_time_01;
        }

        /**
         * Set lead_time_02
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setLeadTime02($value = null)
        {
            $this->lead_time_02 = $value;

            return $this;
        }

        /**
         * Get lead_time_02.
         *
         * @return string|null
         */
        public function getLeadTime02()
        {
            return $this->lead_time_02;
        }

        /**
         * Set closing_time
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setClosingTime($value = null)
        {
            $this->closing_time = $value;

            return $this;
        }

        /**
         * Get closing_time.
         *
         * @return string|null
         */
        public function getClosingTime()
        {
            return $this->closing_time;
        }

        /**
         * Set icon_1
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIcon1($value = null)
        {
            $this->icon_1 = $value;

            return $this;
        }

        /**
         * Get icon_1.
         *
         * @return string|null
         */
        public function getIcon1()
        {
            return $this->icon_1;
        }

        /**
         * Set icon_2
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIcon2($value = null)
        {
            $this->icon_2 = $value;

            return $this;
        }

        /**
         * Get icon_2.
         *
         * @return string|null
         */
        public function getIcon2()
        {
            return $this->icon_2;
        }

        /**
         * Set icon_3
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setIcon3($value = null)
        {
            $this->icon_3 = $value;

            return $this;
        }

        /**
         * Get icon_3.
         *
         * @return string|null
         */
        public function getIcon3()
        {
            return $this->icon_3;
        }

        /**
         * Set detail_comment
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setDetailComment($value = null)
        {
            $this->detail_comment = $value;

            return $this;
        }

        /**
         * Get detail_comment.
         *
         * @return string|null
         */
        public function getDetailComment()
        {
            return $this->detail_comment;
        }

        /**
         * Set complete_comment
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setCompleteComment($value = null)
        {
            $this->complete_comment = $value;

            return $this;
        }

        /**
         * Get complete_comment.
         *
         * @return string|null
         */
        public function getCompleteComment()
        {
            return $this->complete_comment;
        }

        /**
         * Set thanks_comment
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setThanksComment($value = null)
        {
            $this->thanks_comment = $value;

            return $this;
        }

        /**
         * Get thanks_comment.
         *
         * @return string|null
         */
        public function getThanksComment()
        {
            return $this->thanks_comment;
        }

        /**
         * Set shipping_comment
         * @param string|null $value
         *
         * @return PurchaseGroup
         */
        public function setShippingComment($value = null)
        {
            $this->shipping_comment = $value;

            return $this;
        }

        /**
         * Get shipping_comment.
         *
         * @return string|null
         */
        public function getShippingComment()
        {
            return $this->shipping_comment;
        }

        /**
         * Set memo
         * @param string|null $value
         *
         * @return PurchaseGroup
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
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductEvent
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
