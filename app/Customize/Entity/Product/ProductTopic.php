<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;

if (!class_exists('\Customize\Entity\Product\ProductTopic')) {
    /**
     * ProductMaker
     *
     * @ORM\Table(name="dtb_product_Topic")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Customize\Repository\Admin\Product\ProductTopicRepository")
     */
    class ProductTopic extends \Eccube\Entity\AbstractEntity 
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
         * @var \DateTime|null
         *
         * @ORM\Column(name="start_publication_date", type="datetimetz", nullable=true)
         */
        private $start_publication_date;

          /**
         * @var \DateTime|null
         *
         * @ORM\Column(name="end_publication_date", type="datetimetz", nullable=true)
         */
        private $end_publication_date;

           /**
         * メーカー
         * @var string
         *
         * @ORM\Column(name="title", type="string", nullable=true)
         */
        private $title;

        /**
         * メーカー
         * @var string
         *
         * @ORM\Column(name="link_url", type="string", nullable=true)
         */
        private $link_url;


         /**
         * @var boolean
         *
         * @ORM\Column(name="is_target_blank", type="boolean", nullable=true)
         */
        private $is_target_blank;

          /**
         * メーカー
         * @var string
         *
         * @ORM\Column(name="description", type="string", nullable=true)
         */
        private $description;

        /**
         * 索引
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;


        /**
         * @var \Eccube\Entity\Category 
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Category", inversedBy="ProductTopic")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="category", referencedColumnName="id")
         * })
         */
        private $category;

     

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
         * Set start_publication_date
         *
         * @param \DateTime|null $start_publication_date
         *
         * @return ProductTopic
         */
        public function setStartPublicationDate($start_publication_date)
        {
            $this->start_publication_date = $start_publication_date;

            return $this;
        }

        /**
         * Get start_publication_date
         *
         * @return \DateTime|null
         */
        public function getStartPublicationDate()
        {
            return $this->start_publication_date;
        }

        /**
         * Set end_publication_date
         *
         * @param \DateTime|null $end_publication_date
         *
         * @return ProductTopic
         */
        public function setEndPublicationDate($end_publication_date)
        {
            $this->end_publication_date = $end_publication_date;

            return $this;
        }

        /**
         * Get end_publication_date
         *
         * @return \DateTime|null
         */
        public function getEndPublicationDate()
        {
            return $this->end_publication_date;
        }

   

        /**
         * Set title
         *
         * @param string|null $title
         *
         * @return ProductTopic
         */
        public function setTitle($title = null)
        {
            $this->title = $title;

            return $this;
        }

        /**
         * Get title.
         *
         * @return string|null
         */
        public function getTitle()
        {
            return $this->title;
        }

                /**
         * Set link_url
         *
         * @param string|null $link_url
         *
         * @return ProductTopic
         */
        public function setLinkUrl($link_url = null)
        {
            $this->link_url = $link_url;

            return $this;
        }

        /**
         * Get link_url.
         *
         * @return string|null
         */
        public function getLinkUrl()
        {
            return $this->link_url;
        }

                /**
         * Set is_target_blank
         *
         * @param boolean|null $is_target_blank
         *
         * @return ProductTopic
         */
        public function setIsTargetBlank($is_target_blank = null)
        {
            $this->is_target_blank = $is_target_blank;

            return $this;
        }

        /**
         * Get is_target_blank
         *
         * @return boolean|null
         */
        public function getIsTargetBlank()
        {
            return $this->is_target_blank;
        }

                        /**
         * Set description
         *
         * @param string|null $description
         *
         * @return ProductTopic
         */
        public function setDescription($description = null)
        {
            $this->description = $description;

            return $this;
        }

        /**
         * Get description.
         *
         * @return string|null
         */
        public function getDescription()
        {
            return $this->description;
        }

         /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductTopic
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
         * Add Category.
         *
         * @param \Eccube\Entity\Category $category
         *
         * @return ProductTopic
         */
        public function addProduct(Category $category)
        {
            $this->Category[] = $category;

            return $this;
        }

        /**
         * Remove Category.
         *
         * @param \Eccube\Entity\Category $category
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProduct(Category $category)
        {
            return $this->Category->removeElement($category);
        }

        /**
         * Set Category.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function setCategory($category)
        {
            return $this->category = $category;
        }

        /**
         * Get Category.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getCategory()
        {
            return $this->category;
        }
       
    }
}
