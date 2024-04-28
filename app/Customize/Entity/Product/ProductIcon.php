<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;

if (!class_exists('\Customize\Entity\Product\ProductIcon')) {
    /**
     * ProductIcon
     *
     * @ORM\Table(name="dtb_product_icon")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductIconRepository")
     */
    class ProductIcon extends \Eccube\Entity\AbstractEntity
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
         * アイコン
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="icon_name", type="string", nullable=true, length=32)
         */
        private $icon_name;

        /**
         * @var string
         *
         * @ORM\Column(name="file_name", type="string", length=255 ,nullable=true)
         */
        private $file_name;


        /**
         * @var string
         *
         * @ORM\Column(name="comment", type="string", nullable=true)
         */
        private $comment;

        /**
         * @var int
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="create_date", type="datetimetz", nullable=true)
         */
        private $create_date;

        /**
         * @var \Eccube\Entity\Product
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Product", inversedBy="ProductIcon")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="product_id", referencedColumnName="id")
         * })
         */
        private $Product;

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
         * @return ProductIcon
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
         * Set icon_name
         *
         * @param string|null $icon_name
         *
         * @return ProductIcon
         */
        public function setIconName($name = null)
        {
            $this->icon_name = $name;

            return $this;
        }

        /**
         * Get icon_name.
         *
         * @return string|null
         */
        public function getIconName()
        {
            return $this->icon_name;
        }

        /**
         * Set fileName.
         *
         * @param string $fileName
         *
         * @return ProductIcon
         */
        public function setFileName($fileName)
        {
            $this->file_name = $fileName;

            return $this;
        }

        /**
         * Get fileName.
         *
         * @return string
         */
        public function getFileName()
        {
            return $this->file_name;
        }


        /**
         * Set comment
         *
         * @param string|null $comment
         *
         * @return ProductIcon
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
         * Set sortNo.
         *
         * @param int $sortNo
         *
         * @return ProductIcon
         */
        public function setSortNo($sortNo)
        {
            $this->sort_no = $sortNo;

            return $this;
        }

        /**
         * Get sortNo.
         *
         * @return int
         */
        public function getSortNo()
        {
            return $this->sort_no;
        }

        /**
         * Set createDate.
         *
         * @param \DateTime $createDate
         *
         * @return ProductIcon
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
         * Set Product.
         *
         * @param \Eccube\Entity\Product|null $product
         *
         * @return ProductIcon
         */
        public function setProduct(Product $product = null)
        {
            $this->Product = $product;

            return $this;
        }

        /**
         * Get product.
         *
         * @return \Eccube\Entity\Product|null
         */
        public function getProduct()
        {
            return $this->Product;
        }
    }
}
