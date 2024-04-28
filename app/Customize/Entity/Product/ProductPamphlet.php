<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Product\ProductPamphlet')) {
    /**
     * ProductPamphlet
     *
     * @ORM\Table(name="dtb_product_pamphlet")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductGiftRepository")
     */
    class ProductPamphlet extends \Eccube\Entity\AbstractEntity
    {
        /**
         * ID
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * パンフレットコード
         * @var string
         *
         * @ORM\Column(name="pamphlet_id", type="string", nullable=true)
         */
        private $pamphlet_id;

        /**
         * 商品名
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * 税抜価格
         * @var integer
         *
         * @ORM\Column(name="no_tax_price", type="integer", nullable=true)
         */
        private $no_tax_price;

        /**
         * 入力可能開始期間
         * @var string
         *
         * @ORM\Column(name="input_possible_started_at", type="date", nullable=true)
         */
        private $input_possible_started_at;

        /**
         * 入力可能終了期間
         * @var string
         *
         * @ORM\Column(name="input_possible_ended_at", type="date", nullable=true)
         */
        private $input_possible_ended_at;

        /**
         * 商品紹介区分
         * @var string
         *
         * @ORM\Column(name="introduction_class", type="string", nullable=true)
         */
        private $introduction_class;

        /**
         * 新商品区分
         * @var string
         *
         * @ORM\Column(name="new_product_class", type="string", nullable=true)
         */
        private $new_product_class;

        /**
         * 定期購入商品区分
         * @var string
         *
         * @ORM\Column(name="subscription_class", type="string", nullable=true)
         */
        private $subscription_class;

        /**
         * 通常価格
         * @var integer
         *
         * @ORM\Column(name="price", type="integer", nullable=true)
         */
        private $price;

        /**
         * 期間価格
         * @var integer
         *
         * @ORM\Column(name="period_price", type="integer", nullable=true)
         */
        private $period_price;

        /**
         * まとめ買い価格
         * @var integer
         *
         * @ORM\Column(name="bulk_price", type="integer", nullable=true)
         */
        private $bulk_price;

        /**
         * 定期購入価格
         * @var integer
         *
         * @ORM\Column(name="subscription_price", type="integer", nullable=true)
         */
        private $subscription_price;

        /**
         * 割引期間FROM
         * @var string
         *
         * @ORM\Column(name="discount_period_started_at", type="date", nullable=true)
         */
        private $discount_period_started_at;

        /**
         * 割引期間TO
         * @var string
         *
         * @ORM\Column(name="discount_period_ended_at", type="date", nullable=true)
         */
        private $discount_period_ended_at;

        /**
         * まとめ買いグループコード
         * @var string
         *
         * @ORM\Column(name="bulk_group_id", type="string", nullable=true)
         */
        private $bulk_group_id;

        /**
         * ポイント率
         * @var integer
         *
         * @ORM\Column(name="point_rate", type="integer", nullable=true)
         */
        private $point_rate;

        /**
         * キャンペーンポイント率
         * @var integer
         *
         * @ORM\Column(name="campaign_point_rate", type="integer", nullable=true)
         */
        private $campaign_point_rate;

        /**
         * キャンペーン期間FROM
         * @var string
         *
         * @ORM\Column(name="campaign_period_started_at", type="date", nullable=true)
         */
        private $campaign_period_started_at;

        /**
         * キャンペーン期間TO
         * @var string
         *
         * @ORM\Column(name="campaign_period_ended_at", type="date", nullable=true)
         */
        private $campaign_period_ended_at;

        /**
         * 仕入額
         * @var integer
         *
         * @ORM\Column(name="purchase_amount", type="integer", nullable=true)
         */
        private $purchase_amount;

        /**
         * 期間仕入額
         * @var integer
         *
         * @ORM\Column(name="period_purchase_amount", type="integer", nullable=true)
         */
        private $period_purchase_amount;

        /**
         * 包材
         * @var integer
         *
         * @ORM\Column(name="packaging_material", type="integer", nullable=true)
         */
        private $packaging_material;

        /**
         * 決済
         * @var integer
         *
         * @ORM\Column(name="settlement", type="integer", nullable=true)
         */
        private $settlement;

        /**
         * 返済
         * @var integer
         *
         * @ORM\Column(name="pay_back_debt", type="integer", nullable=true)
         */
        private $pay_back_debt;

        /**
         * ドライ
         * @var integer
         *
         * @ORM\Column(name="dry", type="integer", nullable=true)
         */
        private $dry;

        /**
         * 保管料
         * @var integer
         *
         * @ORM\Column(name="storage_fee", type="integer", nullable=true)
         */
        private $storage_fee;

        /**
         * 金利
         * @var integer
         *
         * @ORM\Column(name="interest_rate", type="integer", nullable=true)
         */
        private $interest_rate;

        /**
         * マージン
         * @var integer
         *
         * @ORM\Column(name="margin", type="integer", nullable=true)
         */
        private $margin;

        /**
         * 期間仕入FROM
         * @var integer
         *
         * @ORM\Column(name="period_purchase_started_at", type="date", nullable=true)
         */
        private $period_purchase_started_at;

        /**
         * 期間仕入TO
         * @var integer
         *
         * @ORM\Column(name="period_purchase_ended_at", type="date", nullable=true)
         */
        private $period_purchase_ended_at;

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
         * Set id
         *
         * @param integer $value
         *
         * @return ProductPamphlet
         */
        public function setId($value = null)
        {
            $this->id = $value;

            return $this;
        }

        /**
         * Set pamphlet_id
         *
         * @param integer $value
         *
         * @return ProductPamphlet
         */
        public function setPamphletId($value)
        {
            $this->pamphlet_id = $value;

            return $this;
        }

        /**
         * Get pamphlet_id.
         *
         * @return integer|null
         */
        public function getPamphletId()
        {
            return $this->pamphlet_id;
        }

        /**
         * Set name
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setName($value)
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
         * Get no_tax_price
         *
         * @return integer|null
         */
        public function getNoTaxPrice()
        {
            return $this->no_tax_price;
        }

        /**
         * Set no_tax_price
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setNoTaxPrice($value)
        {
            $this->no_tax_price = $value;

            return $this;
        }

        /**
         * Get input_possible_started_at
         *
         * @return string|null
         */
        public function getInputPossibleStartedAt()
        {
            return $this->input_possible_started_at;
        }

        /**
         * Set input_possible_started_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setInputPossibleStartedAt($value)
        {
            $this->input_possible_started_at = $value;

            return $this;
        }

        /**
         * Get input_possible_ended_at
         *
         * @return string|null
         */
        public function getInputPossibleEndedAt()
        {
            return $this->input_possible_ended_at;
        }

        /**
         * Set input_possible_ended_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setInputPossibleEndedAt($value)
        {
            $this->input_possible_ended_at = $value;

            return $this;
        }

        /**
         * Get introduction_class
         *
         * @return string|null
         */
        public function getIntroductionClass()
        {
            return $this->introduction_class;
        }

        /**
         * Set introduction_class
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setIntroductionClass($value)
        {
            $this->introduction_class = $value;

            return $this;
        }

        /**
         * Get new_product_class
         *
         * @return string|null
         */
        public function getNewProductClass()
        {
            return $this->new_product_class;
        }

        /**
         * Set new_product_class
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setNewProductClass($value)
        {
            $this->new_product_class = $value;

            return $this;
        }

        /**
         * Get subscription_class
         *
         * @return string|null
         */
        public function getSubscriptionClass()
        {
            return $this->subscription_class;
        }

        /**
         * Set subscription_class
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setSubscriptionClass($value)
        {
            $this->subscription_class = $value;

            return $this;
        }

        /**
         * Get price
         *
         * @return string|null
         */
        public function getPrice()
        {
            return $this->price;
        }

        /**
         * Set price
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPrice($value)
        {
            $this->price = $value;

            return $this;
        }

        /**
         * Get period_price
         *
         * @return string|null
         */
        public function getPeriodPrice()
        {
            return $this->period_price;
        }

        /**
         * Set period_price
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPeriodPrice($value)
        {
            $this->period_price = $value;

            return $this;
        }

        /**
         * Get bulk_price
         *
         * @return string|null
         */
        public function getBulkPrice()
        {
            return $this->bulk_price;
        }

        /**
         * Set bulk_price
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setBulkPrice($value)
        {
            $this->bulk_price = $value;

            return $this;
        }

        /**
         * Get subscription_price
         *
         * @return string|null
         */
        public function getSubscriptionPrice()
        {
            return $this->subscription_price;
        }

        /**
         * Set subscription_price
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setSubscriptionPrice($value)
        {
            $this->subscription_price = $value;

            return $this;
        }

        /**
         * Get discount_period_started_at
         *
         * @return string|null
         */
        public function getDiscountPeriodStartedAt()
        {
            return $this->discount_period_started_at;
        }

        /**
         * Set discount_period_started_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setDiscountPeriodStartedAt($value)
        {
            $this->discount_period_started_at = $value;

            return $this;
        }

        /**
         * Get discount_period_ended_at
         *
         * @return string|null
         */
        public function getDiscountPeriodEndedAt()
        {
            return $this->discount_period_ended_at;
        }

        /**
         * Set bulk_group_id
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setBulkGroupId($value)
        {
            $this->bulk_group_id = $value;

            return $this;
        }

        /**
         * Get bulk_group_id
         *
         * @return string|null
         */
        public function getBulkGroupId()
        {
            return $this->bulk_group_id;
        }

        /**
         * Set point_rate
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPointRate($value)
        {
            $this->point_rate = $value;

            return $this;
        }

        /**
         * Get point_rate
         *
         * @return string|null
         */
        public function getPointRate()
        {
            return $this->point_rate;
        }

        /**
         * Set campaign_point_rate
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setCampaignPointRate($value)
        {
            $this->campaign_point_rate = $value;

            return $this;
        }

        /**
         * Get campaign_point_rate
         *
         * @return string|null
         */
        public function getCampaignPointRate()
        {
            return $this->campaign_point_rate;
        }

        /**
         * Set campaign_period_started_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setCampaignPeriodStartedAt($value)
        {
            $this->campaign_period_started_at = $value;

            return $this;
        }

        /**
         * Get campaign_period_started_at
         *
         * @return string|null
         */
        public function getCampaignPeriodStartedAt()
        {
            return $this->campaign_period_started_at;
        }

        /**
         * Set campaign_period_ended_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setCampaignPeriodEndedAt($value)
        {
            $this->campaign_period_ended_at = $value;

            return $this;
        }

        /**
         * Get campaign_period_ended_at
         *
         * @return string|null
         */
        public function getCampaignPeriodEndedAt()
        {
            return $this->campaign_period_ended_at;
        }

        /**
         * Set purchase_amount
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPurchaseAmount($value)
        {
            $this->purchase_amount = $value;

            return $this;
        }

        /**
         * Get purchase_amount
         *
         * @return string|null
         */
        public function getPurchaseAmount()
        {
            return $this->purchase_amount;
        }

        /**
         * Set period_purchase_amount
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPeriodPurchaseAmount($value)
        {
            $this->period_purchase_amount = $value;

            return $this;
        }

        /**
         * Get period_purchase_amount
         *
         * @return string|null
         */
        public function getPeriodPurchaseAmount()
        {
            return $this->period_purchase_amount;
        }

        /**
         * Set packaging_material
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPackagingMaterial($value)
        {
            $this->packaging_material = $value;

            return $this;
        }

        /**
         * Get packaging_material
         *
         * @return string|null
         */
        public function getPackagingMaterial()
        {
            return $this->packaging_material;
        }

        /**
         * Set settlement
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setSettlement($value)
        {
            $this->settlement = $value;

            return $this;
        }

        /**
         * Get settlement
         *
         * @return string|null
         */
        public function getSettlement()
        {
            return $this->settlement;
        }

        /**
         * Set pay_back_debt
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPayBackDebt($value)
        {
            $this->pay_back_debt = $value;

            return $this;
        }

        /**
         * Get pay_back_debt
         *
         * @return string|null
         */
        public function getPayBackDebt()
        {
            return $this->pay_back_debt;
        }

        /**
         * Set dry
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setDry($value)
        {
            $this->dry = $value;

            return $this;
        }

        /**
         * Get dry
         *
         * @return string|null
         */
        public function getDry()
        {
            return $this->dry;
        }

        /**
         * Set storage_fee
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setStorageFee($value)
        {
            $this->storage_fee = $value;

            return $this;
        }

        /**
         * Get storage_fee
         *
         * @return string|null
         */
        public function getStorageFee()
        {
            return $this->storage_fee;
        }

        /**
         * Set margin
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setMargin($value)
        {
            $this->margin = $value;

            return $this;
        }

        /**
         * Get margin
         *
         * @return string|null
         */
        public function getMargin()
        {
            return $this->margin;
        }

        /**
         * Set period_purchase_started_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPeriodPurchaseStartedAt($value)
        {
            $this->period_purchase_started_at = $value;

            return $this;
        }

        /**
         * Get period_purchase_started_at
         *
         * @return string|null
         */
        public function getPeriodPurchaseStartedAt()
        {
            return $this->period_purchase_started_at;
        }

        /**
         * Set period_purchase_ended_at
         *
         * @param string $value
         *
         * @return ProductPamphlet
         */
        public function setPeriodPurchaseEndedAt($value)
        {
            $this->period_purchase_ended_at = $value;

            return $this;
        }

        /**
         * Get period_purchase_ended_at
         *
         * @return string|null
         */
        public function getPeriodPurchaseEndedAt()
        {
            return $this->period_purchase_ended_at;
        }
    }
}
