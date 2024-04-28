<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;

if (!class_exists('\Customize\Entity\Product\ProductGift')) {
    /**
     * ProductGift
     *
     * @ORM\Table(name="dtb_product_gift")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductGiftRepository")
     */
    class ProductGift extends \Eccube\Entity\AbstractEntity
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
         * ギフト
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="gift_name", type="string", nullable=true)
         */
        private $gift_name;

        /**
         * @var string
         *
         * @ORM\Column(name="comment", type="string", nullable=true)
         */
        private $comment;

        /**
         * @var string
         *
         * @ORM\Column(name="note", type="string", nullable=true)
         */
        private $note;

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
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Product", mappedBy="ProductGift")
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
         * Set gift_name
         *
         * @param string|null $gift_name
         *
         * @return ProductGift
         */
        public function setGiftName($name = null)
        {
            $this->gift_name = $name;

            return $this;
        }

        /**
         * Get gift_name.
         *
         * @return string|null
         */
        public function getGiftName()
        {
            return $this->gift_name;
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
         * Set note
         *
         * @param string|null $note
         *
         * @return ProductMaker
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
         * @return ProductGift
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
         * @return ProductGift
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
