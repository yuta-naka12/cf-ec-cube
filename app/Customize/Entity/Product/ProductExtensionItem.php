<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists('\Customize\Entity\Product\ProductExtensionItem')) {
    /**
     * ProductExtensionItem
     *
     * @ORM\Table(name="dtb_product_extension_item")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductExtensionItemRepository")
     */
    class ProductExtensionItem extends \Eccube\Entity\AbstractEntity
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
         * 仕様
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
         
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="specification_name", type="string", nullable=true)
         */
        private $specification_name;




        // Additon of new fields 
        /**
         * @var integer
         *
         * @ORM\Column(name="gender_id", type="integer", nullable=true)
         */
        private $gender_id;

        /**
         * @var string
         *
         * @ORM\Column(name="reason_withdrawal", type="string", nullable=true)
         */
        private $reason_withdrawal;

        /**
         * @var integer
         *
         * @ORM\Column(name="ec_site_linked_classification_id", type="integer", nullable=true)
         */
        private $ec_site_linked_classification_id;

        /**
         * @var integer
         *
         * @ORM\Column(name="web_order_permission_classification_id", type="integer", nullable=true)
         */
        private $web_order_permission_classification_id;

        /**
         * @var integer
         *
         * @ORM\Column(name="ordering_time", type="integer", nullable=true)
         */
        private $ordering_time;

        /**
         * @var string
         *
         * @ORM\Column(name="deposit_box", type="string", nullable=true)
         */
        private $deposit_box;

        /**
         * @var integer
         *
         * @ORM\Column(name="delivery_good_group", type="integer", nullable=true)
         */
        private $delivery_good_group;

        /**
         * @var integer
         *
         * @ORM\Column(name="classification_shipping_cost_calculation", type="integer", nullable=true)
         */
        private $classification_shipping_cost_calculation;

        /**
         * @var integer
         *
         * @ORM\Column(name="filling_control_table_output_classification", type="integer", nullable=true)
         */
        private $filling_control_table_output_classification;

        /**
         * @var integer
         *
         * @ORM\Column(name="repack_classification", type="integer", nullable=true)
         */
        private $repack_classification;

        /**
         * @var integer
         *
         * @ORM\Column(name="processed_product_category_id", type="integer", nullable=true)
         */
        private $processed_product_category_id;

        /**
         * @var integer
         *
         * @ORM\Column(name="defrosting_method_id", type="integer", nullable=true)
         */
        private $defrosting_method_id;

        /**
         * @var integer
         *
         * @ORM\Column(name="impoverished_area_id", type="integer", nullable=true)
         */
        private $impoverished_area_id;

        /**
         * @var integer
         *
         * @ORM\Column(name="pref_id", type="integer", nullable=true)
         */
        private $pref_id;

        /**
         * @var integer
         *
         * @ORM\Column(name="track_no", type="integer", nullable=true)
         */
        private $track_no;


        /**
         * @var boolean
         *
         * @ORM\Column(name="is_description_page", type="boolean", nullable=true)
         */
        private $is_description_page;

        /**
         * 索引
         * @var integer
         *
         * @ORM\Column(name="sort_no", type="integer", nullable=true)
         */
        private $sort_no;

        /**
         * @var boolean
         *
         * @ORM\Column(name="is_detail_display", type="boolean", nullable=true)
         */
        private $is_detail_display;

        /**
         * @var boolean
         *
         * @ORM\Column(name="is_list_display", type="boolean", nullable=true)
         */
        private $is_list_display;

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
         * @return ProductExtensionItem
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
         * Set specification_name
         *
         * @param string|null $specification_name
         *
         * @return ProductExtensionItem
         */
        public function setSpecificationName($name = null)
        {
            $this->specification_name = $name;

            return $this;
        }

        /**
         * Get specification_name.
         *
         * @return string|null
         */
        public function getSpecificationName()
        {
            return $this->specification_name;
        }

        /**
         * Set is_description_page
         *
         * @param boolean|null $is_description_page
         *
         * @return ProductExtensionItem
         */
        public function setIsDescriptionPage($value = null)
        {
            $this->is_description_page = $value;

            return $this;
        }

        /**
         * Get is_description_page.
         *
         * @return boolean|null
         */
        public function getIsDescriptionPage()
        {
            return $this->is_description_page;
        }

        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductExtensionItem
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
         * Set name02
         *
         * @param string|null $name02
         *
         * @return ProductExtensionItem
         */
        public function setIsDetailDisplay($is_detail_display = null)
        {
            $this->is_detail_display = $is_detail_display;

            return $this;
        }

        /**
         * Get is_detail_display.
         *
         * @return boolean
         */
        public function getIsDetailDisplay()
        {
            return $this->is_detail_display;
        }

        /**
         * Set name02
         *
         * @param string|null $name02
         *
         * @return ProductExtensionItem
         */
        public function setIsListDisplay($is_list_display = null)
        {
            $this->is_list_display = $is_list_display;

            return $this;
        }

        /**
         * Get is_detail_display.
         *
         * @return boolean
         */
        public function getIsListDisplay()
        {
            return $this->is_list_display;
        }


        /**
         * Set Gender Id
         *
         * @param integer|null $gender_id
         *
         * @return ProductExtensionItem
         */
        public function setGenderId($value = null)
        {
            $this->gender_id = $value;

            return $this;
        }

        /**
         * Get Gender Id
         *
         * @return integer|null
         */
        public function getGenderId()
        {
            return $this->gender_id;
        }


        /**
         * Set reason_withdrawal
         *
         * @param string|null $reason_withdrawal
         *
         * @return ProductExtensionItem
         */
        public function setReasonWithdrawal($value = null)
        {
            $this->reason_withdrawal = $value;

            return $this;
        }
        /**
         * Get reason_withdrawal
         *
         * @return string|null
         */
        public function getReasonWithdrawal()
        {
            return $this->reason_withdrawal;
        }

        /**
         * Set ec_site_linked_classification_id
         *
         * @param integer|null $ec_site_linked_classification_id
         *
         * @return ProductExtensionItem
         */
        public function setEcSiteLinkedClassificationId($value = null)
        {
            $this->ec_site_linked_classification_id = $value;

            return $this;
        }
        /**
         * Get ec_site_linked_classification_id
         *
         * @return integer|null
         */
        public function getEcSiteLinkedClassificationId()
        {
            return $this->ec_site_linked_classification_id;
        }

        /**
         * Set web_order_permission_classification_id
         *
         * @param integer|null $web_order_permission_classification_id
         *
         * @return ProductExtensionItem
         */
        public function setWebOrderPermissionClassificationId($value = null)
        {
            $this->web_order_permission_classification_id = $value;

            return $this;
        }
        /**
         * Get web_order_permission_classification_id
         *
         * @return integer|null
         */
        public function getWebOrderPermissionClassificationId()
        {
            return $this->web_order_permission_classification_id;
        }


        /**
         * Set ordering_time
         *
         * @param integer|null $ordering_time
         *
         * @return ProductExtensionItem
         */
        public function setOrderingTime($value = null)
        {
            $this->ordering_time = $value;

            return $this;
        }
        /**
         * Get ordering_time
         *
         * @return integer|null
         */
        public function getOrderingTime()
        {
            return $this->ordering_time;
        }


        /**
         * Set deposit_box
         *
         * @param string|null $deposit_box
         *
         * @return ProductExtensionItem
         */
        public function setDepositBox($value = null)
        {
            $this->deposit_box = $value;

            return $this;
        }
        /**
         * Get deposit_box
         *
         * @return string|null
         */
        public function getDepositBox()
        {
            return $this->deposit_box;
        }


        /**
         * Set delivery_good_group
         *
         * @param integer|null $delivery_good_group
         *
         * @return ProductExtensionItem
         */
        public function setDeliveryGoodGroup($value = null)
        {
            $this->delivery_good_group = $value;

            return $this;
        }
        /**
         * Get delivery_good_group
         *
         * @return integer|null
         */
        public function getDeliveryGoodGroup()
        {
            return $this->delivery_good_group;
        }


        /**
         * Set classification_shipping_cost_calculation
         *
         * @param integer|null $classification_shipping_cost_calculation
         *
         * @return ProductExtensionItem
         */
        public function setClassificationShippingCostCalculation($value = null)
        {
            $this->classification_shipping_cost_calculation = $value;

            return $this;
        }
        /**
         * Get classification_shipping_cost_calculation
         *
         * @return integer|null
         */
        public function getClassificationShippingCostCalculation()
        {
            return $this->classification_shipping_cost_calculation;
        }


        /**
         * Set filling_control_table_output_classification
         *
         * @param integer|null $filling_control_table_output_classification
         *
         * @return ProductExtensionItem
         */
        public function setFillingControlTableOutputClassification($value = null)
        {
            $this->filling_control_table_output_classification = $value;

            return $this;
        }
        /**
         * Get filling_control_table_output_classification
         *
         * @return integer|null
         */
        public function getFillingControlTableOutputClassification()
        {
            return $this->filling_control_table_output_classification;
        }


        /**
         * Set repack_classification
         *
         * @param integer|null $repack_classification
         *
         * @return ProductExtensionItem
         */
        public function setRepackClassification($value = null)
        {
            $this->repack_classification = $value;

            return $this;
        }
        /**
         * Get repack_classification
         *
         * @return integer|null
         */
        public function getRepackClassification()
        {
            return $this->repack_classification;
        }


        /**
         * Set processed_product_category_id
         *
         * @param integer|null $processed_product_category_id
         *
         * @return ProductExtensionItem
         */
        public function setProcessedProductCategoryId($value = null)
        {
            $this->processed_product_category_id = $value;

            return $this;
        }
        /**
         * Get processed_product_category_id
         *
         * @return integer|null
         */
        public function getProcessedProductCategoryId()
        {
            return $this->processed_product_category_id;
        }


        /**
         * Set defrosting_method_id
         *
         * @param integer|null $defrosting_method_id
         *
         * @return ProductExtensionItem
         */
        public function setDefrostingMethodId($value = null)
        {
            $this->defrosting_method_id = $value;

            return $this;
        }
        /**
         * Get defrosting_method_id
         *
         * @return integer|null
         */
        public function getDefrostingMethodId()
        {
            return $this->defrosting_method_id;
        }


        /**
         * Set impoverished_area_id
         *
         * @param integer|null $impoverished_area_id
         *
         * @return ProductExtensionItem
         */
        public function setImpoverishedAreaId($value = null)
        {
            $this->impoverished_area_id = $value;

            return $this;
        }
        /**
         * Get impoverished_area_id
         *
         * @return integer|null
         */
        public function getImpoverishedAreaId()
        {
            return $this->impoverished_area_id;
        }


        /**
         * Set pref_id
         *
         * @param integer|null $pref_id
         *
         * @return ProductExtensionItem
         */
        public function setPrefId($value = null)
        {
            $this->pref_id = $value;

            return $this;
        }
        /**
         * Get impoverished_area_id
         *
         * @return integer|null
         */
        public function getPrefId()
        {
            return $this->pref_id;
        }


        /**
         * Set track_no
         *
         * @param integer|null $track_no
         *
         * @return ProductExtensionItem
         */
        public function setTrackNo($value = null)
        {
            $this->track_no = $value;

            return $this;
        }
        /**
         * Get track_no
         *
         * @return integer|null
         */
        public function getTrackNo()
        {
            return $this->track_no;
        }
    }
}
