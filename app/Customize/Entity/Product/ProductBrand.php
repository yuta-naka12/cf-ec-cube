<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;

if (!class_exists('\Customize\Entity\Product\ProductBrand')) {
    /**
     * ProductBrand
     *
     * @ORM\Table(name="dtb_product_brand")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductBrandRepository")
     */
    class ProductBrand extends \Eccube\Entity\AbstractEntity
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
         * ブランド
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="brand_name", type="string", nullable=true, length=32)
         */
        private $brand_name;

        /**
         * @var string
         *
         * @ORM\Column(name="brand_name_2", type="string", nullable=true,  length=32)
         */
        private $brand_name_2;

        /**
         * @var boolean
         *
         * @ORM\Column(name="is_detail_display", type="boolean", nullable=true)
         */
        private $is_detail_display;

        /**
         * @var boolean
         *
         * @ORM\Column(name="is_list_display", type="boolean", nullable=true)
         */
        private $is_list_display;

        /**
         * @var string
         *
         * @ORM\Column(name="comment", type="string", nullable=true)
         */
        private $comment;

        /**
         * @var string
         *
         * @ORM\Column(name="link", type="string", nullable=true)
         */
        private $link;

        /**
         * @var string
         *
         * @ORM\Column(name="image_url", type="string", nullable=true)
         */
        private $image_url;

        /**
         * @var string
         *
         * @ORM\Column(name="free_comment_1", type="string", nullable=true)
         */
        private $free_comment_1;

        /**
         * @var string
         *
         * @ORM\Column(name="free_comment_2", type="string", nullable=true)
         */
        private $free_comment_2;

        /**
         * 索引
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

        /**
         * @var \Doctrine\Common\Collections\Collection
         * 
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Product", mappedBy="ProductBrand")
         */
        private $Product;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->Product = new \Doctrine\Common\Collections\ArrayCollection();
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
         * Set name
         *
         * @param string|null $name
         *
         * @return ProductBrand
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
         * Set brand_name
         *
         * @param string|null $brand_name
         *
         * @return ProductBrand
         */
        public function setBrandName($name = null)
        {
            $this->brand_name = $name;

            return $this;
        }

        /**
         * Get brand_name.
         *
         * @return string|null
         */
        public function getBrandName()
        {
            return $this->brand_name;
        }

        /**
         * Set brand_name_2
         *
         * @param string|null $brand_name
         *
         * @return ProductBrand
         */
        public function setBrandName2($name = null)
        {
            $this->brand_name_2 = $name;

            return $this;
        }

        /**
         * Get brand_name_2.
         *
         * @return string|null
         */
        public function getBrandName2()
        {
            return $this->brand_name_2;
        }

        /**
         * Set comment
         *
         * @param string|null $comment
         *
         * @return ProductBrand
         */
        public function setComment($name = null)
        {
            $this->comment = $name;

            return $this;
        }

        /**
         * Get comment.
         *
         * @return string|null
         */
        public function getComment()
        {
            return $this->comment;
        }

        /**
         * Set link
         *
         * @param string|null $link
         *
         * @return ProductBrand
         */
        public function setLink($name = null)
        {
            $this->link = $name;

            return $this;
        }

        /**
         * Get link.
         *
         * @return string|null
         */
        public function getLink()
        {
            return $this->link;
        }

        /**
         * Set image_url
         *
         * @param string|null $image_url
         *
         * @return ProductBrand
         */
        public function setImageUrl($name = null)
        {
            $this->image_url = $name;

            return $this;
        }

        /**
         * Get image_url.
         *
         * @return string|null
         */
        public function getImageUrl()
        {
            return $this->image_url;
        }

        /**
         * Set free_comment_1
         *
         * @param string|null $image_url
         *
         * @return ProductBrand
         */
        public function setFreeComment1($name = null)
        {
            $this->free_comment_1 = $name;

            return $this;
        }

        /**
         * Get free_comment_1.
         *
         * @return string|null
         */
        public function getFreeComment1()
        {
            return $this->free_comment_1;
        }

        /**
         * Set free_comment_2
         *
         * @param string|null $image_url
         *
         * @return ProductBrand
         */
        public function setFreeComment2($name = null)
        {
            $this->free_comment_2 = $name;

            return $this;
        }

        /**
         * Get free_comment_2.
         *
         * @return string|null
         */
        public function getFreeComment2()
        {
            return $this->free_comment_2;
        }

        /**
         * Set name02
         *
         * @param string|null $name02
         *
         * @return ProductBrand
         */
        public function setIsDetailDisplay($is_detail_display = null)
        {
            $this->is_detail_display = $is_detail_display;

            return $this;
        }

        /**
         * Get is_detail_display.
         *
         * @return boolean
         */
        public function getIsDetailDisplay()
        {
            return $this->is_detail_display;
        }

        /**
         * Set name02
         *
         * @param string|null $name02
         *
         * @return ProductBrand
         */
        public function setIsListDisplay($is_list_display = null)
        {
            $this->is_list_display = $is_list_display;

            return $this;
        }

        /**
         * Get is_detail_display.
         *
         * @return boolean
         */
        public function getIsListDisplay()
        {
            return $this->is_list_display;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductBrand
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
         * Add Product.
         *
         * @param \Eccube\Entity\Product $product
         *
         * @return ProductBrand
         */
        public function addProduct(Product $product)
        {
            $this->Product[] = $product;

            return $this;
        }

        /**
         * Remove Product.
         *
         * @param \Eccube\Entity\Product $product
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProduct(Product $product)
        {
            return $this->Product->removeElement($product);
        }

        /**
         * Get Product.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProduct()
        {
            return $this->Product;
        }
    }
}
