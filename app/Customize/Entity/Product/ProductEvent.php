<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Category;

if (!class_exists('\Customize\Entity\Product\ProductEvent')) {
    /**
     * ProductBrand
     *
     * @ORM\Table(name="dtb_product_event")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductEventRepository")
     */
    class ProductEvent extends \Eccube\Entity\AbstractEntity
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
         * イベントID
         * @var string
         *
         * @ORM\Column(name="event_id", type="string", nullable=true, length=32)
         */
        private $event_id;

        /**
         * イベント名
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true, length=32)
         */
        private $name;

        /**
         * カテゴリーID
         * @var string
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Category")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="category_id", referencedColumnName="id")
         * })
         */
        private $category_id;

        /**
         * イベント開始日
         * @var \DateTime
         *
         * @ORM\Column(name="started_at", type="date", nullable=true)
         */
        private $started_at;

        /**
         * イベント終了日
         * @var \DateTime
         *
         * @ORM\Column(name="ended_at", type="date", nullable=true)
         */
        private $ended_at;

        /**
         * リンク
         * @var string
         *
         * @ORM\Column(name="link", type="string", nullable=true)
         */
        private $link;

        /**
         * 商品表示モード
         * @var string
         *
         * @ORM\Column(name="display_mode", type="string", nullable=true)
         */
        private $display_mode;

        /**
         * LPO表示
         * @var string
         *
         * @ORM\Column(name="is_lpo_display", type="boolean", nullable=true)
         */
        private $is_lpo_display;

        /**
         * キーワード
         * @var string
         *
         * @ORM\Column(name="keyword", type="string", nullable=true)
         */
        private $keyword;

        /**
         * イベント表示位置
         * @var string
         *
         * @ORM\Column(name="display_position", type="string", nullable=true)
         */
        private $display_position;

        /**
         * 表示最大数
         * @var integer
         *
         * @ORM\Column(name="display_maximum_number", type="integer", nullable=true)
         */
        private $display_maximum_number;

        /**
         * 状態
         * @var string
         *
         * @ORM\Column(name="status", type="string", nullable=true)
         */
        private $status;

        /**
         * 絞り込みオプション
         * @var string
         *
         * @ORM\Column(name="sort_option", type="string", nullable=true)
         */
        private $sort_option;

        /**
         * コメント
         * @var string
         *
         * @ORM\Column(name="comment", type="string", nullable=true)
         */
        private $comment;

        /**
         * フリーコメント1
         * @var string
         *
         * @ORM\Column(name="free_comment_1", type="string", nullable=true)
         */
        private $free_comment_1;

        /**
         * フリーコメント2
         * @var string
         *
         * @ORM\Column(name="free_comment_2", type="string", nullable=true)
         */
        private $free_comment_2;

        /**
         * フリーコメント3
         * @var string
         *
         * @ORM\Column(name="free_comment_3", type="string", nullable=true)
         */
        private $free_comment_3;

        /**
         * フリーコメント4
         * @var string
         *
         * @ORM\Column(name="free_comment_4", type="string", nullable=true)
         */
        private $free_comment_4;

        /**
         * フリーコメント5
         * @var string
         *
         * @ORM\Column(name="free_comment_5", type="string", nullable=true)
         */
        private $free_comment_5;

        /**
         * 並び順
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

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
         * Set event_id
         *
         * @param string|null $value
         *
         * @return ProductEvent
         */
        public function setEventId($value = null)
        {
            $this->event_id = $value;

            return $this;
        }

        /**
         * Get event_id.
         *
         * @return string|null
         */
        public function getEventId()
        {
            return $this->event_id;
        }

        /**
         * Set category_id
         *
         * @param string|null $category_id
         *
         * @return Category
         */
        public function setCategory($value = null)
        {
            $this->category_id = $value;

            return $this;
        }

        /**
         * Get category_id.
         *
         * @return integer|null
         */
        public function getCategory()
        {
            return $this->category_id;
        }

        /**
         * Set name
         *
         * @param string|null $name
         *
         * @return ProductEvent
         */
        public function setName($name = null)
        {
            $this->name = $name;

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
         * Set started_at
         *
         * @param string|null $started_at
         *
         * @return ProductEvent
         */
        public function setStartedAt($name = null)
        {
            $this->started_at = $name;

            return $this;
        }

        /**
         * Get started_at.
         *
         * @return string|null
         */
        public function getStartedAt()
        {
            return $this->started_at;
        }

        /**
         * Set ended_at
         *
         * @param string|null $ended_at
         *
         * @return ProductEvent
         */
        public function setEndedAt($name = null)
        {
            $this->ended_at = $name;

            return $this;
        }

        /**
         * Get ended_at.
         *
         * @return string|null
         */
        public function getEndedAt()
        {
            return $this->ended_at;
        }

        /**
         * Set link
         *
         * @param string|null $link
         *
         * @return ProductEvent
         */
        public function setLink($value = null)
        {
            $this->link = $value;

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
         * Set display_mode
         *
         * @param string|null $display_mode
         *
         * @return ProductEvent
         */
        public function setDisplayMode($value = null)
        {
            $this->display_mode = $value;

            return $this;
        }

        /**
         * Get display_mode.
         *
         * @return string|null
         */
        public function getDisplayMode()
        {
            return $this->display_mode;
        }

        /**
         * Set is_lpo_display
         *
         * @param string|null $value
         *
         * @return ProductEvent
         */
        public function setIsLpoDisplay($value = null)
        {
            $this->is_lpo_display = $value;

            return $this;
        }

        /**
         * Get is_lpo_display.
         *
         * @return string|null
         */
        public function getIsLpoDisplay()
        {
            return $this->is_lpo_display;
        }

        /**
         * Set keyword
         *
         * @param string|null $keyword
         *
         * @return ProductEvent
         */
        public function setKeyword($value = null)
        {
            $this->keyword = $value;

            return $this;
        }

        /**
         * Get keyword.
         *
         * @return string|null
         */
        public function getKeyword()
        {
            return $this->keyword;
        }

        /**
         * Set display_position
         *
         * @param string|null $display_position
         *
         * @return ProductEvent
         */
        public function setDisplayPosition($value = null)
        {
            $this->display_position = $value;

            return $this;
        }

        /**
         * Get display_position.
         *
         * @return string|null
         */
        public function getDisplayPosition()
        {
            return $this->display_position;
        }

        /**
         * Set display_maximum_number
         *
         * @param integer|null $display_maximum_number
         *
         * @return ProductEvent
         */
        public function setDisplayMaximumNumber($value = null)
        {
            $this->display_maximum_number = $value;

            return $this;
        }

        /**
         * Get display_maximum_number.
         *
         * @return integer|null
         */
        public function getDisplayMaximumNumber()
        {
            return $this->display_maximum_number;
        }

        /**
         * Set status
         *
         * @param integer|null $status
         *
         * @return ProductEvent
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
         * Set sort_option
         *
         * @param string|null $sort_option
         *
         * @return ProductEvent
         */
        public function setSortOption($value = null)
        {
            $this->sort_option = $value;

            return $this;
        }

        /**
         * Get sort_option.
         *
         * @return string|null
         */
        public function getSortOption()
        {
            return $this->sort_option;
        }

        /**
         * Set comment
         *
         * @param string|null $comment
         *
         * @return ProductEvent
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
         * Set free_comment_1
         *
         * @param string|null $free_comment_1
         *
         * @return ProductEvent
         */
        public function setFreeComment1($value = null)
        {
            $this->free_comment_1 = $value;

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
         * @param string|null $free_comment_2
         *
         * @return ProductEvent
         */
        public function setFreeComment2($value = null)
        {
            $this->free_comment_2 = $value;

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
         * Set free_comment_3
         *
         * @param string|null $free_comment_3
         *
         * @return ProductEvent
         */
        public function setFreeComment3($value = null)
        {
            $this->free_comment_3 = $value;

            return $this;
        }

        /**
         * Get free_comment_3.
         *
         * @return string|null
         */
        public function getFreeComment3()
        {
            return $this->free_comment_3;
        }

        /**
         * Set free_comment_4
         *
         * @param string|null $free_comment_4
         *
         * @return ProductEvent
         */
        public function setFreeComment4($value = null)
        {
            $this->free_comment_4 = $value;

            return $this;
        }

        /**
         * Get free_comment_4.
         *
         * @return string|null
         */
        public function getFreeComment4()
        {
            return $this->free_comment_4;
        }

        /**
         * Set free_comment_5
         *
         * @param string|null $free_comment_5
         *
         * @return ProductEvent
         */
        public function setFreeComment5($value = null)
        {
            $this->free_comment_5 = $value;

            return $this;
        }

        /**
         * Get free_comment_5.
         *
         * @return string|null
         */
        public function getFreeComment5()
        {
            return $this->free_comment_5;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductEvent
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
