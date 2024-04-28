<?php

namespace Customize\Entity\Voice;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Category;

if (!class_exists('\Customize\Entity\Setting\Voice')) {
    /**
     * PurchaseGroup
     *
     * @ORM\Table(name="dtb_voice")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\VoiceRepository")
     */
    class Voice extends \Eccube\Entity\AbstractEntity
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
         * 購入グループ名
         * @var string
         *
         * @ORM\Column(name="title", type="string", nullable=false, length=255)
         */
        private $title;

        /**
         * 配送手段
         * @var string
         *
         * @ORM\Column(name="customer_initials", type="string", nullable=false, length=5)
         */
        private $customer_initials;

        /**
         * 配送分割有無
         * @var string
         *
         * @ORM\Column(name="content", type="string", nullable=false)
         */
        private $content;

        /**
         *  送り主変更有無
         * @var image_path="/"
         *
         * @ORM\Column(name="image_path", type="string", nullable=true)
         */
        private $image_path;


       
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
         * Set id
         *
         * @param int|null $value
         *
         * @return Voice
         */
        public function setId($value = null)
        {
            $this->id = $value;

            return $this;
        }

        /**
         * Set title
         *
         * @param string|null $value
         *
         * @return Voice
         */
        public function setTitle($value = null)
        {
            $this->title = $value;

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
         * Set customer_initials
         *
         * @param string|null $value
         *
         * @return Voice
         */
        public function setCustomerInitials($value = null)
        {
            $this->customer_initials = $value;

            return $this;
        }

        /**
         * Get dcustomer_initials.
         *
         * @return string|null
         */
        public function getCustomerInitials()
        {
            return $this->customer_initials;
        }

        /**
         * Set content
         *
         * @param string|null $value
         *
         * @return Voice
         */
        public function setContent($value = null)
        {
            $this->content = $value;

            return $this;
        }

        /**
         * Get content.
         *
         * @return string|null
         */
        public function getContent()
        {
            return $this->content;
        }

        /**
         * Set image_path
         *
         * @param string|null $value
         *
         * @return Voice
         */
        public function setImagePath($value = null)
        {
            $this->image_path = $value;

            return $this;
        }

        /**
         * Get image_path.
         *
         * @return string|null
         */
        public function getImagePath()
        {
            return $this->image_path;
        }

       
    }
}
