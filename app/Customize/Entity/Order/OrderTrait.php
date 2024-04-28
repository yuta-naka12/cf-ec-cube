<?php

namespace Customize\Entity\Order;

#DBにアクセスするためのライブラリなどを読み込み
use Customize\Entity\Master\DeliveryType;
use Customize\Entity\Shipping\ShippingGroup;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Customize\Entity\Order\Sender;
use Eccube\Entity\Master\PurchasingGroup;

#拡張をする対象エンティティの指定
/**
 * @Eccube\EntityExtension("Eccube\Entity\Order")
 */

trait OrderTrait //ファイル名と合わせる
{
    /**
     * ビル名
     * @var string
     * @ORM\Column(name="addr03", type="string", precision=12, nullable=true, length=32)
     */
    private $addr03;

    /**
     * 部署名
     * @var string
     * @ORM\Column(name="department", type="string", precision=12, nullable=true, length=32)
     */
    private $department;

    /**
     * システム状態
     * @var string
     * @ORM\Column(name="system_status", type="string", precision=12, nullable=true, length=32)
     */
    private $system_status;

    /**
     * 注文処理状態
     * @var string
     * @ORM\Column(name="order_processing_status", type="string", precision=12, nullable=true, length=32)
     */
    private $order_processing_status;

    /**
     * セッション番号
     * @var string
     * @ORM\Column(name="session_number", type="string", precision=12, nullable=true, length=32)
     */
    private $session_number;

    /**
     * 注文発生場所
     * @var string
     * @ORM\Column(name="place_order", type="string", precision=12, nullable=true, length=32)
     */
    private $place_order;

    /**
     * サイト識別子
     * @var string
     * @ORM\Column(name="site_id", type="string", precision=12, nullable=true, length=32)
     */
    private $site_id;

    /**
     * SAコード
     * @var string
     * @ORM\Column(name="sa_code", type="string", precision=12, nullable=true, length=32)
     */
    private $sa_code;

    /**
     * 担当者
     * @var string
     * @ORM\Column(name="person_in_charge", type="string", precision=12, nullable=true, length=32)
     */
    private $person_in_charge;

    /**
     * 注文確認日
     * @var \DateTime|null
     *
     * @ORM\Column(name="order_date_confirm", type="datetimetz", nullable=true)
     */
    private $order_date_confirm;

    /**
     * 配達日
     * @var \DateTime|null
     *
     * @ORM\Column(name="delivery_date", type="datetimetz", nullable=true)
     */
    private $delivery_date;

    /**
     * 配達予定日
     * @var \DateTime|null
     *
     * @ORM\Column(name="scheduled_delivery_date", type="datetimetz", nullable=true)
     */
    private $scheduled_delivery_date;

    /**
     * 出荷日
     * @var \DateTime|null
     *
     * @ORM\Column(name="shipping_date", type="datetimetz", nullable=true)
     */
    private $shipping_date;

    /**
     * 出荷指示日
     * @var \DateTime|null
     *
     * @ORM\Column(name="shipping_instruction_date", type="datetimetz", nullable=true)
     */
    private $shipping_instruction_date;

    /**
     * キャンセル日
     * @var \DateTime|null
     *
     * @ORM\Column(name="cancel_date", type="datetimetz", nullable=true)
     */
    private $cancel_date;

    /**
     * 返品日
     * @var \DateTime|null
     *
     * @ORM\Column(name="return_date", type="datetimetz", nullable=true)
     */
    private $return_date;

    /**
     * 請求日
     * @var \DateTime|null
     *
     * @ORM\Column(name="billing_date", type="datetimetz", nullable=true)
     */
    private $billing_date;

    /**
     * 基幹連携日
     * @var \DateTime|null
     *
     * @ORM\Column(name="core_collaboration_date", type="datetimetz", nullable=true)
     */
    private $core_collaboration_date;

    /**
     * 注文区分
     * @var string
     *
     * @ORM\Column(name="order_category", type="string", nullable=true)
     */
    private $order_category;

    /**
     * 配送手段
     * @var string
     *
     * @ORM\Column(name="shipping_method", type="datetimetz", nullable=true)
     */
    private $shipping_method;

    /**
     * 請求区分
     * @var string
     *
     * @ORM\Column(name="billing_category", type="datetimetz", nullable=true)
     */
    private $billing_category;

    /**
     * オーダーID2
     * @var string
     *
     * @ORM\Column(name="order_id_2", type="integer", nullable=true)
     */
    private $order_id_2;

    /**
     * オーダーID3
     * @var string
     *
     * @ORM\Column(name="order_id_3", type="integer", nullable=true)
     */
    private $order_id_3;

    /**
     * 宅配区分
     * @var string
     *
     * @ORM\Column(name="delivery_category", type="string", nullable=true)
     */
    private $delivery_category;

    /**
     * 基幹会員区分
     * @var string
     *
     * @ORM\Column(name="core_member_category", type="string", nullable=true)
     */
    private $core_member_category;

    /**
     * 配達種別
     * @var string
     *
     * @ORM\Column(name="delivery_type", type="string", nullable=true)
     */
    private $delivery_type;

    /**
     * 配送希望時間帯
     * @var string
     *
     * @ORM\Column(name="desired_delivery_time", type="string", nullable=true)
     */
    private $desired_delivery_time;

    /**
     * クール便料金
     * @var string
     *
     * @ORM\Column(name="cool_delivery_fee", type="string", nullable=true)
     */
    private $cool_delivery_fee;

    /**
     * 送り状番号
     * @var string
     *
     * @ORM\Column(name="invoice_number", type="string", nullable=true)
     */
    private $invoice_number;

    /**
     * お問い合わせURL
     * @var string
     *
     * @ORM\Column(name="luggage_inquiry_url", type="string", nullable=true)
     */
    private $luggage_inquiry_url;

    /**
     * @var \Customize\Entity\Order\Sender
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Order\Sender", inversedBy="Orders", cascade={"persist"})
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="sender_id", referencedColumnName="id")
     * })
     */
    private $Sender;

    /**
     * 購入グループ
     * @var \Eccube\Entity\Master\PurchasingGroup
     *
     * @ORM\OneToOne(targetEntity="Eccube\Entity\Master\PurchasingGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="purchasing_group_id", referencedColumnName="id")
     * })
     */
    private $PurchasingGroup;

    /**
     * 配送種別
     * @var \Customize\Entity\Master\DeliveryType
     *
     * @ORM\OneToOne(targetEntity="Customize\Entity\Master\DeliveryType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="delivery_type_id", referencedColumnName="id")
     * })
     */
    private $DeliveryType;

    /**
     * @var \Customize\Entity\Shipping\ShippingGroup
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Shipping\ShippingGroup", inversedBy="Order")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="shipping_group_id", referencedColumnName="id")
     * })
     */
    private $ShippingGroup;


    /**
     * Set addr03.
     *
     * @param null $value
     * @return ShippingGroup
     */
    public function setAddr03($value = null)
    {
        $this->addr03 = $value;

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
     * Set department.
     *
     * @param null $department
     * @return ShippingGroup
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
     * Set orderDateConfirm.
     *
     * @param \DateTime|null $orderDateConfirm
     *
     * @return Order
     */
    public function setOrderDateConfirm($orderDateConfirm = null)
    {
        $this->order_date_confirm = $orderDateConfirm;

        return $this;
    }

    /**
     * Get orderDateConfirm.
     *
     * @return \DateTime|null
     */
    public function getOrderDateConfirm()
    {
        return $this->order_date_confirm;
    }

    /**
     * Set deliveryDate.
     *
     * @param \DateTime|null $deliveryDate
     *
     * @return Order
     */
    public function setDeliveryDate($deliveryDate = null)
    {
        $this->delivery_date = $deliveryDate;

        return $this;
    }

    /**
     * Get deliveryDate.
     *
     * @return \DateTime|null
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * Set scheduledDeliveryDate.
     *
     * @param \DateTime|null $scheduledDeliveryDate
     *
     * @return Order
     */
    public function setScheduledDeliveryDate($scheduledDeliveryDate = null)
    {
        $this->scheduled_delivery_date = $scheduledDeliveryDate;

        return $this;
    }


    /**
     * Get scheduledDeliveryDate.
     *
     * @return \DateTime|null
     */
    public function getScheduledDeliveryDate()
    {
        return $this->scheduled_delivery_date;
    }

    /**
     * Set shippingDate.
     *
     * @param \DateTime|null $shippingDate
     *
     * @return Order
     */
    public function setShippingDate($shippingDate = null)
    {
        $this->shipping_date = $shippingDate;

        return $this;
    }


    /**
     * Get shippingDate.
     *
     * @return \DateTime|null
     */
    public function getShippingDate()
    {
        return $this->shipping_date;
    }

    /**
     * Set shippingInstructionDate.
     *
     * @param \DateTime|null $shippingInstructionDate
     *
     * @return Order
     */
    public function setShippingInstructionDate($shippingInstructionDate = null)
    {
        $this->shipping_instruction_date = $shippingInstructionDate;

        return $this;
    }


    /**
     * Get shippingInstructionDate.
     *
     * @return \DateTime|null
     */
    public function getShippingInstructionDate()
    {
        return $this->shipping_instruction_date;
    }

    /**
     * Set cancelDate.
     *
     * @param \DateTime|null $cancelDate
     *
     * @return Order
     */
    public function setCancelDate($cancelDate = null)
    {
        $this->cancel_date = $cancelDate;

        return $this;
    }


    /**
     * Get cancelDate.
     *
     * @return \DateTime|null
     */
    public function getCancelDate()
    {
        return $this->cancel_date;
    }

    /**
     * Set returnDate.
     *
     * @param \DateTime|null $returnDate
     *
     * @return Order
     */
    public function setReturnDate($returnDate = null)
    {
        $this->return_date = $returnDate;

        return $this;
    }


    /**
     * Get returnDate.
     *
     * @return \DateTime|null
     */
    public function getReturnDate()
    {
        return $this->return_date;
    }

    /**
     * Set billingDate.
     *
     * @param \DateTime|null $billingDate
     *
     * @return Order
     */
    public function setBillingDate($billingDate = null)
    {
        $this->billing_date = $billingDate;

        return $this;
    }


    /**
     * Get billingDate.
     *
     * @return \DateTime|null
     */
    public function getBillingDate()
    {
        return $this->billing_date;
    }

    /**
     * Set coreCollaborationDate.
     *
     * @param \DateTime|null $coreCollaborationDate
     *
     * @return Order
     */
    public function setCoreCollaborationDate($coreCollaborationDate = null)
    {
        $this->core_collaboration_date = $coreCollaborationDate;

        return $this;
    }

    /**
     * Get coreCollaborationDate.
     *
     * @return \DateTime|null
     */
    public function getCoreCollaborationDate()
    {
        return $this->core_collaboration_date;
    }

    /**
     * Set orderCategory.
     *
     * @param string $orderCategory
     *
     * @return Order
     */
    public function setOrderCategory($orderCategory = null)
    {
        $this->order_category = $orderCategory;

        return $this;
    }

    /**
     * Get orderCategory.
     *
     * @return string
     */
    public function getOrderCategory()
    {
        return $this->order_category;
    }

    /**
     * Set shippingMethod.
     *
     * @param string $shippingMethod
     *
     * @return Order
     */
    public function setShippingMethod($shippingMethod = null)
    {
        $this->shipping_method = $shippingMethod;

        return $this;
    }

    /**
     * Get shippingMethod.
     *
     * @return string
     */
    public function getShippingMethod()
    {
        return $this->shipping_method;
    }

    /**
     * Set billingCategory.
     *
     * @param string $billingCategory
     *
     * @return Order
     */
    public function setBillingCategory($billingCategory = null)
    {
        $this->billing_category = $billingCategory;

        return $this;
    }

    /**
     * Get billingCategory.
     *
     * @return string
     */
    public function getBillingCategory()
    {
        return $this->billing_category;
    }

    /**
     * Set orderId2.
     *
     * @param string|null $orderId2
     *
     * @return Order
     */
    public function setOrderId2($orderId2 = null)
    {
        $this->order_id_2 = $orderId2;

        return $this;
    }

    /**
     * Get orderId2.
     *
     * @return string|null
     */
    public function getOrderId2()
    {
        return $this->order_id_2;
    }

    /**
     * Set orderId3.
     *
     * @param string|null $orderId3
     *
     * @return Order
     */
    public function setOrderId3($orderId3 = null)
    {
        $this->order_id_3 = $orderId3;

        return $this;
    }

    /**
     * Get orderId3.
     *
     * @return string|null
     */
    public function getOrderId3()
    {
        return $this->order_id_3;
    }

    /**
     * Set deliveryCategory.
     *
     * @param string|null $deliveryCategory
     *
     * @return Order
     */
    public function setDeliveryCategory($deliveryCategory = null)
    {
        $this->delivery_category = $deliveryCategory;

        return $this;
    }

    /**
     * Get deliveryCategory.
     *
     * @return string|null
     */
    public function getDeliveryCategory()
    {
        return $this->delivery_category;
    }

    /**
     * Set coreMemberCategory.
     *
     * @param string|null $coreMemberCategory
     *
     * @return Order
     */
    public function setCoreMemberCategory($coreMemberCategory = null)
    {
        $this->core_member_category = $coreMemberCategory;

        return $this;
    }

    /**
     * Get coreMemberCategory.
     *
     * @return string|null
     */
    public function getCoreMemberCategory()
    {
        return $this->core_member_category;
    }

    /**
     * Set orderProcessingStatus.
     *
     * @param string|null $orderProcessingStatus
     *
     * @return Order
     */
    public function setOrderProcessingStatus($orderProcessingStatus = null)
    {
        $this->order_processing_status = $orderProcessingStatus;

        return $this;
    }

    /**
     * Get orderProcessingStatus.
     *
     * @return string|null
     */
    public function getOrderProcessingStatus()
    {
        return $this->order_processing_status;
    }

    /**
     * Set desiredDeliveryTime.
     *
     * @param string|null $desiredDeliveryTime
     *
     * @return Order
     */
    public function setDesiredDeliveryTime($desiredDeliveryTime = null)
    {
        $this->desired_delivery_time = $desiredDeliveryTime;

        return $this;
    }

    /**
     * Get desiredDeliveryTime.
     *
     * @return string|null
     */
    public function getDesiredDeliveryTime()
    {
        return $this->desired_delivery_time;
    }

    /**
     * Set coolDeliveryFee.
     *
     * @param string|null $coolDeliveryFee
     *
     * @return Order
     */
    public function setCoolDeliveryFee($coolDeliveryFee = null)
    {
        $this->cool_delivery_fee = $coolDeliveryFee;

        return $this;
    }

    /**
     * Get coolDeliveryFee.
     *
     * @return string|null
     */
    public function getCoolDeliveryFee()
    {
        return $this->cool_delivery_fee;
    }

    /**
     * Set invoiceNumber.
     *
     * @param string|null $invoiceNumber
     *
     * @return Order
     */
    public function setInvoiceNumber($invoiceNumber = null)
    {
        $this->invoice_number = $invoiceNumber;

        return $this;
    }

    /**
     * Get invoiceNumber.
     *
     * @return string|null
     */
    public function getInvoiceNumber()
    {
        return $this->invoice_number;
    }

    /**
     * Set luggageInquiryUrl.
     *
     * @param string|null $luggageInquiryUrl
     *
     * @return Order
     */
    public function setLuggageInquiryUrl($luggageInquiryUrl = null)
    {
        $this->luggage_inquiry_url = $luggageInquiryUrl;

        return $this;
    }

    /**
     * Get luggage$luggageInquiryUrl.
     *
     * @return string|null
     */
    public function getLuggageInquiryUrl()
    {
        return $this->luggage_inquiry_url;
    }

    /**
     * Set Sender.
     *
     * @param \Customize\Entity\Order\Sender $sender
     *
     * @return Order
     */
    public function setSender(Sender $sender)
    {
        $this->Sender = $sender;

        return $this;
    }

    /**
     * Get Sender.
     *
     * @return \Customize\Entity\Order\Sender
     */
    public function getSender()
    {
        return $this->Sender;
    }

    /**
     * Set PurchasingGroup.
     *
     * @param \Eccube\Entity\Master\PurchasingGroup|null $purchasingGroup
     *
     * @return Order
     */
    public function setPurchasingGroup(PurchasingGroup $purchasingGroup = null)
    {
        $this->PurchasingGroup = $purchasingGroup;

        return $this;
    }

    /**
     * Get PurchasingGroup.
     *
     * @return ShippingGroup|null
     */
    public function getShippingGroup()
    {
        return $this->ShippingGroup;
    }

    /**
     * Set ShippingGroup.
     *
     * @param ShippingGroup|null $shippingGroup
     *
     * @return Order
     */
    public function setShippingGroup(ShippingGroup $shippingGroup = null)
    {
        $this->ShippingGroup = $shippingGroup;

        return $this;
    }

    /**
     * Get PurchasingGroup.
     *
     * @return \Eccube\Entity\Master\PurchasingGroup|null
     */
    public function getPurchasingGroup()
    {
        return $this->PurchasingGroup;
    }

    /**
     * Set DeliveryType.
     *
     * @param DeliveryType|null $shippingGroup
     *
     * @return Order
     */
    public function setDeliveryType(DeliveryType $deliveryType = null)
    {
        $this->DeliveryType = $deliveryType;

        return $this;
    }

    /**
     * Get DeliveryType.
     *
     * @return DeliveryType|null
     */
    public function getDeliveryType()
    {
        return $this->DeliveryType;
    }

    /**
     * Get DeliveryTypeId.
     *
     * @return integer|null
     */
    public function getDeliveryTypeId()
    {
        return $this->DeliveryType ? $this->DeliveryType->getId() : null;
    }
}
