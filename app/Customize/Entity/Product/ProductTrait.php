<?php

namespace Customize\Entity\Product;

#DBにアクセスするためのライブラリなどを読み込み
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Eccube\Entity\Category;
use Eccube\Entity\Product;
use Eccube\Entity\ProductClass;

#拡張をする対象エンティティの指定
/**
 * @Eccube\EntityExtension("Eccube\Entity\Product")
 */


trait ProductTrait //ファイル名と合わせる
{
    //TODO productSpecificationとproductのリレーションを作成する　その後productControllerで$formに['ProductSpecification']を追加する
    /**
     * @var string
     * @ORM\Column(name="product_code_id", type="string", precision=12, nullable=true)
     */
    private $product_code_id;

    /**
     * @var string
     * @ORM\Column(name="part_number", type="string", nullable=true)
     */
    private $part_number;

    /**
     * @var string
     * @ORM\Column(name="sub_name", type="string", nullable=true)
     */
    private $sub_name;

    /**
     * @var \Eccube\Entity\Master\PurchasingGroup
     *
     * @ORM\OneToOne(targetEntity="\Eccube\Entity\Master\PurchasingGroup")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="purchasing_group_id", referencedColumnName="id")
     * })
     */
    private $PurchasingGroup;

    /**
     * @var string
     * @ORM\Column(name="variation_group", type="string", nullable=true)
     */
    private $variation_group;

    /**
     * @var string
     * @ORM\Column(name="variation_priority", type="string", nullable=true)
     */
    private $variation_priority;

    /**
     * @var string
     * @ORM\Column(name="size", type="string", nullable=true)
     */
    private $size;

    /**
     * @var string
     * @ORM\Column(name="color", type="string", nullable=true)
     */
    private $color;

//    /**
//     * @var \Customize\Entity\Product\ProductBrand
//     *
//     * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductBrand")
//     * @ORM\JoinColumns({
//     *   @ORM\JoinColumn(name="brand_id", referencedColumnName="id")
//     * })
//     */
//    private $ProductBrand;

    /**
     * @var \Customize\Entity\Product\ProductTopic
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductTopic")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="topic_id", referencedColumnName="id")
     * })
     */
    private $ProductTopic;

    /**
     * @var \Customize\Entity\Product\ProductMaker
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductMaker")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="maker_id", referencedColumnName="id")
     * })
     */
    private $ProductMaker;

    /**
     * @var Category
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Category")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     * })
     */
    private $Category;

    /**
     * @var \Customize\Entity\Product\ProductSupplier
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductSupplier", inversedBy="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
     * })
     */
    private $ProductSupplier;



    /**
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\BulkBuying", inversedBy="Product")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="bulk_buying_id", referencedColumnName="id")
     * })
     */
    private $BulkBuying;

    /**
     * @var string
     * @ORM\Column(name="detail_url", type="text", nullable=true)
     */
    private $detail_url;

    /**
     * @var string
     * @ORM\Column(name="one_word_comment", type="text", nullable=true)
     */
    private $one_word_comment;

    /**
     * @var \Customize\Entity\Master\TemperatureRange
     *
     * @ORM\OneToOne(targetEntity="Customize\Entity\Master\TemperatureRange")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="temperature_range_id", referencedColumnName="id")
     * })
     */
    private $TemperatureRange;

    /**
     * @var \Customize\Entity\Master\RegularPurchaseCategory
     * @ORM\OneToOne(targetEntity="Customize\Entity\Master\RegularPurchaseCategory")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="regular_purchase_category_id", referencedColumnName="id")
     * })
     */
    private $RegularPurchaseCategoryId;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\OneToMany(targetEntity="Customize\Entity\Product\ProductIcon", mappedBy="Product",cascade={"remove"})
     */
    private $ProductIcon;

    /**
     * @var string
     * @ORM\Column(name="note", type="text", nullable=true)
     */
    private $note;

    /**
     * @var integer
     * @ORM\Column(name="introduce_good_id", type="integer", nullable=true)
     */
    private $introduce_good_id;

    /**
     * @var integer
     * @ORM\Column(name="cycle_purchase_id", type="integer", nullable=true)
     */
    private $cycle_purchase_id;

    /**
     * @var integer
     * @ORM\Column(name="purchase_limited_number", type="integer", nullable=true)
     */
    private $purchase_limited_number;

    /**
     * @var integer
     * @ORM\Column(name="purchase_minimum_number", type="integer", nullable=true)
     */
    private $purchase_minimum_number;

    /**
     * @var integer
     * @ORM\Column(name="delivery_calculation_number", type="integer", nullable=true)
     */
    private $delivery_calculation_number;

    /**
     * @var string
     * @ORM\Column(name="publication_start_date", type="string", nullable=true)
     */
    private $publication_start_date;

    /**
     * @var string
     * @ORM\Column(name="publication_end_date", type="string", nullable=true)
     */
    private $publication_end_date;

    /**
     * @var integer
     * @ORM\Column(name="priority", type="integer", nullable=true)
     */
    private $priority;

    /**
     * @var boolean
     * @ORM\Column(name="is_list_page", type="boolean", nullable=true)
     */
    private $is_list_page;

    /**
     * @var boolean
     * @ORM\Column(name="is_detail_page", type="boolean", nullable=true)
     */
    private $is_detail_page;

    /**
     * @var boolean
     * @ORM\Column(name="status_id", type="boolean", nullable=true)
     */
    private $status_id;

    /**
     * @var boolean
     * @ORM\Column(name="is_import", type="boolean", nullable=true)
     */
    private $is_import;

    /**
     * @var string
     * @ORM\Column(name="product_index", type="string", nullable=true)
     */
    private $product_index;

    /**
     * @var integer
     * @ORM\Column(name="unit", type="string", nullable=true)
     * @ORM\Column(type="string")
     */
    private $unit;

    /**
     * @var integer
     * @ORM\Column(name="product_shortname", type="integer", nullable=true)
     */
    private $product_shortname;

    /**
     * @var \Customize\Entity\Setting\PurchaseGroup
     * @ORM\OneToOne(targetEntity="Customize\Entity\Setting\PurchaseGroup")
     * @ORM\JoinColumns({
     *  @ORM\JoinColumn(name="purchase_group_id", referencedColumnName="id")
     * })
     */
    private $PurchaseGroup;

    /**
     * @var \Eccube\Entity\ProductClass
     * @ORM\OneToOne(targetEntity="Eccube\Entity\ProductClass")
     * @ORM\JoinColumns({
     * @ORM\JoinColumn(name="product_specification_id", referencedColumnName="id")
     * })
     */
    private $ProductSpecification;


    /**
     * 塩分
     * @var string
     * @ORM\Column(name="salt_amount", type="string", nullable=true)
     */
    private $salt_amount;

    /**
     * カロリー
     * @var string
     * @ORM\Column(name="calorie", type="string", nullable=true)
     */
    private $calorie;

    /**
     * アレルギー
     * @var string
     * @ORM\Column(name="allergy", type="string", nullable=true)
     */
    private $allergy;

    /**
     * 原材料
     * @var string
     * @ORM\Column(name="raw_materials", type="string", nullable=true)
     */
    private $raw_materials;

    /**
     * 商品説明１
     * @var string|null
     *
     * @ORM\Column(name="description_detail", type="string", length=4000, nullable=true)
     */
    private $description_detail;

    /**
     * Constructor
     */
    public function __construct()
    {

        $this->ProductIcon = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set product_code_id.
     *
     * @param $product_code_id
     * @return Product
     */
    public function setProductCodeId($product_code_id)
    {
        $this->product_code_id = $product_code_id;

        return $this;
    }

    /**
     * Get plan_id.
     *
     * @return string|null
     */
    public function getProductCodeId()
    {
        return $this->product_code_id;
    }

    /**
     * Set poe_power.
     *
     * @param string|null $part_number
     *
     * @return
     */
    public function setPartNumber($part_number = null)
    {
        $this->part_number = $part_number;

        return $this;
    }

    /**
     * Get part_number.
     *
     * @return string|null
     */
    public function getPartNumber()
    {
        return $this->part_number;
    }

    /**
     * Set sub_name.
     *
     * @param string|null $sub_name
     *
     * @return
     */
    public function setSubName($sub_name = null)
    {
        $this->sub_name = $sub_name;

        return $this;
    }

    /**
     * Get sub_name.
     *
     * @return string|null
     */
    public function getSubName()
    {
        return $this->sub_name;
    }

    /**
     * Set PurchasingGroup.
     *
     * @param \Eccube\Entity\Master\PurchasingGroup|null $purchasingGroup
     *
     * @return Product
     */
    public function setPurchasingGroup($purchasingGroup = null)
    {
        $this->PurchasingGroup = $purchasingGroup;

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
     * Set variation_group.
     *
     * @param string|null $variation_group
     *
     * @return
     */
    public function setVariationGroup($value = null)
    {
        $this->variation_group = $value;

        return $this;
    }

    /**
     * Get variation_group.
     *
     * @return string|null
     */
    public function getVariationGroup()
    {
        return $this->variation_group;
    }

    /**
     * Set variation_priority.
     *
     * @param string|null $variation_priority
     *
     * @return
     */
    public function setVariationPriority($value = null)
    {
        $this->variation_priority = $value;

        return $this;
    }

    /**
     * Get variation_priority.
     *
     * @return string|null
     */
    public function getVariationPriority()
    {
        return $this->variation_priority;
    }

    /**
     * Set size.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setSize($value = null)
    {
        $this->size = $value;

        return $this;
    }

    /**
     * Get size.
     *
     * @return string|null
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set color.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setColor($value = null)
    {
        $this->color = $value;

        return $this;
    }

    /**
     * Get color.
     *
     * @return string|null
     */
    public function getColor()
    {
        return $this->color;
    }

//    /**
//     * Set ProductBrand.
//     *
//     * @param \Customize\Entity\Product\ProductBrand|null $brand
//     *
//     * @return Product
//     */
//    public function setProductBrand($brand = null)
//    {
//        $this->ProductBrand = $brand;
//
//        return $this;
//    }
//
//    /**
//     * Get ProductBrand.
//     *
//     * @return \Customize\Entity\Product\ProductBrand|null
//     */
//    public function getProductBrand()
//    {
//        return $this->ProductBrand;
//    }

    /**
     * Set ProductTopic.
     *
     * @param \Customize\Entity\Product\ProductTopic|null $topic
     *
     * @return Product
     */
    public function setProductTopic($topic = null)
    {
        $this->ProductTopic = $topic;

        return $this;
    }

    /**
     * Get ProductTopic.
     *
     * @return \Customize\Entity\Product\ProductTopic|null
     */
    public function getProductTopic()
    {
        return $this->ProductTopic;
    }

    /**
     * Set ProductMaker.
     *
     * @param \Customize\Entity\Product\ProductMaker|null $maker
     *
     * @return Product
     */
    public function setProductMaker($maker = null)
    {
        $this->ProductMaker = $maker;

        return $this;
    }

    /**
     * Get ProductMaker.
     *
     * @return \Customize\Entity\Product\ProductMaker|null
     */
    public function getProductMaker()
    {
        return $this->ProductMaker;
    }

    /**
     * Set Category.
     *
     * @param Category|null $category
     *
     * @return Product
     */
    public function setCategory($category = null)
    {
        $this->Category = $category;

        return $this;
    }

    /**
     * Get Category.
     *
     * @return Category|null
     */
    public function getCategory()
    {
        return $this->Category;
    }


    /**
     * Set ProductSupplier.
     *
     * @param \Customize\Entity\Product\ProductSupplier|null $supplier
     *
     * @return Product
     */
    public function setProductSupplier($supplier = null)
    {
        $this->ProductSupplier = $supplier;

        return $this;
    }

    /**
     * Get ProductSupplier.
     *
     * @return \Customize\Entity\Product\ProductSupplier|null
     */
    public function getProductSupplier()
    {
        return $this->ProductSupplier;
    }

    /**
     * Set ProductGift.
     *
     * @param \Customize\Entity\Product\ProductGift|null $gift
     *
     * @return Product
     */
    public function setProductGift($gift = null)
    {
        $this->ProductGift = $gift;

        return $this;
    }

    /**
     * Get ProductGift.
     *
     * @return \Customize\Entity\Product\ProductGift|null
     */
    public function getProductGift()
    {
        return $this->ProductGift;
    }


    /**
     * Set BulkBuying.
     *
     * @param \Customize\Entity\Mater\BulkBuying|null $bulkBuying
     *
     * @return Product
     */
    public function setBulkBuying($bulkBuying = null)
    {
        $this->BulkBuying = $bulkBuying;

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
     * Set detail_url.
     *
     * @param string|null $value
     *
     * @return
     */
    public function setDetailUrl($value = null)
    {
        $this->detail_url = $value;

        return $this;
    }

    /**
     * Get detail_url.
     *
     * @return string|null
     */
    public function getDetailUrl()
    {
        return $this->detail_url;
    }

    /**
     * Set one_word_comment.
     *
     * @param string|null $value
     *
     * @return
     */
    public function setOneWordComment($value = null)
    {
        $this->one_word_comment = $value;

        return $this;
    }

    /**
     * Get one_word_comment.
     *
     * @return string|null
     */
    public function getOneWordComment()
    {
        return $this->one_word_comment;
    }


    /**
     * Set TemperatureRange.
     *
     * @param \Customize\Entity\Master\TemperatureRange|null $temperatureRange
     *
     * @return Product
     */
    public function setTemperatureRange(
        \Customize\Entity\Master\TemperatureRange $temperatureRange = null
    ) {
        $this->TemperatureRange = $temperatureRange;

        return $this;
    }

    /**
     * Get TemperatureRange.
     *
     * @return \Customize\Entity\Master\TemperatureRange|null
     */
    public function getTemperatureRange()
    {
        return $this->TemperatureRange;
    }

    /**
     * Set RegularPurchaseCategory.
     *
     * @param \Customize\Entity\Master\RegularPurchaseCategory|null $regularPurchaseCategoryId
     *
     * @return Product
     */
    public function setRegularPurchaseCategoryId(
        \Customize\Entity\Master\RegularPurchaseCategory $regularPurchaseCategoryId = null
    ) {
        $this->RegularPurchaseCategoryId = $regularPurchaseCategoryId;

        return $this;
    }

    /**
     * Get RegularPurchaseCategory.
     *
     * @return \Customize\Entity\Master\RegularPurchaseCategory|null
     */
    public function getRegularPurchaseCategoryId()
    {
        return $this->RegularPurchaseCategoryId;
    }

    /**
     * Add productIcon.
     *
     * @param \Customize\Entity\Product\ProductIcon $productIcon
     *
     * @return Product
     */
    public function addProductIcon(ProductIcon $productIcon)
    {
        $this->ProductIcon[] = $productIcon;

        return $this;
    }

    /**
     * Remove productIcon.
     *
     * @param \Customize\Entity\Product\ProductIcon $productIcon
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProductIcon(ProductIcon $productIcon)
    {
        return $this->ProductIcon->removeElement($productIcon);
    }

    /**
     * Get productIcon
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProductIcon()
    {
        return $this->ProductIcon;
    }

    /**
     * Set note.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setNote($value = null)
    {
        $this->note = $value;

        return $this;
    }

    /**
     * Get note.
     *
     * @return string|null
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * Set introduce_good_id.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setIntroduceGoodId($value = null)
    {
        $this->introduce_good_id = $value;

        return $this;
    }

    /**
     * Get introduce_good_id.
     *
     * @return string|null
     */
    public function getIntroduceGoodId()
    {
        return $this->introduce_good_id;
    }

    /**
     * Set cycle_purchase_id.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setCyclePurchaseId($value = null)
    {
        $this->cycle_purchase_id = $value;

        return $this;
    }

    /**
     * Get cycle_purchase_id.
     *
     * @return string|null
     */
    public function getCyclePurchaseId()
    {
        return $this->cycle_purchase_id;
    }

    /**
     * Set purchase_limited_number.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setPurchaseLimitedNumber($value = null)
    {
        $this->purchase_limited_number = $value;

        return $this;
    }

    /**
     * Get purchase_limited_number.
     *
     * @return string|null
     */
    public function getPurchaseLimitedNumber()
    {
        return $this->purchase_limited_number;
    }

    /**
     * Set purchase_minimum_number.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setPurchaseMinimumNumber($value = null)
    {
        $this->purchase_minimum_number = $value;

        return $this;
    }

    /**
     * Get purchase_minimum_number.
     *
     * @return string|null
     */
    public function getPurchaseMinimumNumber()
    {
        return $this->purchase_minimum_number;
    }

    /**
     * Set delivery_calculation_number.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setDeliveryCalculationNumber($value = null)
    {
        $this->delivery_calculation_number = $value;

        return $this;
    }

    /**
     * Get delivery_calculation_number.
     *
     * @return integer|null
     */
    public function getDeliveryCalculationNumber()
    {
        return $this->delivery_calculation_number;
    }

    /**
     * Set publication_start_date.
     *
     * @param string|null $value
     *
     * @return
     */
    public function setPublicationStartDate($value = null)
    {
        $this->publication_start_date = $value;

        return $this;
    }

    /**
     * Get publication_start_date.
     *
     * @return string|null
     */
    public function getPublicationStartDate()
    {
        return $this->publication_start_date;
    }

    /**
     * Set publication_end_date.
     *
     * @param string|null $value
     *
     * @return
     */
    public function setPublicationEndDate($value = null)
    {
        $this->publication_end_date = $value;

        return $this;
    }

    /**
     * Get publication_end_date.
     *
     * @return string|null
     */
    public function getPublicationEndDate()
    {
        return $this->publication_end_date;
    }

    /**
     * Set priority.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setPriority($value = null)
    {
        $this->priority = $value;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return integer|null
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set is_list_page.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setIsListPage($value = null)
    {
        $this->is_list_page = $value;

        return $this;
    }

    /**
     * Get is_list_page.
     *
     * @return integer|null
     */
    public function getIsListPage()
    {
        return $this->is_list_page;
    }

    /**
     * Set is_detail_page.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setIsDetailPage($value = null)
    {
        $this->is_detail_page = $value;

        return $this;
    }

    /**
     * Get is_detail_page.
     *
     * @return integer|null
     */
    public function getIsDetailPage()
    {
        return $this->is_detail_page;
    }

    /**
     * Set status_id.
     *
     * @param integer|null $value
     *
     * @return
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
     * Set is_import.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setIsImport($value = null)
    {
        $this->is_import = $value;

        return $this;
    }

    /**
     * Get is_import.
     *
     * @return integer|null
     */
    public function getIsImport()
    {
        return $this->is_import;
    }

    /**
     * Set product_shortname.
     *
     * @param integer|null $value
     *
     * @return
     */
    public function setProductShortname($value = null)
    {
        $this->product_shortname = $value;

        return $this;
    }

    /**
     * Get product_shortname.
     *
     * @return integer|null
     */
    public function getProductShortname()
    {
        return $this->product_shortname;
    }

    /**
     * Set PurchaseGroup.
     *
     * @param \Customize\Entity\Setting\PurchaseGroup|null $purchaseGroup
     *
     * @return Product
     */
    public function setPurchaseGroup(
        \Customize\Entity\Setting\PurchaseGroup $purchaseGroup = null
    ) {
        $this->PurchaseGroup = $purchaseGroup;

        return $this;
    }

    /**
     * Get PurchaseGroup.
     *
     * @return \Customize\Entity\Setting\PurchaseGroup|null
     */
    public function getPurchaseGroup()
    {
        return $this->PurchaseGroup;
    }

    /**
     * Set salt_amount.
     *
     * @param $salt_amount
     * @return
     */
    public function setSaltAmount($value)
    {
        $this->salt_amount = $value;

        return $this;
    }

    /**
     * Get salt_amount.
     *
     * @return string|null
     */
    public function getSaltAmount()
    {
        return $this->salt_amount;
    }

    /**
     * Set calorie.
     *
     * @param $calorie
     * @return
     */
    public function setCalorie($value)
    {
        $this->calorie = $value;

        return $this;
    }

    /**
     * Get calorie.
     *
     * @return string|null
     */
    public function getCalorie()
    {
        return $this->calorie;
    }

    /**
     * Set allergy.
     *
     * @param $allergy
     * @return
     */
    public function setAllergy($value)
    {
        $this->allergy = $value;

        return $this;
    }

    /**
     * Get allergy.
     *
     * @return string|null
     */
    public function getAllergy()
    {
        return $this->allergy;
    }

    /**
     * Set raw_materials.
     *
     * @param $raw_materials
     * @return
     */
    public function setRawMaterials($value)
    {
        $this->raw_materials = $value;

        return $this;
    }

    /**
     * Get raw_materials.
     *
     * @return string|null
     */
    public function getRawMaterials()
    {
        return $this->raw_materials;
    }
}
