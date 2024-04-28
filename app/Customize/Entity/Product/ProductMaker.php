<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;

if (!class_exists('\Customize\Entity\Product\ProductMaker')) {
    /**
     * ProductMaker
     *
     * @ORM\Table(name="dtb_product_maker")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductMakerRepository")
     */
    class ProductMaker extends \Eccube\Entity\AbstractEntity
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
         * メーカー
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="maker_name", type="string", nullable=true, length=32)
         */
        private $maker_name;

        /**
         * @var string
         *
         * @ORM\Column(name="maker_name_2", type="string", nullable=true,  length=32)
         */
        private $maker_name_2;

        /**
         * @var string
         *
         * @ORM\Column(name="link", type="string", nullable=true)
         */
        private $link;

        /**
         * @var string
         *
         * @ORM\Column(name="comment", type="string", nullable=true)
         */
        private $comment;

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
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Customize\Entity\Product\ProductMakerImage" , mappedBy="ProductMaker",cascade={"remove"}),
         */
        private $ProductMakerImage;

        /**
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Product" , mappedBy="ProductMaker",cascade={"remove"}),
         */
        private $Product;

        public function __construct()
        {
            $this->ProductMakerImage = new \Doctrine\Common\Collections\ArrayCollection();
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
         * @return ProductMaker
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
         * Set maker_name
         *
         * @param string|null $maker_name
         *
         * @return ProductMaker
         */
        public function setMakerName($name = null)
        {
            $this->maker_name = $name;

            return $this;
        }

        /**
         * Get maker_name.
         *
         * @return string|null
         */
        public function getMakerName()
        {
            return $this->maker_name;
        }

        /**
         * Set maker_name_2
         *
         * @param string|null $maker_name
         *
         * @return ProductMaker
         */
        public function setMakerName2($name = null)
        {
            $this->maker_name_2 = $name;

            return $this;
        }

        /**
         * Get maker_name_2.
         *
         * @return string|null
         */
        public function getMakerName2()
        {
            return $this->maker_name_2;
        }

        /**
         * Set comment
         *
         * @param string|null $comment
         *
         * @return ProductMaker
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
         * @return ProductMaker
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
         * Set free_comment_1
         *
         * @param string|null $image_url
         *
         * @return ProductMaker
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
         * @return ProductMaker
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
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductMaker
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
         * Set name02
         *
         * @param string|null $name02
         *
         * @return ProductMaker
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
         * @return ProductMaker
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
         * Add productMakerImage.
         *
         * @param \Customize\Entity\Product\ProductMakerImage $productMakerImage
         *
         * @return ProductMaker
         */
        public function addProductMakerImage(ProductMakerImage $productMakerImage)
        {
            $this->ProductMakerImage[] = $productMakerImage;

            return $this;
        }

        /**
         * Remove productMakerImage.
         *
         * @param \Customize\Entity\Product\ProductMakerImage $productMakerImage
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProductMakerImage(ProductMakerImage $productMakerImage)
        {
            return $this->ProductMakerImage->removeElement($productMakerImage);
        }

        /**
         * Get productMakerImage.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProductMakerImage()
        {
            return $this->ProductMakerImage;
        }

        /**
         * Add Product.
         *
         * @param \Eccube\Entity\Product $product
         *
         * @return ProductMaker
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
