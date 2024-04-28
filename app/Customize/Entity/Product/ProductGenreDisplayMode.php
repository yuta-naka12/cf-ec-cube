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

namespace Customize\Entity\Product;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Product\ProductGenreDisplayMode')) {
    /**
     * ProductGenreDisplayMode
     *
     * @ORM\Table(name="dtb_product_genre_display_mode")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductGenreDisplayModeRepository")
     */
    class ProductGenreDisplayMode extends \Eccube\Entity\AbstractEntity
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
         * @var string
         *
         * @ORM\Column(name="name", type="string", length=255)
         */
        private $name;

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
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\ManyToMany(targetEntity="Customize\Entity\Product\ProductGenre",inversedBy="ProductGenreDisplayMode")
         * @ORM\JoinTable(name="dtb_product_genres_display_modes")
         * 
         */
        private $ProductGenre;
        

        public function __construct()
        {
            $this->ProductGenre = new ArrayCollection();
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
         * Set id.
         * @param string $id
         *
         * @return ProductGenreDisplayMode
         */
        public function setId($id)
        {
            return $this->id = $id;
        }

        /**
         * Set name.
         *
         * @param string $name
         *
         * @return ProductGenreDisplayMode
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
         * Set sortNo.
         *
         * @param int $sortNo
         *
         * @return ProductGenreDisplayMode
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
         * @return ProductGenreDisplayMode
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
         * Add productGenre.
         *
         * @param \Customize\Entity\Product\ProductGenre|null $productGenre
         *
         * @return ProductGenreDisplayMode
         */
        public function addProductGenre(ProductGenre $productGenre = null)
        {
            $this->ProductGenre[] = $productGenre;

            return $this;
        }

        /**
         * Remove productGenre.
         *
         * @param \Customize\Entity\Product\ProductGenre $productGenre
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProductGenre(ProductGenre $productGenre)
        {
            return $this->ProductGenre->removeElement($productGenre);
        }

        /**
         * Get productGenre.
         *
         * @return \Customize\Entity\Product\ProductGenre|null
         */
        public function getProductGenre()
        {
            return $this->ProductGenre;
        }
    }
}
