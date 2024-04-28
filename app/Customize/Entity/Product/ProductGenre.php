<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Category;
use Doctrine\Common\Collections\ArrayCollection;

if (!class_exists('\Customize\Entity\Product\ProductGenre')) {
    /**
     * ProductGenre
     *
     * @ORM\Table(name="dtb_product_genre")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductGenreRepository")
     */
    class ProductGenre extends \Eccube\Entity\AbstractEntity
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
         * ジャンル
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true, length=32)
         */
        private $name;

        /**
         * ジャンル階層
         * @var string
         *
         * @ORM\Column(name="genre_hierarchy", type="string", nullable=true, length=32)
         */
        private $genre_hierarchy;

        /**
         *　ジャンル名
         * @var string
         *
         * @ORM\Column(name ="genre_name", type="string", nullable=true)
         */
        private $genre_name;

        /**
         *　ジャンル名2
         * @var string
         *
         * @ORM\Column(name ="genre_name_2", type="string", nullable=true)
         */
        private $genre_name_2;

        /**
         * コメント
         * @var string
         *
         * @ORM\Column(name="comment", type="string", nullable=true)
         */
        private $comment;

        /**
         * 商品表示モードセレクトボックス
         * @var string
         *
         * @ORM\Column(name="display_mode_default", type="string", nullable=true)
         */
        private $display_mode_default;

        /**
         * 商品表示モードチェックボックス
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\ManyToMany(targetEntity="Customize\Entity\Product\ProductGenreDisplayMode", mappedBy = "ProductGenre")
         */
        private $ProductGenreDisplayMode;

        /**
         * フリースペース上部
         * @var string
         *
         * @ORM\Column(name="free_space_top", type="string", nullable=true)
         */
        private $free_space_top;

        /**
         * フリ＝スペース下部
         * @var string
         *
         * @ORM\Column(name="free_space_bottom", type="string", nullable=true)
         */
        private $free_space_bottom;

        /**
         * 状態
         * @var string
         *
         * @ORM\Column(name="status", type="string", nullable=true)
         */
        private $status;

        /**
         * 並び順
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

        public function __construct()
        {
            $this->ProductGenreDisplayMode = new ArrayCollection();
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
         * @return ProductGenre
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
         * Set genre_hierarchy
         *
         * @param string|null $genre_hierarchy
         *
         * @return ProductGenre
         */
        public function setGenreHierarchy($value = null)
        {
            $this->genre_hierarchy = $value;

            return $this;
        }

        /**
         * Get genre_hierarchy.
         *
         * @return string|null
         */
        public function getGenreHierarchy()
        {
            return $this->genre_hierarchy;
        }

        /**
         * Set genre_name
         *
         * @param string|null $genre_name
         *
         * @return ProductGenre
         */
        public function setGenreName($value = null)
        {
            $this->genre_name = $value;

            return $this;
        }

        /**
         * Get genre_name.
         *
         * @return string|null
         */
        public function getGenreName()
        {
            return $this->genre_name;
        }

        /**
         * Set genre_name_2
         *
         * @param string|null $genre_name_2
         *
         * @return ProductGenre
         */
        public function setGenreName2($value = null)
        {
            $this->genre_name_2 = $value;

            return $this;
        }

        /**
         * Get genre_name_2.
         *
         * @return string|null
         */
        public function getGenreName2()
        {
            return $this->genre_name_2;
        }

        /**
         * Set comment
         *
         * @param string|null $comment
         *
         * @return ProductGenre
         */
        public function setComment($value = null)
        {
            $this->comment = $value;

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
         * Set display_mode_default
         *
         * @param string|null $display_mode_default
         *
         * @return ProductGenre
         */
        public function setDisplayModeDefault($value = null)
        {
            $this->display_mode_default = $value;

            return $this;
        }

        /**
         * Get display_mode_default.
         *
         * @return string|null
         */
        public function getDisplayModeDefault()
        {
            return $this->display_mode_default;
        }

        /**
         * Add DisplayMode.
         *
         * @param \Customize\Entity\Product\ProductGenreDisplayMode $displayMode
         * 
         * @return ProductGenre
         */
        public function addProductGenreDisplayMode(ProductGenreDisplayMode $displayMode)
        {
            $this->ProductGenreDisplayMode[] = $displayMode;

            return $this;
        }

        /**
         * Remove DisplayMode.
         *
         * @param \Customize\Entity\Product\ProductGenreDisplayMode $displayMode
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProductGenreDisplayMode(ProductGenreDisplayMode $displayMode)
        {
            return $this->ProductGenreDisplayMode->removeElement($displayMode);
        }

        /**
         * Get DisplayMode.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProductGenreDisplayMode()
        {
            return $this->ProductGenreDisplayMode;
        }

        /**
         * Set free_space_top
         *
         * @param string|null $free_space_top
         *
         * @return ProductGenre
         */
        public function setFreeSpaceTop($value = null)
        {
            $this->free_space_top = $value;

            return $this;
        }

        /**
         * Get free_space_top.
         *
         * @return string|null
         */
        public function getFreeSpaceTop()
        {
            return $this->free_space_top;
        }

        /**
         * Set free_space_bottom
         *
         * @param string|null $free_space_bottom
         *
         * @return ProductGenre
         */
        public function setFreeSpaceBottom($value = null)
        {
            $this->free_space_bottom = $value;

            return $this;
        }

        /**
         * Get free_space_bottom.
         *
         * @return string|null
         */
        public function getFreeSpaceBottom()
        {
            return $this->free_space_bottom;
        }

        /**
         * Set status
         *
         * @param integer|null $status
         *
         * @return ProductGenre
         */
        public function setStatus($value = null)
        {
            $this->status = $value;

            return $this;
        }

        /**
         * Get status.
         *
         * @return integer|null
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductGenre
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
    }
}
