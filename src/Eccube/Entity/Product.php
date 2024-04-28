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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

if (!class_exists('\Eccube\Entity\Product')) {
    /**
     * Product
     *
     * @ORM\Table(name="dtb_product")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductRepository")
     * @UniqueEntity(
     *     "code",
     *     message="商品コードが重複しています"
     * )
     */
    class Product extends \Eccube\Entity\AbstractEntity
    {
        private $_calc = false;
        private $stockFinds = [];
        private $stocks = [];
        private $stockUnlimiteds = [];
        private $price01 = [];
        private $price02 = [];
        private $price01IncTaxs = [];
        private $price02IncTaxs = [];
        private $codes = [];
        private $classCategories1 = [];
        private $classCategories2 = [];
        private $className1;
        private $className2;

        /**
         * @return string
         */
        public function __toString()
        {
            return (string) $this->getName();
        }

        public function _calc()
        {
            if (!$this->_calc) {
                $i = 0;
                foreach ($this->getProductClasses() as $ProductClass) {
                    /* @var $ProductClass \Eccube\Entity\ProductClass */
                    // stock_find
//                    if ($ProductClass->isVisible() == false) {
//                        continue;
//                    }
//                    $ClassCategory1 = $ProductClass->getClassCategory1();
//                    $ClassCategory2 = $ProductClass->getClassCategory2();
//                    if ($ClassCategory1 && !$ClassCategory1->isVisible()) {
//                        continue;
//                    }
//                    if ($ClassCategory2 && !$ClassCategory2->isVisible()) {
//                        continue;
//                    }
//
//                    // stock_find
//                    $this->stockFinds[] = $ProductClass->getStockFind();
//
//                    // stock
//                    $this->stocks[] = $ProductClass->getStock();
//
//                    // stock_unlimited
//                    $this->stockUnlimiteds[] = $ProductClass->isStockUnlimited();

                    // price01
                    if (!is_null($ProductClass->getPrice())) {
                        $this->price01[] = $ProductClass->getPrice();
                        // price01IncTax
                        $this->price01IncTaxs[] = $ProductClass->getPrice01IncTax();
                    }

                    // price02
                    $this->price02[] = $ProductClass->getPrice();

                    // price02IncTax
                    $this->price02IncTaxs[] = $ProductClass->getPrice02IncTax();

                    // product_code
                    $this->codes[] = $ProductClass->getCode();

                    if ($i === 0) {
                        if ($ProductClass->getClassCategory1() && $ProductClass->getClassCategory1()->getId()) {
                            $this->className1 = $ProductClass->getClassCategory1()->getClassName()->getName();
                        }
                        if ($ProductClass->getClassCategory2() && $ProductClass->getClassCategory2()->getId()) {
                            $this->className2 = $ProductClass->getClassCategory2()->getClassName()->getName();
                        }
                    }
                    if ($ProductClass->getClassCategory1()) {
                        $classCategoryId1 = $ProductClass->getClassCategory1()->getId();
                        if (!empty($classCategoryId1)) {
                            if ($ProductClass->getClassCategory2()) {
                                $this->classCategories1[$ProductClass->getClassCategory1()->getId()] = $ProductClass->getClassCategory1()->getName();
                                $this->classCategories2[$ProductClass->getClassCategory1()->getId()][$ProductClass->getClassCategory2()->getId()] = $ProductClass->getClassCategory2()->getName();
                            } else {
                                $this->classCategories1[$ProductClass->getClassCategory1()->getId()] = $ProductClass->getClassCategory1()->getName() . ($ProductClass->getStockFind() ? '' : trans('front.product.out_of_stock_label'));
                            }
                        }
                    }
                    $i++;
                }
                $this->_calc = true;
            }
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
            return $this->getStatus()->getId() === \Eccube\Entity\Master\ProductStatus::DISPLAY_SHOW ? true : false;
        }

        /**
         * Get ClassName1
         *
         * @return string
         */
        public function getClassName1()
        {
            $this->_calc();

            return $this->className1;
        }

        /**
         * Get ClassName2
         *
         * @return string
         */
        public function getClassName2()
        {
            $this->_calc();

            return $this->className2;
        }

        /**
         * Get getClassCategories1
         *
         * @return array
         */
        public function getClassCategories1()
        {
            $this->_calc();

            return $this->classCategories1;
        }

        public function getClassCategories1AsFlip()
        {
            return array_flip($this->getClassCategories1());
        }

        /**
         * Get getClassCategories2
         *
         * @return array
         */
        public function getClassCategories2($class_category1)
        {
            $this->_calc();

            return isset($this->classCategories2[$class_category1]) ? $this->classCategories2[$class_category1] : [];
        }

        public function getClassCategories2AsFlip($class_category1)
        {
            return array_flip($this->getClassCategories2($class_category1));
        }

        /**
         * Get StockFind
         *
         * @return bool
         */
        public function getStockFind()
        {
            $this->_calc();

            return count($this->stockFinds)
                ? max($this->stockFinds)
                : null;
        }

        /**
         * Get Stock min
         *
         * @return integer
         */
        public function getStockMin()
        {
            $this->_calc();

            return count($this->stocks)
                ? min($this->stocks)
                : null;
        }

        /**
         * Get Stock max
         *
         * @return integer
         */
        public function getStockMax()
        {
            $this->_calc();

            return count($this->stocks)
                ? max($this->stocks)
                : null;
        }

        /**
         * Get StockUnlimited min
         *
         * @return integer
         */
        public function getStockUnlimitedMin()
        {
            $this->_calc();

            return count($this->stockUnlimiteds)
                ? min($this->stockUnlimiteds)
                : null;
        }

        /**
         * Get StockUnlimited max
         *
         * @return integer
         */
        public function getStockUnlimitedMax()
        {
            $this->_calc();

            return count($this->stockUnlimiteds)
                ? max($this->stockUnlimiteds)
                : null;
        }

        /**
         * Get Price01 min
         *
         * @return integer
         */
        public function getPrice01Min()
        {
            $this->_calc();

            if (count($this->price01) == 0) {
                return null;
            }

            return min($this->price01);
        }

        /**
         * Get Price01 max
         *
         * @return integer
         */
        public function getPrice01Max()
        {
            $this->_calc();

            if (count($this->price01) == 0) {
                return null;
            }

            return max($this->price01);
        }

        /**
         * Get Price02 min
         *
         * @return integer
         */
        public function getPrice02Min()
        {
            $this->_calc();

            return count($this->price02)
                ? min($this->price02)
                : null;
        }

        /**
         * Get Price02 max
         *
         * @return integer
         */
        public function getPrice02Max()
        {
            $this->_calc();

            return count($this->price02)
                ? max($this->price02)
                : null;
        }

        /**
         * Get Price01IncTax min
         *
         * @return integer
         */
        public function getPrice01IncTaxMin()
        {
            $this->_calc();

            return count($this->price01IncTaxs)
                ? min($this->price01IncTaxs)
                : null;
        }

        /**
         * Get Price01IncTax max
         *
         * @return integer
         */
        public function getPrice01IncTaxMax()
        {
            $this->_calc();

            return count($this->price01IncTaxs)
                ? max($this->price01IncTaxs)
                : null;
        }

        /**
         * Get Price02IncTax min
         *
         * @return integer
         */
        public function getPrice02IncTaxMin()
        {
            $this->_calc();

            return count($this->price02IncTaxs)
                ? min($this->price02IncTaxs)
                : null;
        }

        /**
         * Get Price02IncTax max
         *
         * @return integer
         */
        public function getPrice02IncTaxMax()
        {
            $this->_calc();

            return count($this->price02IncTaxs)
                ? max($this->price02IncTaxs)
                : null;
        }

        /**
         * Get Product_code min
         *
         * @return integer
         */
        public function getCodeMin()
        {
            $this->_calc();

            $codes = [];
            foreach ($this->codes as $code) {
                if (!is_null($code)) {
                    $codes[] = $code;
                }
            }

            return count($codes) ? min($codes) : null;
        }

        /**
         * Get Product_code max
         *
         * @return integer
         */
        public function getCodeMax()
        {
            $this->_calc();

            $codes = [];
            foreach ($this->codes as $code) {
                if (!is_null($code)) {
                    $codes[] = $code;
                }
            }

            return count($codes) ? max($codes) : null;
        }

        public function getMainListImage()
        {
            $ProductImages = $this->getProductImage();

            return empty($ProductImages) ? null : $ProductImages[0];
        }

        public function getMainFileName()
        {
            if (count($this->ProductImage) > 0) {
                return $this->ProductImage[0];
            } else {
                return null;
            }
        }

        public function hasProductClass()
        {
            foreach ($this->ProductClasses as $ProductClass) {
                if (!$ProductClass->isVisible()) {
                    continue;
                }
                if (!is_null($ProductClass->getClassCategory1())) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @var integer
         *
         * @ORM\Column(name="id", type="integer", options={"unsigned":true})
         * @ORM\Id
         * @ORM\GeneratedValue(strategy="IDENTITY")
         */
        private $id;

        /**
         * @var integer
         * @ORM\Column(name="code", type="string", length=255)
         */
        private $code;

        /**
         * 商品名
         * @var string
         *
         * @ORM\Column(name="name", type="string", length=255)
         */
        private $name;

        /**
         * 索引
         * @var string
         * @ORM\Column(name="product_index", type="string", nullable=true)
         */
        private $product_index;

        /**
         * 単位
         * @var integer
         * @ORM\Column(name="unit", type="string", nullable=true)
         */
        private $unit;

        /**
         * 大分類
         * @var \Customize\Entity\Master\BroadCategory
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\BroadCategory")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="broad_category_id", referencedColumnName="id")
         * })
         */
        private $BroadCategory;

        /**
         * 中分類
         * @var \Customize\Entity\Master\MiddleCategory
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\MiddleCategory", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="middle_category_id", referencedColumnName="id")
         * })
         */
        private $MiddleCategory;

        /**
         * 商品　カテゴリ　中間テーブル
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\ProductClass", mappedBy="Product", cascade={"persist","remove"})
         */
        private $ProductClasses;

        /**
         * 商品名略称
         * @var string
         * @ORM\Column(name="product_shortname", type="string", nullable=true)
         */
        private $product_shortname;

        /**
         * 送料計算用区分
         * @var \Customize\Entity\Master\DeliveryCalculation
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\DeliveryCalculation", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="delivery_calculation_id", referencedColumnName="id")
         * })
         */
        private $DeliveryCalculation;

        /**
         * 詰込管理区分
         * @var \Customize\Entity\Master\PackingManagement
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\PackingManagement", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="packing_management_id", referencedColumnName="id")
         * })
         */
        private $PackingManagement;

        /**
         * リパック区分
         * @var \Customize\Entity\Master\Repack
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\Repack", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="repack_id", referencedColumnName="id")
         * })
         */
        private $Repack;

        /**
         * 加工区分
         * @var \Customize\Entity\Master\ProcessedProductCategory
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\ProcessedProductCategory", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="processed_product_category_id", referencedColumnName="id")
         * })
         */
        private $ProcessedProductCategory;

        /**
         * 仕入先
         * @var \Customize\Entity\Product\ProductSupplier
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductSupplier", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="supplier_id", referencedColumnName="id")
         * })
         */
        private $ProductSupplier;

        /**
         * 量目
         * @var string
         * @ORM\Column(name="weight", type="string", nullable=true)
         */
        private $weight;

        /**
         * 加工場所
         * @var string
         * @ORM\Column(name="processing_place", type="string", nullable=true)
         */
        private $processing_place;

        /**
         * 調理方法
         * @var string
         * @ORM\Column(name="cooking_method", type="string", nullable=true)
         */
        private $cooking_method;

        /**
         * 解凍区分
         * @var \Customize\Entity\Master\DecompressionMethod
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\DecompressionMethod", inversedBy="Product")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="decompression_method_id", referencedColumnName="id")
         * })
         */
        private $DecompressionMethod;

        /**
         * 商品説明（一覧）
         * @var string|null
         *
         * @ORM\Column(name="description_list", type="string", length=4000, nullable=true)
         */
        private $description_list;

        /**
         * 商品画像小・大・パッケージ画像
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\ProductImage", mappedBy="Product", cascade={"remove"})
         * @ORM\OrderBy({
         *     "sort_no"="ASC"
         * })
         */
        private $ProductImage;

        /**
         * 販売種別
         * @var string
         * @ORM\Column(name="sale_type", type="string", nullable=true)
         */
        private $sale_type;

        /**
         * 備考
         * @var string|null
         *
         * @ORM\Column(name="note", type="string", length=4000, nullable=true)
         */
        private $note;

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

        // /**
        //  * @var \Doctrine\Common\Collections\Collection
        //  *
        //  * @ORM\OneToMany(targetEntity="Eccube\Entity\ProductTag", mappedBy="Product", cascade={"remove"})
        //  */
        // private $ProductTag;

        // /**
        //  * @var \Doctrine\Common\Collections\Collection
        //  *
        //  * @ORM\OneToMany(targetEntity="Eccube\Entity\CustomerFavoriteProduct", mappedBy="Product")
        //  */
        // private $CustomerFavoriteProducts;

        /**
         * @var \Eccube\Entity\Member
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
         * })
         */
        private $Creator;

        /**
         * ステータス
         * @var \Eccube\Entity\Master\ProductStatus
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Master\ProductStatus")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="product_status_id", referencedColumnName="id")
         * })
         */
        private $Status;

        /**
         * カテゴリ
         * @var \Eccube\Entity\Category
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Category")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
         * })
         */
        private $Category;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->ProductCategories = new \Doctrine\Common\Collections\ArrayCollection();
            $this->ProductClasses = new \Doctrine\Common\Collections\ArrayCollection();
            $this->ProductImage = new \Doctrine\Common\Collections\ArrayCollection();
            $this->ProductTag = new \Doctrine\Common\Collections\ArrayCollection();
            $this->CustomerFavoriteProducts = new \Doctrine\Common\Collections\ArrayCollection();
        }

        public function __clone()
        {
            $this->id = null;
        }

        public function copy()
        {
            // コピー対象外
            $this->CustomerFavoriteProducts = new ArrayCollection();

            $Categories = $this->getProductCategories();
            $this->ProductCategories = new ArrayCollection();
            foreach ($Categories as $Category) {
                $CopyCategory = clone $Category;
                $this->addProductCategory($CopyCategory);
                $CopyCategory->setProduct($this);
            }

            $Classes = $this->getProductClasses();
            $this->ProductClasses = new ArrayCollection();
            foreach ($Classes as $Class) {
                $CopyClass = clone $Class;
                $this->addProductClass($CopyClass);
                $CopyClass->setProduct($this);
            }

            $Images = $this->getProductImage();
            $this->ProductImage = new ArrayCollection();
            foreach ($Images as $Image) {
                $CloneImage = clone $Image;
                $this->addProductImage($CloneImage);
                $CloneImage->setProduct($this);
            }

            $Tags = $this->getProductTag();
            $this->ProductTag = new ArrayCollection();
            foreach ($Tags as $Tag) {
                $CloneTag = clone $Tag;
                $this->addProductTag($CloneTag);
                $CloneTag->setProduct($this);
            }

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
         * Set code.
         *
         * @param string $code
         *
         * @return Product
         */
        public function setCode($code)
        {
            $this->code = $code;

            return $this;
        }

        /**
         * Get code.
         *
         * @return string
         */
        public function getCode()
        {
            return $this->code;
        }

        /**
         * Set name.
         *
         * @param string $name
         *
         * @return Product
         */
        public function setName($name)
        {
            $this->name = $name;

            return $this;
        }

        /**
         * Get name.
         *
         * @return string
         */
        public function getName()
        {
            return $this->name;
        }

        /**
         * Set product_index.
         *
         * @param string|null $value
         *
         * @return
         */
        public function setProductIndex($value = null)
        {
            $this->product_index = $value;

            return $this;
        }

        /**
         * Get product_index.
         *
         * @return string|null
         */
        public function getProductIndex()
        {
            return $this->product_index;
        }

        /**
         * Set unit.
         *
         * @param integer|null $value
         *
         * @return
         */
        public function setUnit($value = null)
        {
            $this->unit = $value;

            return $this;
        }

        /**
         * Get unit.
         *
         * @return integer|null
         */
        public function getUnit()
        {
            return $this->unit;
        }

        /**
         * Set BroadCategory.
         *
         * @param \Customize\Entity\Master\BroadCategory|null $broadCategory
         *
         * @return Product
         */
        public function setBroadCategory($broadCategory = null)
        {
            $this->BroadCategory = $broadCategory;

            return $this;
        }

        /**
         * Get BroadCategory.
         *
         * @return \Customize\Entity\Master\BroadCategory|null
         */
        public function getBroadCategory()
        {
            return $this->BroadCategory;
        }

        /**
         * Set MiddleCategory.
         *
         * @param \Customize\Entity\Master\MiddleCategory|null $middleCategory
         *
         * @return Product
         */
        public function setMiddleCategory($middleCategory = null)
        {
            $this->MiddleCategory = $middleCategory;

            return $this;
        }

        /**
         * Get MiddleCategory.
         *
         * @return \Customize\Entity\Master\MiddleCategory|null
         */
        public function getMiddleCategory()
        {
            return $this->MiddleCategory;
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
         * Set DeliveryCalculation.
         *
         * @param \Customize\Entity\Master\DeliveryCalculation|null $delivery_calculation
         *
         * @return Product
         */
        public function setDeliveryCalculation($delivery_calculation = null)
        {
            $this->DeliveryCalculation = $delivery_calculation;

            return $this;
        }

        /**
         * Get DeliveryCalculation.
         *
         * @return \Customize\Entity\Master\DeliveryCalculation|null
         */
        public function getDeliveryCalculation()
        {
            return $this->DeliveryCalculation;
        }

        /**
         * Set PackingManagement.
         *
         * @param \Customize\Entity\Master\PackingManagement|null $packing_management
         *
         * @return Product
         */
        public function setPackingManagement($packing_management = null)
        {
            $this->PackingManagement = $packing_management;

            return $this;
        }

        /**
         * Get PackingManagement.
         *
         * @return \Customize\Entity\Master\PackingManagement|null
         */
        public function getPackingManagement()
        {
            return $this->PackingManagement;
        }

        /**
         * Set Repack.
         *
         * @param \Customize\Entity\Master\Repack|null $repack
         *
         * @return Product
         */
        public function setRepack($repack = null)
        {
            $this->Repack = $repack;

            return $this;
        }

        /**
         * Get Repack.
         *
         * @return \Customize\Entity\Master\Repack|null
         */
        public function getRepack()
        {
            return $this->Repack;
        }

        /**
         * Set ProcessedProductCategory.
         *
         * @param \Customize\Entity\Master\ProcessedProductCategory|null $processed_product_category
         *
         * @return Product
         */
        public function setProcessedProductCategory($processed_product_category = null)
        {
            $this->ProcessedProductCategory = $processed_product_category;

            return $this;
        }

        /**
         * Get ProcessedProductCategory.
         *
         * @return \Customize\Entity\Master\ProcessedProductCategory|null
         */
        public function getProcessedProductCategory()
        {
            return $this->ProcessedProductCategory;
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
         * Set weight.
         *
         * @param $weight
         * @return
         */
        public function setWeight($value)
        {
            $this->weight = $value;

            return $this;
        }

        /**
         * Get weight.
         *
         * @return string|null
         */
        public function getWeight()
        {
            return $this->weight;
        }

        /**
         * Set processing_place.
         *
         * @param $processing_place
         * @return
         */
        public function setProcessingPlace($value)
        {
            $this->processing_place = $value;

            return $this;
        }

        /**
         * Get processing_place.
         *
         * @return string|null
         */
        public function getProcessingPlace()
        {
            return $this->processing_place;
        }

        /**
         * Set cooking_method.
         *
         * @param $cooking_method
         * @return
         */
        public function setCookingMethod($value)
        {
            $this->cooking_method = $value;

            return $this;
        }

        /**
         * Get cooking_method.
         *
         * @return string|null
         */
        public function getCookingMethod()
        {
            return $this->cooking_method;
        }

        /**
         * Set DecompressionMethod.
         *
         * @param \Customize\Entity\Master\DecompressionMethod|null $decompression_method
         *
         * @return Product
         */
        public function setDecompressionMethod($decompression_method = null)
        {
            $this->DecompressionMethod = $decompression_method;

            return $this;
        }

        /**
         * Get DecompressionMethod.
         *
         * @return \Customize\Entity\Master\DecompressionMethod|null
         */
        public function getDecompressionMethod()
        {
            return $this->DecompressionMethod;
        }


        /**
         * Set sale_type.
         *
         * @param string|null $value
         *
         * @return
         */
        public function setSaleType($value = null)
        {
            $this->sale_type = $value;

            return $this;
        }

        /**
         * Get sale_type.
         *
         * @return string|null
         */
        public function getSaleType()
        {
            return $this->sale_type;
        }

        /**
         * Set note.
         *
         * @param string|null $note
         *
         * @return Product
         */
        public function setNote($note = null)
        {
            $this->note = $note;

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
         * Set descriptionList.
         *
         * @param string|null $descriptionList
         *
         * @return Product
         */
        public function setDescriptionList($descriptionList = null)
        {
            $this->description_list = $descriptionList;

            return $this;
        }

        /**
         * Get descriptionList.
         *
         * @return string|null
         */
        public function getDescriptionList()
        {
            return $this->description_list;
        }

        /**
         * Set descriptionDetail.
         *
         * @param string|null $descriptionDetail
         *
         * @return Product
         */
        public function setDescriptionDetail($descriptionDetail = null)
        {
            $this->description_detail = $descriptionDetail;

            return $this;
        }

        /**
         * Get descriptionDetail.
         *
         * @return string|null
         */
        public function getDescriptionDetail()
        {
            return $this->description_detail;
        }

        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return Product
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
         * @return Product
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
         * Set category.
         *
         * @param \Eccube\Entity\Category|null $category
         *
         * @return ProductCategory
         */
        public function setCategory(Category $category = null)
        {
            $this->Category = $category;

            return $this;
        }

        /**
         * Get category.
         *
         * @return \Eccube\Entity\Category|null
         */
        public function getCategory()
        {
            return $this->Category;
        }

        /**
         * Add productClass.
         *
         * @param \Eccube\Entity\ProductClass $productClass
         *
         * @return Product
         */
        public function addProductClass(ProductClass $productClass)
        {
            $this->ProductClasses[] = $productClass;

            return $this;
        }

        /**
         * Remove productClass.
         *
         * @param \Eccube\Entity\ProductClass $productClass
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProductClass(ProductClass $productClass)
        {
            return $this->ProductClasses->removeElement($productClass);
        }

        /**
         * Get productClasses.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProductClasses()
        {
            return $this->ProductClasses;
        }

        /**
         * Add productImage.
         *
         * @param \Eccube\Entity\ProductImage $productImage
         *
         * @return Product
         */
        public function addProductImage(ProductImage $productImage)
        {
            $this->ProductImage[] = $productImage;

            return $this;
        }

        /**
         * Remove productImage.
         *
         * @param \Eccube\Entity\ProductImage $productImage
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProductImage(ProductImage $productImage)
        {
            return $this->ProductImage->removeElement($productImage);
        }

        /**
         * Get productImage.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProductImage()
        {
            return $this->ProductImage;
        }

        /**
         * Add productTag.
         *
         * @param \Eccube\Entity\ProductTag $productTag
         *
         * @return Product
         */
        public function addProductTag(ProductTag $productTag)
        {
            $this->ProductTag[] = $productTag;

            return $this;
        }

        /**
         * Remove productTag.
         *
         * @param \Eccube\Entity\ProductTag $productTag
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProductTag(ProductTag $productTag)
        {
            return $this->ProductTag->removeElement($productTag);
        }

        /**
         * Get productTag.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProductTag()
        {
            return $this->ProductTag;
        }

        /**
         * Get Tag
         * フロント側タグsort_no順の配列を作成する
         *
         * @return []Tag
         */
        public function getTags()
        {
            $tags = [];

            foreach ($this->getProductTag() as $productTag) {
                $tags[] = $productTag->getTag();
            }

            usort($tags, function (Tag $tag1, Tag $tag2) {
                return $tag1->getSortNo() < $tag2->getSortNo();
            });

            return $tags;
        }

        /**
         * Add customerFavoriteProduct.
         *
         * @param \Eccube\Entity\CustomerFavoriteProduct $customerFavoriteProduct
         *
         * @return Product
         */
        public function addCustomerFavoriteProduct(CustomerFavoriteProduct $customerFavoriteProduct)
        {
            $this->CustomerFavoriteProducts[] = $customerFavoriteProduct;

            return $this;
        }

        /**
         * Remove customerFavoriteProduct.
         *
         * @param \Eccube\Entity\CustomerFavoriteProduct $customerFavoriteProduct
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeCustomerFavoriteProduct(CustomerFavoriteProduct $customerFavoriteProduct)
        {
            return $this->CustomerFavoriteProducts->removeElement($customerFavoriteProduct);
        }

        /**
         * Get customerFavoriteProducts.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getCustomerFavoriteProducts()
        {
            return $this->CustomerFavoriteProducts;
        }

        /**
         * Set creator.
         *
         * @param \Eccube\Entity\Member|null $creator
         *
         * @return Product
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
         * Set status.
         *
         * @param \Eccube\Entity\Master\ProductStatus|null $status
         *
         * @return Product
         */
        public function setStatus(Master\ProductStatus $status = null)
        {
            $this->Status = $status;

            return $this;
        }

        /**
         * Get status.
         *
         * @return \Eccube\Entity\Master\ProductStatus|null
         */
        public function getStatus()
        {
            return $this->Status;
        }

        public function serializer()
        {
            $encoder = new JsonEncoder();

            $normalizer = new ObjectNormalizer();
            $normalizer->setIgnoredAttributes(array(
                'typeCode', 'type', 'range',
                'useCaseCode', 'useCase', 'updatedAt', 'updatedBy'
            ));

            $normalizer->setCircularReferenceHandler(function ($object) {
                return $object->getName();
            });

            $serializer = new Serializer(array($normalizer), array($encoder));
            return $serializer->serialize($this, 'json');
        }
    }
}
