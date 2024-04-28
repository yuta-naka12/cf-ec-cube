<?php

namespace Customize\Entity\Customer;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Payment;

if (!class_exists('\Customize\Entity\Customer\CustomerClass')) {
    /**
     * ProductBrand
     *
     * @ORM\Table(name="dtb_customer_class")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductClassRepository")
     */
    class CustomerClass extends \Eccube\Entity\AbstractEntity
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
         * ブランド
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Payment", mappedBy="CustomerClass", cascade={"persist","remove"})
         */
        private $PaymentMethods;

        /**
         * @var integer
         *
         * @ORM\Column(name="partition_rate", type="integer", nullable=true, length=3)
         */
        private $partition_rate;

        /**
         * @var integer
         *
         * @ORM\Column(name="status", type="integer", nullable=true,  length=32)
         */
        private $status;

        /**
         * 索引
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
         * Set name
         *
         * @param string|null $name
         *
         * @return CustomerClass
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
         * Add paymentMethods.
         *
         * @param \Eccube\Entity\Payment $productCategory
         *
         * @return Payment
         */
        public function addPayment(Payment $payment)
        {
            $this->PaymentMethods[] = $payment;

            return $this;
        }

        /**
         * Remove paymentMethods.
         *
         * @param \Eccube\Entity\Payment $productCategory
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removePayment(Payment $payment)
        {
            return $this->PaymentMethods->removeElement($payment);
        }

        /**
         * Get paymentMethods.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getPaymentMethods()
        {
            return $this->PaymentMethods;
        }

        /**
         * Set partition_rate
         *
         * @param string|null $partition_rate
         *
         * @return CustomerClass
         */
        public function setPartitionRate($value = null)
        {
            $this->partition_rate = $value;

            return $this;
        }

        /**
         * Get partition_rate.
         *
         * @return string|null
         */
        public function getPartitionRate()
        {
            return $this->partition_rate;
        }

        /**
         * Set status
         *
         * @param string|null $status
         *
         * @return CustomerClass
         */
        public function setStatus($value = null)
        {
            $this->status = $value;

            return $this;
        }

        /**
         * Get status.
         *
         * @return string|null
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
         * @return CustomerClass
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
