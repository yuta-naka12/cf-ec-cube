<?php

namespace Customize\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;
use Symfony\Component\Validator\Constraints\Date;

if (!class_exists('\Customize\Entity\Shipping\ShippingGroup')) {
    /**
     * ProductMaker
     *
     * @ORM\Table(name="dtb_shipping_group")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\Shipping\ShippingGroupRepository")
     */
    class ShippingGroup extends \Eccube\Entity\AbstractEntity
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
         * @var integer
         *
         * @ORM\Column(name="status", type="integer", nullable=true)
         */
        private $status;

        /**
         * @var \DateTime|null
         *
         * @ORM\Column(name="created_at", type="datetimetz", nullable=true)
         */
        private $created_at;

        /**
         * Get id.
         *
         * @return integer
         */
        public function getId()
        {
            return $this->id;
        }

        /**
         * Set id
         *
         * @param integer|null $value
         *
         * @return ShippingGroup
         */
        public function setId($value = null)
        {
            $this->id = $value;

            return $this;
        }

        /**
         * Get status.
         *
         * @return integer
         */
        public function getStatus()
        {
            return $this->status;
        }

        /**
         * Set status
         *
         * @param integer|null $value
         *
         * @return ShippingGroup
         */
        public function setStatus($value = null)
        {
            $this->status = $value;

            return $this;
        }


        /**
         * Get created_at.
         *
         * @return \DateTime|null
         */
        public function getCreatedAt()
        {
            return $this->created_at;
        }

        /**
         * Set created_at
         *
         * @param \DateTime|null $value
         *
         * @return ShippingGroup
         */
        public function setCreatedAt($value = null)
        {
            $this->created_at = $value;

            return $this;
        }
    }
}
