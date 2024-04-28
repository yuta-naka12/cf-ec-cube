<?php

namespace Customize\Entity\Product;

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

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Product\ProductMakerImage')) {
    /**
     * ProductMakerImage
     *
     * @ORM\Table(name="dtb_product_maker_image")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductMakerImageRepository")
     */
    class ProductMakerImage extends \Eccube\Entity\AbstractEntity
    {
        /**
         * @return string
         */
        public function __toString()
        {
            return (string) $this->getFileName();
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
         * @var string
         *
         * @ORM\Column(name="file_name", type="string", length=255)
         */
        private $file_name;

        /**
         * @var int
         *
         * @ORM\Column(name="sort_no", type="smallint", options={"unsigned":true})
         */
        private $sort_no;

        /**
         * @var \DateTime
         *
         * @ORM\Column(name="create_date", type="datetimetz")
         */
        private $create_date;

        /**
         * @var \Customize\Entity\Product\ProductMaker
         *
         * @ORM\ManyToOne(targetEntity="Customize\Entity\Product\ProductMaker", inversedBy="ProductMakerImage")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="product_maker_id", referencedColumnName="id")
         * })
         */
        private $ProductMaker;

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
         * Set fileName.
         *
         * @param string $fileName
         *
         * @return ProductMakerImage
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
         * Set sortNo.
         *
         * @param int $sortNo
         *
         * @return ProductMakerImage
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
         * @return ProductMakerImage
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
         * Set productMaker.
         *
         * @param \Customize\Entity\Product\ProductMaker|null $productMaker
         *
         * @return ProductMakerImage
         */
        public function setProductMaker(ProductMaker $productMaker = null)
        {
            $this->ProductMaker = $productMaker;

            return $this;
        }

        /**
         * Get productMaker.
         *
         * @return \Customize\Entity\Product\ProductMaker|null
         */
        public function getProductMaker()
        {
            return $this->ProductMaker;
        }
    }
}
