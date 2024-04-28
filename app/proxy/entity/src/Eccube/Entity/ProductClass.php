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

use Customize\Entity\Master\IntroduceGood;
use Customize\Entity\Master\NewProductCategory;
use Customize\Entity\Master\SubscriptionPurchase;
use Customize\Entity\Product\ProductClassProductIcon;
use Customize\Entity\Product\ProductIcon;
use Doctrine\ORM\Mapping as ORM;


    /**
     * ProductClass
     *
     * @ORM\Table(name="dtb_product_class")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductClassRepository")
     */
    class ProductClass extends \Eccube\Entity\AbstractEntity
    {
    use \Customize\Entity\Product\ProductClassTrait;

        private $price01_inc_tax = null;
        private $price02_inc_tax = null;
        private $tax_rate = false;

        /**
         * 商品規格名を含めた商品名を返す.
         *
         * @return string
         */
        public function formattedProductName()
        {
            $productName = $this->getProduct()->getName();
            if ($this->hasClassCategory1()) {
                $productName .= ' - ' . $this->getClassCategory1()->getName();
            }
            if ($this->hasClassCategory2()) {
                $productName .= ' - ' . $this->getClassCategory2()->getName();
            }

            return $productName;
        }

        /**
         * Is Enable
         *
         * @return bool
         *
         * @deprecated
         */
        public function isEnable()
        {
            return $this->getProduct()->isEnable();
        }

        /**
         * Set price01 IncTax
         *
         * @param  string       $price01_inc_tax
         *
         * @return ProductClass
         */
        public function setPrice01IncTax($price01_inc_tax)
        {
            $this->price01_inc_tax = $price01_inc_tax;

            return $this;
        }

        /**
         * Get price01 IncTax
         *
         * @return string
         */
        public function getPrice01IncTax()
        {
            return $this->price01_inc_tax;
        }

        /**
         * Set price02 IncTax
         *
         * @return ProductClass
         */
        public function setPrice02IncTax($price02_inc_tax)
        {
            $this->price02_inc_tax = $price02_inc_tax;

            return $this;
        }

        /**
         * Get price02 IncTax
         *
         * @return string
         */
        public function getPrice02IncTax()
        {
            return $this->price02_inc_tax;
        }

        /**
         * Get StockFind
         *
         * @return bool
         */
        public function getStockFind()
        {
            if ($this->getStock() > 0 || $this->isStockUnlimited()) {
                return true;
            } else {
                return false;
            }
        }

        /**
         * Set tax_rate
         *
         * @param  string $tax_rate
         *
         * @return ProductClass
         */
        public function setTaxRate($tax_rate)
        {
            $this->tax_rate = $tax_rate;

            return $this;
        }

        /**
         * Get tax_rate
         *
         * @return boolean
         */
        public function getTaxRate()
        {
            return $this->tax_rate;
        }

        /**
         * Has ClassCategory1
         *
         * @return boolean
         */
        public function hasClassCategory1()
        {
            return isset($this->ClassCategory1);
        }

        /**
         * Has ClassCategory1
         *
         * @return boolean
         */
        public function hasClassCategory2()
        {
            return isset($this->ClassCategory2);
        }

        /**
         * @var int
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * 商品マスタ
         * @var \Eccube\Entity\Product
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Product", inversedBy="ProductClasses")
         * @ORM\JoinColumns({
         *  @ORM\JoinColumn(name="product_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
         * })
         */
        private $Product;

        /**
         * パンフレットコード
         * @var string
         * @ORM\Column(name="pamphlet_code", type="string", nullable=true)
         */
        private $pamphlet_code;

        /**
         * 販売期間(開始)
         * @var string
         * @ORM\Column(name="sales_start_period", type="datetime", nullable=true)
         */
        private $sales_start_period;

        /**
         * 販売期間(終了)
         * @var string
         * @ORM\Column(name="sales_end_period", type="datetime", nullable=true)
         */
        private $sales_end_period;

        /**
         * 紹介品区分
         * @var \Doctrine\Common\Collections\Collection
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\IntroduceGood", inversedBy="ProductClass")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="introduce_goods_id", referencedColumnName="id")
         * })
         */
        private $IntroduceGoods;

        /**
         * 新商品区分
         * @var \Doctrine\Common\Collections\Collection
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\NewProductCategory", inversedBy="ProductClass")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="new_product_id", referencedColumnName="id")
         * })
         */
        private $NewProductCategory;

        /**
         * 定期購入品区分
         * @var \Doctrine\Common\Collections\Collection
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\SubscriptionPurchase", inversedBy="ProductClass")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="subscription_purchase_id", referencedColumnName="id")
         * })
         */
        private $SubscriptionPurchase;

        /**
         * 通常価格
         * @var string|null
         *
         * @ORM\Column(name="price", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $price;

        /**
         * 会員区分別価格0
         * @var string
         *
         * @ORM\Column(name="member_price_0", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $member_price_0;

        /**
         * 会員区分別価格１
         * @var string
         *
         * @ORM\Column(name="member_price_1", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $member_price_1;

        /**
         * 会員区分別価格２
         * @var string
         *
         * @ORM\Column(name="member_price_2", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $member_price_2;

        /**
         * 会員区分別価格3
         * @var string
         *
         * @ORM\Column(name="member_price_3", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $member_price_3;

        /**
         * 割引期間(開始)
         * @var \DateTime
         * @ORM\Column(name="discount_start_period", type="datetime", nullable=true)
         */
        private $discount_start_period;

        /**
         * 割引期間(終了)
         * @var \DateTime
         * @ORM\Column(name="discount_end_period", type="datetime", nullable=true)
         */
        private $discount_end_period;

        /**
         * 割引期間価格
         * @var integer
         * @ORM\Column(name="discount_period_price", type="integer", nullable=true)
         */
        private $discount_period_price;

        /**
         * まとめ買いグループ
         * @var \Doctrine\Common\Collections\Collection
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\BulkBuying", inversedBy="ProductClass")
         * @ORM\JoinColumns({
         *  @ORM\JoinColumn(name="bulk_buying_group_id", referencedColumnName="id")
         * })
         */
        private $BulkBuying;

        /**
         * まとめ買い価格
         * @var int
         * @ORM\Column(name="bulk_buying_price", type="integer", nullable=true)
         */
        private $bulk_buying_price;


        /**
         * キャンペーン期間(開始)
         * @var \DateTime
         * @ORM\Column(name="campaign_start_period", type="datetime", nullable=true)
         */
        private $campaign_start_period;

        /**
         * キャンペーン期間(終了)
         * @var \DateTime
         * @ORM\Column(name="campaign_end_period", type="datetime", nullable=true)
         */
        private $campaign_end_period;

        /**
         * ポイント(%入力)
         * @var integer
         * @ORM\Column(name="point", type="integer", nullable=true)
         */
        private $point;

        /**
         * キャンペーンポイント(%入力)
         * @var integer
         * @ORM\Column(name="campaign_point", type="integer", nullable=true)
         */
        private $campaign_point;

        /**
         * 仕入額
         * @var integer
         * @ORM\Column(name="cost", type="integer", nullable=true)
         */
        private $cost;


        /**
         * 仕入期間(開始)
         * @var datetime
         * @ORM\Column(name="cost_start_period", type="datetime", nullable=true)
         */
        private $cost_start_period;

        /**
         * 仕入期間(終了)
         * @var datetime
         * @ORM\Column(name="cost_end_period", type="datetime", nullable=true)
         */
        private $cost_end_period;

        /**
         * 期間仕入額
         * @var integer
         * @ORM\Column(name="cost_period_price", type="integer", nullable=true)
         */
        private $cost_period_price;

        /**
         * 包材
         * @var integer
         * @ORM\Column(name="packing_material", type="integer", nullable=true)
         */
        private $packing_material;

        /**
         * 決済
         * @var integer
         * @ORM\Column(name="settlement", type="integer", nullable=true)
         */
        private $settlement;

        /**
         * 返済
         * @var integer
         * @ORM\Column(name="pay_back_debt", type="integer", nullable=true)
         */
        private $pay_back_debt;

        /**
         * ドライ
         * @var integer
         * @ORM\Column(name="dry", type="integer", nullable=true)
         */
        private $dry;

        /**
         * 保管料
         * @var integer
         * @ORM\Column(name="discount_fee", type="integer", nullable=true)
         */
        private $discount_fee;

        /**
         * 金利
         * @var integer
         * @ORM\Column(name="interest_rates", type="integer", nullable=true)
         */
        private $interest_rates;

        /**
         * マージン
         * @var integer
         * @ORM\Column(name="margin", type="integer", nullable=true)
         */
        private $margin;

        /**
         * 購入限定数
         * @var integer
         * @ORM\Column(name="purchase_limit", type="integer", nullable=true)
         */
        private $purchase_limit;

        /**
         * 購入最低数
         * @var integer
         * @ORM\Column(name="purchase_minimum", type="integer", nullable=true)
         */
        private $purchase_minimum;

        /**
         * 掲載開始日
         * @var \DateTime
         * @ORM\Column(name="insert_start_date", type="datetime", nullable=true)
         */
        private $insert_start_date;

        /**
         * 掲載終了日
         * @var \DateTime
         * @ORM\Column(name="insert_end_date", type="datetime", nullable=true)
         */
        private $insert_end_date;

        /**
         * 一覧画面フラグ
         * @var integer
         * @ORM\Column(name="is_list_page", type="boolean", nullable=true)
         */
        private $is_list_page;

        /**
         * 詳細画面フラグ
         * @var integer
         * @ORM\Column(name="is_detail_page", type="boolean", nullable=true)
         */
        private $is_detail_page;

        /**
         * 状態（あり/なし/ブランク）
         * @var integer
         * @ORM\Column(name="status", type="integer", nullable=true)
         */
        private $status;


        /**
         * 商品詳細コメント2
         * @var string
         * @ORM\Column(name="description_detail_2", type="string", nullable=true)
         */
        private $description_detail_2;

        /**
         *　アイコン1
         * @var \Customize\Entity\Product\ProductIcon
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductIcon", inversedBy="ProductClass")
         * @ORM\JoinColumn(name="product_icon_id_1", referencedColumnName="id", nullable=true)
         */
        private $ProductIcon1;

        /**
         * アイコン2
         * @var \Customize\Entity\Product\ProductIcon
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductIcon", inversedBy="ProductClass")
         * @ORM\JoinColumn(name="product_icon_id_2", referencedColumnName="id", nullable=true)
         */
        private $ProductIcon2;

        /**
         * アイコン3
         * @var \Customize\Entity\Product\ProductIcon
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductIcon", inversedBy="ProductClass")
         * @ORM\JoinColumn(name="product_icon_id_3", referencedColumnName="id", nullable=true)
         */
        private $ProductIcon3;

        /**
         * かご投入時メッセージ
         * @var string
         *
         * @ORM\Column(name="cart_insert_message", type="string", nullable=true)
         */
        private $cart_insert_message;

        /**
         * キーワード
         * @var string
         * @ORM\Column(name="keyword", type="string", nullable=true)
         */
        private $keyword;

        /**
         * 在庫数
         * @var integer
         *
         * @ORM\Column(name="stock", type="integer", nullable=true)
         */
        private $stock;

        /**
         * 在庫扱いの種別
         * @var string
         * @ORM\Column(name="stock_type", type="string", nullable=true)
         */
        private $stock_type;

        /**
         * 在庫通常コメント
         * @var string
         * @ORM\Column(name="normal_stock_comment", type="string", nullable=true)
         */
        private $normal_stock_comment;

        /**
         * 在庫少量時コメント
         * @var string
         * @ORM\Column(name="low_stock_comment", type="string", nullable=true)
         */
        private $low_stock_comment;

        /**
         * 在庫切れコメント
         * @var string
         * @ORM\Column(name="out_of_stock_comment", type="string", nullable=true)
         */
        private $out_of_stock_comment;

        /**
         * コメントしきい値
         * @var string
         * @ORM\Column(name="comment_threshold", type="string", nullable=true)
         */
        private $comment_threshold;


        /**
         * 実数表示フラグ
         * @var boolean
         * @ORM\Column(name="is_real_indicator", type="boolean", nullable=true)
         */
        private $is_real_indicator;


        /**
         * 登録日
         * @var \DateTime
         *
         * @ORM\Column(name="create_date", type="datetime")
         */
        private $create_date;

        /**
         * 更新日
         * @var \DateTime
         *
         * @ORM\Column(name="update_date", type="datetime")
         */
        private $update_date;

        /**
         *
         * @ORM\Column(name="price01", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $price01;

        /**
         * @var string
         *
         * @ORM\Column(name="price02", type="decimal", precision=12, scale=2, nullable=true)
         */
        private $price02;

        /**
         * 更新者
         * @var \Eccube\Entity\Member
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
         * })
         */
        private $Creator;
        // !===============================================

        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="product_code", type="string", length=255, nullable=true)
        //  */
        // private $code;

        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="stock", type="decimal", precision=10, scale=0, nullable=true)
        //  */
        // private $stock;

        // /**
        //  * @var boolean
        //  *
        //  * @ORM\Column(name="stock_unlimited", type="boolean", options={"default":false})
        //  */
        // private $stock_unlimited = false;

        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="sale_limit", type="decimal", precision=10, scale=0, nullable=true, options={"unsigned":true})
        //  */
        // private $sale_limit;



        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="delivery_fee", type="decimal", precision=12, scale=2, nullable=true, options={"unsigned":true})
        //  */
        // private $delivery_fee;

        // /**
        //  * @var boolean
        //  *
        //  * @ORM\Column(name="visible", type="boolean", options={"default":true}, nullable=true)
        //  */
        // private $visible;
        // /**
        //  * @var string|null
        //  *
        //  * @ORM\Column(name="currency_code", type="string", nullable=true)
        //  */
        // private $currency_code;

        // /**
        //  * @var string
        //  *
        //  * @ORM\Column(name="point_rate", type="decimal", precision=10, scale=0, options={"unsigned":true}, nullable=true)
        //  */
        // private $point_rate;

        // /**
        //  * @var \Eccube\Entity\ProductStock
        //  *
        //  * @ORM\OneToOne(targetEntity="Eccube\Entity\ProductStock", mappedBy="ProductClass", cascade={"persist","remove"})
        //  */
        // private $ProductStock;

        // /**
        //  * @var \Eccube\Entity\TaxRule
        //  *
        //  * @ORM\OneToOne(targetEntity="Eccube\Entity\TaxRule", mappedBy="ProductClass", cascade={"persist","remove"})
        //  */
        // private $TaxRule;

        // /**
        //  * @var \Eccube\Entity\Product
        //  *
        //  * @ORM\ManyToOne(targetEntity="Eccube\Entity\Product", inversedBy="ProductClasses")
        //  * @ORM\JoinColumns({
        //  *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
        //  * })
        //  */
        // private $Product;

        // /**
        //  * @var \Eccube\Entity\Master\SaleType
        //  *
        //  * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\SaleType")
        //  * @ORM\JoinColumns({
        //  *   @ORM\JoinColumn(name="sale_type_id", referencedColumnName="id")
        //  * })
        //  */
        // private $SaleType;

         /**
          * @var \Eccube\Entity\ClassCategory
          *
          * @ORM\ManyToOne(targetEntity="Eccube\Entity\ClassCategory")
          * @ORM\JoinColumns({
          *   @ORM\JoinColumn(name="class_category_id1", referencedColumnName="id", nullable=true)
          * })
          */
         private $ClassCategory1;

         /**
          * @var \Eccube\Entity\ClassCategory
          *
          * @ORM\ManyToOne(targetEntity="Eccube\Entity\ClassCategory")
          * @ORM\JoinColumns({
          *   @ORM\JoinColumn(name="class_category_id2", referencedColumnName="id", nullable=true)
          * })
          */
         private $ClassCategory2;

        // /**
        //  * @var \Eccube\Entity\DeliveryDuration
        //  *
        //  * @ORM\ManyToOne(targetEntity="Eccube\Entity\DeliveryDuration")
        //  * @ORM\JoinColumns({
        //  *   @ORM\JoinColumn(name="delivery_duration_id", referencedColumnName="id")
        //  * })
        //  */
        // private $DeliveryDuration;
        public function __clone()
        {
            $this->id = null;
        }

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->ProductClassProductIcon = new \Doctrine\Common\Collections\ArrayCollection();
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
         * set Product
         * @param Product $Product
         * @return $this
         */
        public function setProduct(Product $Product)
        {
            $this->Product = $Product;

            return $this;
        }

        /**
         * Get Product.
         *
         * @return Product
         */
        public function getProduct()
        {
            return $this->Product;
        }

        /**
         * Set pamphlet_code.
         *
         * @param string|null $pamphlet_code
         *
         * @return ProductClass
         */
        public function setPamphletCode($pamphlet_code = null)
        {
            $this->pamphlet_code = $pamphlet_code;

            return $this;
        }

        /**
         * Get pamphlet_code.
         *
         * @return string|null
         */
        public function getPamphletCode()
        {
            return $this->pamphlet_code;
        }

        /**
         * Set sales_start_period.
         *
         * @param DatetimeInterFace|null $sales_period
         *
         * @return ProductClass
         */
        public function setSalesStartPeriod(?\DateTimeInterface $sales_period): self
        {
            $this->sales_start_period = $sales_period;

            return $this;
        }

        /**
         * Get sales_start_period.
         *
         * @return DatetimeInterFace|null
         */
        public function getSalesStartPeriod()
        {
            return $this->sales_start_period;
        }

        /**
         * Set sales_end_period.
         *
         * @param DatetimeInterFace|null $sales_period
         *
         * @return ProductClass
         */
        public function setSalesEndPeriod(?\DateTimeInterface $sales_period): self
        {
            $this->sales_end_period = $sales_period;

            return $this;
        }

        /**
         * Get sales_end_period.
         *
         * @return DatetimeInterFace|null
         */
        public function getSalesEndPeriod()
        {
            return $this->sales_end_period;
        }

        /**
         * Set IntroduceGoods.
         *
         * @param \Customize\Entity\Master\IntroduceGood|null $introduce_good
         *
         * @return ProductClass
         */
        public function setIntroduceGood($introduce_good = null)
        {
            $this->IntroduceGoods = $introduce_good;

            return $this;
        }

        /**
         * Get IntroduceGoods.
         *
         * @return \Customize\Entity\Master\IntroduceGood|null
         */
        public function getIntroduceGood()
        {
            return $this->IntroduceGoods;
        }

        /**
         * Set NewProductCategory.
         *
         * @param \Customize\Entity\Master\NewProductCategory|null $new_product_category
         *
         * @return ProductClass
         */
        public function setNewProductCategory($new_product_category = null)
        {
            $this->NewProductCategory = $new_product_category;

            return $this;
        }

        /**
         * Get NewProductCategory.
         *
         * @return \Customize\Entity\Master\NewProductCategory|null
         */
        public function getNewProductCategory()
        {
            return $this->NewProductCategory;
        }

        /**
         * Set SubscriptionPurchase.
         *
         * @param \Customize\Entity\Master\SubscriptionPurchase|null $subscription_purchase
         *
         * @return ProductClass
         */
        public function setSubscriptionPurchase($subscription_purchase = null)
        {
            $this->SubscriptionPurchase = $subscription_purchase;

            return $this;
        }

        /**
         * Get SubscriptionPurchase.
         *
         * @return \Customize\Entity\Master\SubscriptionPurchase|null
         */
        public function getSubscriptionPurchase()
        {
            return $this->SubscriptionPurchase;
        }

        /**
         * Set price.
         *
         * @param string|null $price
         *
         * @return ProductClass
         */
        public function setPrice($price = null)
        {
            $this->price = $price;

            return $this;
        }

        /**
         * Get price.
         *
         * @return string|null
         */
        public function getPrice()
        {
            return $this->price;
        }

        /**
         * Set member_price_0.
         *
         * @param string $member_price_0
         *
         * @return ProductClass
         */
        public function setMemberPrice0($member_price_0)
        {
            $this->member_price_0 = $member_price_0;

            return $this;
        }

        /**
         * Get member_price_0.
         *
         * @return string
         */
        public function getMemberPrice0()
        {
            return $this->member_price_0;
        }

        /**
         * Set member_price_1.
         *
         * @param string $member_price_1
         *
         * @return ProductClass
         */
        public function setMemberPrice1($member_price_1)
        {
            $this->member_price_1 = $member_price_1;

            return $this;
        }

        /**
         * Get member_price_1.
         *
         * @return string
         */
        public function getMemberPrice1()
        {
            return $this->member_price_1;
        }

        /**
         * Set member_price_2.
         *
         * @param string $member_price_2
         *
         * @return ProductClass
         */
        public function setMemberPrice2($member_price_2)
        {
            $this->member_price_2 = $member_price_2;

            return $this;
        }

        /**
         * Get member_price_2.
         *
         * @return string
         */
        public function getMemberPrice2()
        {
            return $this->member_price_2;
        }

        /**
         * Set member_price_3.
         *
         * @param string $member_price_3
         *
         * @return ProductClass
         */
        public function setMemberPrice3($member_price_3)
        {
            $this->member_price_3 = $member_price_3;

            return $this;
        }

        /**
         * Get member_price_3.
         *
         * @return string
         */
        public function getMemberPrice3()
        {
            return $this->member_price_3;
        }

        /**
         * Set discount_start_period.
         *
         * @param DatetimeInterFace|null $discount_period
         *
         * @return ProductClass
         */
        public function setDiscountStartPeriod(?\DateTimeInterface $discount_period): self
        {
            $this->discount_start_period = $discount_period;

            return $this;
        }

        /**
         * Get discount_start_period.
         *
         * @return DatetimeInterFace|null
         */
        public function getDiscountStartPeriod(): ?\DateTimeInterface
        {
            return $this->discount_start_period;
        }

        /**
         * Set discount_end_period.
         *
         * @param DatetimeInterFace|null $discount_period
         *
         * @return ProductClass
         */
        public function setDiscountEndPeriod(?\DateTimeInterface $discount_period): self
        {
            $this->discount_end_period = $discount_period;

            return $this;
        }

        /**
         * Get discount_end_period.
         *
         * @return DatetimeInterFace|null
         */
        public function getDiscountEndPeriod(): ?\DateTimeInterface
        {
            return $this->discount_end_period;
        }

        /**
         * Set discount_period_price.
         *
         * @param int|null $discount_period_price
         *
         * @return ProductClass
         */
        public function getDiscountPeriodPrice(): ?int
        {
            return $this->discount_period_price;
        }

        /**
         * Get discount_period_price.
         *
         * @return int|null
         */
        public function setDiscountPeriodPrice(?int $discount_period_price): self
        {
            $this->discount_period_price = $discount_period_price;

            return $this;
        }

        /**
         * Set BulkBuying.
         *
         * @param \Customize\Entity\Master\BulkBuying|null $bulk_buying
         *
         * @return Product
         */
        public function setBulkBuying($bulk_buying = null)
        {
            $this->BulkBuying = $bulk_buying;

            return $this;
        }

        /**
         * Get BulkBuying.
         *
         * @return \Customize\Entity\Master\BulkBuying|null
         */
        public function getBulkBuying()
        {
            return $this->BulkBuying;
        }

        /**
         * Set bulk_buying_price.
         *
         * @param int|null $bulk_buying_price
         *
         * @return ProductClass
         */
        public function setBulkBuyingPrice($bulk_buying_price = null)
        {
            $this->bulk_buying_price = $bulk_buying_price;

            return $this;
        }

        /**
         * Get bulk_buying_price.
         *
         * @return int|null
         */
        public function getBulkBuyingPrice()
        {
            return $this->bulk_buying_price;
        }

        /**
         * Set campaign_start_period.
         *
         * @param DateTimeInterface|null $campaign_start_period
         *
         * @return ProductClass
         */
        public function setCampaignStartPeriod(?\DateTimeInterface $campaign_start_period): self
        {
            $this->campaign_start_period = $campaign_start_period;

            return $this;
        }

        /**
         * Get campaign_start_period.
         *
         * @return DateTimeInterface|null
         */
        public function getCampaignStartPeriod(): ?\DateTimeInterface
        {
            return $this->campaign_start_period;
        }

        /**
         * Set campaign_end_period.
         *
         * @param DateTimeInterface|null $campaign_end_period
         *
         * @return ProductClass
         */
        public function setCampaignEndPeriod(?\DateTimeInterface $campaign_end_period): self
        {
            $this->campaign_end_period = $campaign_end_period;

            return $this;
        }

        /**
         * Get campaign_end_period.
         *
         * @return DateTimeInterface|null
         */
        public function getCampaignEndPeriod(): ?\DateTimeInterface
        {
            return $this->campaign_end_period;
        }

        /**
         * Set point
         *
         * @param int|null $point
         *
         * @return ProductClass
         */
        public function setPoint(?int $point): self
        {
            $this->point = $point;

            return $this;
        }

        /**
         * Get point
         *
         * @return int|null
         */
        public function getPoint(): ?int
        {
            return $this->point;
        }

        /**
         * Set campaign_point
         *
         * @param int|null $campaign_point
         *
         * @return ProductClass
         */
        public function setCampaignPoint(?int $campaign_point): self
        {
            $this->campaign_point = $campaign_point;

            return $this;
        }

        /**
         * Get campaign_point
         *
         * @return int|null
         */
        public function getCampaignPoint(): ?int
        {
            return $this->campaign_point;
        }

        /**
         * Set cost
         *
         * @param int|null $cost
         *
         * @return ProductClass
         */
        public function setCost(?int $cost): self
        {
            $this->cost = $cost;

            return $this;
        }

        /**
         * Get cost
         *
         * @return int|null
         */
        public function getCost(): ?int
        {
            return $this->cost;
        }

        /**
         * Set cost_start_period
         *
         * @param \DateTimeInterface|null $cost_start_period
         *
         * @return ProductClass
         */
        public function setCostStartPeriod(?\DateTimeInterface $cost_start_period): self
        {
            $this->cost_start_period = $cost_start_period;

            return $this;
        }

        /**
         * Get cost_start_period
         *
         * @return \DateTimeInterface|null
         */
        public function getCostStartPeriod()
        {
            return $this->cost_start_period;
        }

        /**
         * Set cost_end_period
         *
         * @param \DateTimeInterface|null $cost_end_period
         *
         * @return ProductClass
         */
        public function setCostEndPeriod(?\DateTimeInterface $cost_end_period): self
        {
            $this->cost_end_period = $cost_end_period;

            return $this;
        }

        /**
         * Get cost_end_period
         *
         * @return \DateTimeInterface|null
         */
        public function getCostEndPeriod()
        {
            return $this->cost_end_period;
        }

        /**
         * Set cost_period_price
         *
         * @param int|null $cost_period_price
         *
         * @return ProductClass
         */
        public function setCostPeriodPrice(?int $cost_period_price): self
        {
            $this->cost_period_price = $cost_period_price;

            return $this;
        }

        /**
         * Get cost_period_price
         *
         * @return int|null
         */
        public function getCostPeriodPrice(): ?int
        {
            return $this->cost_period_price;
        }

        /**
         * Set packing_material
         *
         * @param int|null $packing_material
         *
         * @return ProductClass
         */
        public function setPackingMaterial(?int $packing_material): self
        {
            $this->packing_material = $packing_material;

            return $this;
        }

        /**
         * Get packing_material
         *
         * @return int|null
         */
        public function getPackingMaterial(): ?int
        {
            return $this->packing_material;
        }

        /**
         * Set settlement
         *
         * @param int|null $settlement
         *
         * @return ProductClass
         */
        public function setSettlement(?int $settlement): self
        {
            $this->settlement = $settlement;

            return $this;
        }

        /**
         * Get settlement
         *
         * @return int|null
         */
        public function getSettlement(): ?int
        {
            return $this->settlement;
        }

        /**
         * set pay_back_debt
         *
         * @param int|null $pay_back_debt
         *
         * @return ProductClass
         */
        public function setPayBackDebt(?int $pay_back_debt): self
        {
            $this->pay_back_debt = $pay_back_debt;
            return $this;
        }

        /**
         * get pay_back_debt
         *
         * @return int|null
         */
        public function getPayBackDebt(): ?int
        {
            return $this->pay_back_debt;
        }

        /**
         * set dry
         *
         * @param int|null $dry
         *
         *  @return ProductClass
         */
        public function setDry(?int $dry): self
        {
            $this->dry = $dry;
            return $this;
        }

        /**
         * get dry
         *
         * @return int|null
         */
        public function getDry(): ?int
        {
            return $this->dry;
        }

        /**
         * set discount_fee
         *
         * @param int|null $discount_fee
         *
         *  @return ProductClass
         */
        public function setDiscountFee(?int $discount_fee): self
        {
            $this->discount_fee = $discount_fee;
            return $this;
        }

        /**
         * get discount_fee
         *
         * @return int|null
         */
        public function getDiscountFee(): ?int
        {
            return $this->discount_fee;
        }

        /**
         * set interest_rates
         *
         * @param int|null $interest_rates
         *
         * @return ProductClass
         */
        public function setInterestRates(?int $interest_rates): self
        {
            $this->interest_rates = $interest_rates;
            return $this;
        }

        /**
         * get interest_rates
         *
         * @return int|null
         */
        public function getInterestRates(): ?int
        {
            return $this->interest_rates;
        }

        /**
         * set margin
         *
         * @param int|null $margin
         *
         * @return ProductClass
         */
        public function setMargin(?int $margin): self
        {
            $this->margin = $margin;
            return $this;
        }

        /**
         * get margin
         *
         * @return int|null
         */
        public function getMargin(): ?int
        {
            return $this->margin;
        }

        /**
         * set purchase_limit
         *
         * @param int|null $purchase_limit
         *
         * @return ProductClass
         */
        public function setPurchaseLimit(?int $purchase_limit): self
        {
            $this->purchase_limit = $purchase_limit;
            return $this;
        }

        /**
         * get purchase_limit
         *
         * @return int|null
         */
        public function getPurchaseLimit(): ?int
        {
            return $this->purchase_limit;
        }

        /**
         * set purchase_minimum
         *
         * @param int|null $purchase_minimum
         *
         * @return ProductClass
         */
        public function setPurchaseMinimum(?int $purchase_minimum): self
        {
            $this->purchase_minimum = $purchase_minimum;
            return $this;
        }

        /**
         * get purchase_minimum
         *
         * @return int|null
         */
        public function getPurchaseMinimum(): ?int
        {
            return $this->purchase_minimum;
        }

        /**
         * set purchase_maximum
         *
         * @param int|null $purchase_maximum
         *
         * @return ProductClass
         */
        public function setInsertStartDate(?\DateTimeInterface $insert_start_date): self
        {
            $this->insert_start_date = $insert_start_date;
            return $this;
        }

        /**
         * get purchase_maximum
         *
         * @return int|null
         */
        public function getInsertStartDate(): ?\DateTimeInterface
        {
            return $this->insert_start_date;
        }

        /**
         * set purchase_maximum
         *
         * @param int|null $purchase_maximum
         *
         * @return ProductClass
         */
        public function setInsertEndDate(?\DateTimeInterface $insert_end_date): self
        {
            $this->insert_end_date = $insert_end_date;
            return $this;
        }

        /**
         * get purchase_maximum
         *
         * @return int|null
         */
        public function getInsertEndDate(): ?\DateTimeInterface
        {
            return $this->insert_end_date;
        }

        /**
         * set is_list_page
         *
         * @param int|null $is_list_page
         *
         * @return ProductClass
         */
        public function setIsListPage(?int $is_list_page): self
        {
            $this->is_list_page = $is_list_page;
            return $this;
        }

        /**
         * get is_list_page
         *
         * @return int|null
         */
        public function getIsListPage(): ?int
        {
            return $this->is_list_page;
        }

        /**
         * set is_detail_page
         *
         * @param int|null $is_detail_page
         *
         * @return ProductClass
         */
        public function setIsDetailPage(?int $is_detail_page): self
        {
            $this->is_detail_page = $is_detail_page;
            return $this;
        }

        /**
         * get is_detail_page
         *
         * @return int|null
         */
        public function getIsDetailPage(): ?int
        {
            return $this->is_detail_page;
        }

        /**
         * set status
         *
         * @param int|null $status
         *
         * @return ProductClass
         */
        public function setStatus(?int $status): self
        {
            $this->status = $status;
            return $this;
        }

        /**
         * get status
         *
         * @return int|null
         */
        public function getStatus(): ?int
        {
            return $this->status;
        }

        /**
         * set description_detail_2
         *
         * @param string|null $description_detail_2
         *
         * @return ProductClass
         */
        public function setDescriptionDetail2(?string $description_detail_2): self
        {
            $this->description_detail_2 = $description_detail_2;
            return $this;
        }

        /**
         * get description_detail_2
         *
         * @return string|null
         */
        public function getDescriptionDetail2(): ?string
        {
            return $this->description_detail_2;
        }

        /**
         * Set ProductIcon1.
         *
         * @param \Customize\Entity\Product\ProductIcon|null $ProductIcon1
         *
         * @return ProductClass
         */
        public function setProductIcon1(ProductIcon $ProductIcon1 = null)
        {
            $this->ProductIcon1 = $ProductIcon1;

            return $this;
        }

        /**
         * Get ProductIcon1.
         *
         * @return \Customize\Entity\Product\ProductIcon|null
         */
        public function getProductIcon1()
        {
            return $this->ProductIcon1;
        }

        /**
         * Set ProductIcon2.
         *
         * @param \Customize\Entity\Product\ProductIcon|null $ProductIcon2
         *
         * @return ProductClass
         */
        public function setProductIcon2(ProductIcon $ProductIcon2 = null)
        {
            $this->ProductIcon2 = $ProductIcon2;

            return $this;
        }

        /**
         * Get ProductIcon2.
         *
         * @return \Customize\Entity\Product\ProductIcon|null
         */
        public function getProductIcon2()
        {
            return $this->ProductIcon2;
        }

        /**
         * Set ProductIcon3.
         *
         * @param \Customize\Entity\Product\ProductIcon|null $ProductIcon3
         *
         * @return ProductClass
         */
        public function setProductIcon3(ProductIcon $ProductIcon3 = null)
        {
            $this->ProductIcon3 = $ProductIcon3;

            return $this;
        }

        /**
         * Get ProductIcon3.
         *
         * @return \Customize\Entity\Product\ProductIcon|null
         */
        public function getProductIcon3()
        {
            return $this->ProductIcon3;
        }

        /**
         * Set cart_insert_message
         *
         * @param string|null $cart_insert_message
         *
         * @return ProductClass
         */
        public function setCartInsertMessage(?string $cart_insert_message): self
        {
            $this->cart_insert_message = $cart_insert_message;
            return $this;
        }

        /**
         * get cart_insert_message
         *
         * @return string|null
         */
        public function getCartInsertMessage(): ?string
        {
            return $this->cart_insert_message;
        }

        /**
         * set keyword
         *
         * @param string|null $keyword
         *
         * @return ProductClass
         */
        public function setKeyword(?string $keyword): self
        {
            $this->keyword = $keyword;
            return $this;
        }

        /**
         * get keyword
         *
         * @return string|null
         */
        public function getKeyword(): ?string
        {
            return $this->keyword;
        }

        /**
         * set stock
         * @param string|null $stock
         * @return ProductClass
         */
        public function setStock(?string $stock): self
        {
            $this->stock = $stock;
            return $this;
        }

        /**
         * get stock
         * @return string|null
         */
        public function getStock(): ?string
        {
            return $this->stock;
        }

        /**
         * Set stock_type.
         *
         * @param $stock_type
         * @return ProductClass
         */
        public function setStockType($value)
        {
            $this->stock_type = $value;

            return $this;
        }

        /**
         * Get stock_type.
         *
         * @return string|null
         */
        public function getStockType()
        {
            return $this->stock_type;
        }

        /**
         * Set normal_stock_comment.
         *
         * @param $normal_stock_comment
         * @return
         */
        public function setNormalStockComment($value)
        {
            $this->normal_stock_comment = $value;

            return $this;
        }

        /**
         * Get normal_stock_comment.
         *
         * @return string|null
         */
        public function getNormalStockComment()
        {
            return $this->normal_stock_comment;
        }

        /**
         * Set low_stock_comment.
         *
         * @param $low_stock_comment
         * @return
         */
        public function setLowStockComment($value)
        {
            $this->low_stock_comment = $value;

            return $this;
        }

        /**
         * Get low_stock_comment.
         *
         * @return string|null
         */
        public function getLowStockComment()
        {
            return $this->low_stock_comment;
        }

        /**
         * Set out_of_stock_comment.
         *
         * @param $out_of_stock_comment
         * @return
         */
        public function setOutOfStockComment($value)
        {
            $this->out_of_stock_comment = $value;

            return $this;
        }

        /**
         * Get out_of_stock_comment.
         *
         * @return string|null
         */
        public function getOutOfStockComment()
        {
            return $this->out_of_stock_comment;
        }


        /**
         * Set comment_threshold.
         *
         * @param $comment_threshold
         * @return
         */
        public function setCommentThreshold($value)
        {
            $this->comment_threshold = $value;

            return $this;
        }

        /**
         * Get comment_threshold.
         *
         * @return integer|null
         */
        public function getCommentThreshold()
        {
            return $this->comment_threshold;
        }

        /**
         * Set is_real_indicator.
         *
         * @param $is_real_indicator
         * @return
         */
        public function setIsRealIndicator($value)
        {
            $this->is_real_indicator = $value;

            return $this;
        }

        /**
         * Get is_real_indicator.
         *
         * @return boolean|null
         */
        public function getIsRealIndicator()
        {
            return $this->is_real_indicator;
        }


        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return ProductClass
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
         * @return ProductClass
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
         * Set creator.
         *
         * @param \Eccube\Entity\Member|null $creator
         *
         * @return ProductClass
         */
        public function setCreator(Member $creator = null)
        {
            $this->Creator = $creator;

            return $this;
        }

        /**
         * Get creator.
         *
         * @return \Eccube\Entity\Member|null
         */
        public function getCreator()
        {
            return $this->Creator;
        }

        /**
         * Get price01.
         *
         * @return string|null
         */
        public function getPrice01()
        {
            return $this->price01;
        }

        // !=============================

        // /**
        //  * Set code.
        //  *
        //  * @param string|null $code
        //  *
        //  * @return ProductClass
        //  */
        // public function setCode($code = null)
        // {
        //     $this->code = $code;

        //     return $this;
        // }

        // /**
        //  * Set stock.
        //  *
        //  * @param string|null $stock
        //  *
        //  * @return ProductClass
        //  */
        // public function setStock($stock = null)
        // {
        //     $this->stock = $stock;

        //     return $this;
        // }

        // /**
        //  * Get stock.
        //  *
        //  * @return string|null
        //  */
        // public function getStock()
        // {
        //     return $this->stock;
        // }

        // /**
        //  * Set stockUnlimited.
        //  *
        //  * @param boolean $stockUnlimited
        //  *
        //  * @return ProductClass
        //  */
        // public function setStockUnlimited($stockUnlimited)
        // {
        //     $this->stock_unlimited = $stockUnlimited;

        //     return $this;
        // }

        // /**
        //  * Get stockUnlimited.
        //  *
        //  * @return boolean
        //  */
        // public function isStockUnlimited()
        // {
        //     return $this->stock_unlimited;
        // }

        // /**

        // /**
        //  * Set deliveryFee.
        //  *
        //  * @param string|null $deliveryFee
        //  *
        //  * @return ProductClass
        //  */
        // public function setDeliveryFee($deliveryFee = null)
        // {
        //     $this->delivery_fee = $deliveryFee;

        //     return $this;
        // }

        // /**
        //  * Get deliveryFee.
        //  *
        //  * @return string|null
        //  */
        // public function getDeliveryFee()
        // {
        //     return $this->delivery_fee;
        // }

        // /**
        //  * @return boolean
        //  */
        // public function isVisible()
        // {
        //     return $this->visible;
        // }

        // /**
        //  * @param boolean $visible
        //  *
        //  * @return ProductClass
        //  */
        // public function setVisible($visible)
        // {
        //     $this->visible = $visible;

        //     return $this;
        // }

        // /**
        //  * Get currencyCode.
        //  *
        //  * @return string
        //  */
        // public function getCurrencyCode()
        // {
        //     return $this->currency_code;
        // }

        // /**
        //  * Set currencyCode.
        //  *
        //  * @param string|null $currencyCode
        //  *
        //  * @return $this
        //  */
        // public function setCurrencyCode($currencyCode = null)
        // {
        //     $this->currency_code = $currencyCode;

        //     return $this;
        // }

        // /**
        //  * Set productStock.
        //  *
        //  * @param \Eccube\Entity\ProductStock|null $productStock
        //  *
        //  * @return ProductClass
        //  */
        // public function setProductStock(ProductStock $productStock = null)
        // {
        //     $this->ProductStock = $productStock;

        //     return $this;
        // }

        // /**
        //  * Get productStock.
        //  *
        //  * @return \Eccube\Entity\ProductStock|null
        //  */
        // public function getProductStock()
        // {
        //     return $this->ProductStock;
        // }

        // /**
        //  * Set taxRule.
        //  *
        //  * @param \Eccube\Entity\TaxRule|null $taxRule
        //  *
        //  * @return ProductClass
        //  */
        // public function setTaxRule(TaxRule $taxRule = null)
        // {
        //     $this->TaxRule = $taxRule;

        //     return $this;
        // }

        // /**
        //  * Get taxRule.
        //  *
        //  * @return \Eccube\Entity\TaxRule|null
        //  */
        // public function getTaxRule()
        // {
        //     return $this->TaxRule;
        // }

        // /**
        //  * Set product.
        //  *
        //  * @param \Eccube\Entity\Product|null $product
        //  *
        //  * @return ProductClass
        //  */
        // public function setProduct(Product $product = null)
        // {
        //     $this->Product = $product;

        //     return $this;
        // }

        // /**
        //  * Get product.
        //  *
        //  * @return \Eccube\Entity\Product|null
        //  */
        // public function getProduct()
        // {
        //     return $this->Product;
        // }

        // /**
        //  * Set saleType.
        //  *
        //  * @param \Eccube\Entity\Master\SaleType|null $saleType
        //  *
        //  * @return ProductClass
        //  */
        // public function setSaleType(Master\SaleType $saleType = null)
        // {
        //     $this->SaleType = $saleType;

        //     return $this;
        // }

        // /**
        //  * Get saleType.
        //  *
        //  * @return \Eccube\Entity\Master\SaleType|null
        //  */
        // public function getSaleType()
        // {
        //     return $this->SaleType;
        // }

         /**
          * Set classCategory1.
          *
          * @param \Eccube\Entity\ClassCategory|null $classCategory1
          *
          * @return ProductClass
          */
         public function setClassCategory1(ClassCategory $classCategory1 = null)
         {
             $this->ClassCategory1 = $classCategory1;

             return $this;
         }

         /**
          * Get classCategory1.
          *
          * @return \Eccube\Entity\ClassCategory|null
          */
         public function getClassCategory1()
         {
             return $this->ClassCategory1;
         }

         /**
          * Set classCategory2.
          *
          * @param \Eccube\Entity\ClassCategory|null $classCategory2
          *
          * @return ProductClass
          */
         public function setClassCategory2(ClassCategory $classCategory2 = null)
         {
             $this->ClassCategory2 = $classCategory2;

             return $this;
         }

         /**
          * Get classCategory2.
          *
          * @return \Eccube\Entity\ClassCategory|null
          */
         public function getClassCategory2()
         {
             return $this->ClassCategory2;
         }

        // /**
        //  * Set deliveryDuration.
        //  *
        //  * @param \Eccube\Entity\DeliveryDuration|null $deliveryDuration
        //  *
        //  * @return ProductClass
        //  */
        // public function setDeliveryDuration(DeliveryDuration $deliveryDuration = null)
        // {
        //     $this->DeliveryDuration = $deliveryDuration;

        //     return $this;
        // }

        // /**
        //  * Get deliveryDuration.
        //  *
        //  * @return \Eccube\Entity\DeliveryDuration|null
        //  */
        // public function getDeliveryDuration()
        // {
        //     return $this->DeliveryDuration;
        // }

        // /**
        //  * Set pointRate
        //  *
        //  * @param string $pointRate
        //  *
        //  * @return ProductClass
        //  */
        // public function setPointRate($pointRate)
        // {
        //     $this->point_rate = $pointRate;

        //     return $this;
        // }

        // /**
        //  * Get pointRate
        //  *
        //  * @return string
        //  */
        // public function getPointRate()
        // {
        //     return $this->point_rate;
        // }
    }
