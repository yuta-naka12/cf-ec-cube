<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Member;

if (!class_exists('\Customize\Entity\Product\ProductClassSearchTemplate')) {
    /**
     * Sender
     *
     * @ORM\Table(name="dtb_product_class_search_template")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductClassSearchTemplateRepository")
     */
    class ProductClassSearchTemplate extends \Eccube\Entity\AbstractEntity
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
         * @ORM\Column(name="name", type="string", length=255, nullable=true)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="search_contents", type="text", length=65535, nullable=true)
         */
        private $search_contents;

        /**
         * @var \Eccube\Entity\Member
         *
         * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member", inversedBy="ProductSearchTemplate")
         * @ORM\JoinColumns({
         *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
         * })
         */
        private $Member;

        /**
         * Set id.
         *
         * @param string $id
         *
         * @return ProductClassSearchTemplate
         */
        public function setId($id)
        {
            $this->id = $id;

            return $this;
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
         * Set name.
         *
         * @param string $name
         *
         * @return ProductClassSearchTemplate
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
         * Set search_contents.
         *
         * @param string|null $value
         *
         * @return ProductClassSearchTemplate
         */
        public function setSearchContents($value = null)
        {
            $this->search_contents = $value;

            return $this;
        }

        /**
         * Get search_contents.
         *
         * @return string|null
         */
        public function getSearchContents()
        {
            return $this->search_contents;
        }

        /**
         * Set Member.
         *
         * @param Member|null $value
         *
         * @return ProductClassSearchTemplate
         */
        public function setMember($value = null)
        {
            $this->Member = $value;

            return $this;
        }

        /**
         * Get Member.
         *
         * @return Member|null
         */
        public function getMember()
        {
            return $this->Member;
        }
    }
}
