<?php

namespace Customize\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Member;
use Eccube\Entity\CustomerTrait;
use Eccube\Entity\Product;

if (!class_exists('\Customize\Entity\Product\ProductSupplier')) {
    /**
     * ProductSupplier
     *
     * @ORM\Table(name="dtb_product_supplier")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\ProductSupplierRepository")
     */
    class ProductSupplier extends \Eccube\Entity\AbstractEntity
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
         * 仕入先
         * @var string
         *
         * @ORM\Column(name="name", type="string", nullable=true)
         */
        private $name;

        /**
         * @var string
         *
         * @ORM\Column(name="supplier_name", type="string", nullable=true)
         */
        private $supplier_name;

        /**
         * @var string
         *
         * @ORM\Column(name="phone_number", type="string", nullable=true)
         */
        private $phone_number;

        /**
         * @var string
         *
         * @ORM\Column(name="fax", type="string", nullable=true)
         */
        private $fax;

        /**
         * @var string
         *
         * @ORM\Column(name="email", type="string", nullable=true)
         */
        private $email;

        /**
         * @var string
         *
         * @ORM\Column(name="postal_code", type="string", nullable=true)
         */
        private $postal_code;

        /**
         * @var string
         *
         * @ORM\Column(name="address", type="string", nullable=true)
         */
        private $address;

        /**
         * @var string
         *
         * @ORM\Column(name="supplier_category_1", type="string", nullable=true)
         */
        private $supplier_category_1;

        /**
         * @var string
         *
         * @ORM\Column(name="supplier_category_2", type="string", nullable=true)
         */
        private $supplier_category_2;

        /**
         * @var \DateTime|null
         *
         * @ORM\Column(name="closing_date", type="datetimetz", nullable=true)
         */
        private $closing_date;

         /**
         * @var \DateTime|null
         *
         * @ORM\Column(name="payment_date", type="datetimetz", nullable=true)
         */
        private $payment_date;

         /**
         * @var string
         *
         * @ORM\Column(name="payment_cycle", type="string", nullable=true)
         */
        private $payment_cycle;

         /**
         * @var string
         *
         * @ORM\Column(name="payment_term", type="string", nullable=true)
         */
        private $payment_term;

         /**
         * @var string
         *
         * @ORM\Column(name="closing_process_target_category", type="string", nullable=true)
         */
        private $closing_process_target_category;

         /**
       * SA紐付け
       * @var \Eccube\Entity\Member
       *
       * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member", inversedBy="Customer")
       * @ORM\JoinColumns({
       *   @ORM\JoinColumn(name="member", referencedColumnName="id")
       * })
       */
       private $member;

        /**
         * @var string
         *
         * @ORM\Column(name="payment_management_class", type="string", nullable=true)
         */
        private $payment_management_class;

        /**
         * @var integer
         *
         * @ORM\Column(name="balance_costs_beginning_year", type="integer", nullable=true)
         */
        private $balance_costs_beginning_year;

        /**
         * @var integer
         *
         * @ORM\Column(name="previous_payment_amount", type="integer", nullable=true)
         */
        private $previous_payment_amount;

        /**
         * @var integer
         *
         * @ORM\Column(name="payment_rate", type="integer", nullable=true)
         */
        private $payment_rate;

        /**
         * @var integer
         *
         * @ORM\Column(name="basic_cost", type="integer", nullable=true)
         */
        private $basic_cost;

        /**
         * @var string
         *
         * @ORM\Column(name="payment_amount_method", type="string", nullable=true)
         */
        private $payment_amount_method;

         /**
         * @var integer
         *
         * @ORM\Column(name="basic_cost_amount", type="integer", nullable=true)
         */
        private $basic_cost_amount;

        /**
         * @var boolean
         *
         * @ORM\Column(name="is_enable_transfer_infomation", type="boolean", nullable=true)
         */
        private $is_enable_transfer_infomation;

        /**
         * @var string
         *
         * @ORM\Column(name="payee", type="string", nullable=true)
         */
        private $payee;

         /**
         * @var string
         *
         * @ORM\Column(name="account_classification", type="string", nullable=true)
         */
        private $account_classification;

         /**
         * @var string
         *
         * @ORM\Column(name="debit_account_code", type="string", nullable=true)
         */
        private $debit_account_code;

         /**
         * @var boolean
         *
         * @ORM\Column(name="cost_multiplier_calculation", type="boolean", nullable=true)
         */
        private $cost_multiplier_calculation;

         /**
         * 索引
         * @var integer
         *
         * @ORM\Column(name="unit_cost_rate", type="integer", nullable=true)
         */
        private $unit_cost_rate;

           /**
         * @var boolean
         *
         * @ORM\Column(name="is_enable_unit_cost", type="boolean", nullable=true)
         */
        private $is_enable_unit_cost;

        /**
         * @var boolean
         *
         * @ORM\Column(name="consumption_tax_notice", type="boolean", nullable=true)
         */
        private $consumption_tax_notice;

        /**
         * @var boolean
         *
         * @ORM\Column(name="calculation_of_consumption_tax_payable", type="boolean", nullable=true)
         */
        private $calculation_of_consumption_tax_payable;

         /**
         * @var string
         *
         * @ORM\Column(name="unit_cost_class", type="string", nullable=true)
         */
        private $unit_cost_class;

           /**
         * @var string
         *
         * @ORM\Column(name="unit_cost", type="string", nullable=true)
         */
        private $unit_cost;

         /**
         * @var string
         *
         * @ORM\Column(name="subscription_class", type="string", nullable=true)
         */
        private $subscription_class;

        /**
         * @var string
         *
         * @ORM\Column(name="vat_class", type="string", nullable=true)
         */
        private $vat_class;

        /**
         * @var string
         *
         * @ORM\Column(name="vat_calculation_class", type="string", nullable=true)
         */
        private $vat_calculation_class;

          /**
         * @var string
         *
         * @ORM\Column(name="consumption_vat_culture_class", type="string", nullable=true)
         */
        private $consumption_vat_culture_class;

           /**
         * @var string
         *
         * @ORM\Column(name="designated_payment_cost_use_class", type="string", nullable=true)
         */
        private $designated_payment_cost_use_class;

                  /**
         * @var integer
         *
         * @ORM\Column(name="designated_payment_form_no", type="integer", nullable=true)
         */
        private $designated_payment_form_no;

        /**
         * @var string
         *
         * @ORM\Column(name="payment_statement_output_type", type="string", nullable=true)
         */
        private $payment_statement_output_type;

         /**
         * @var string
         *
         * @ORM\Column(name="payment_statement_output_format", type="string", nullable=true)
         */
        private $payment_statement_output_format;

            /**
         * @var string
         *
         * @ORM\Column(name="date_classification", type="string", nullable=true)
         */
        private $date_classification;

        /**
         * @var string
         *
         * @ORM\Column(name="order_output_document_class", type="string", nullable=true)
         */
        private $order_output_document_class;

          /**
         * @var string
         *
         * @ORM\Column(name="transaction", type="string", nullable=true)
         */
        private $transaction;

         /**
         * @var string
         *
         * @ORM\Column(name="individual_setting_of_entry_levels", type="string", nullable=true)
         */
        private $individual_setting_of_entry_levels;

         /**
         * @var string
         *
         * @ORM\Column(name="classification", type="string", nullable=true)
         */
        private $classification;

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
         * @var \Doctrine\Common\Collections\Collection
         *
         * @ORM\OneToMany(targetEntity="Eccube\Entity\Product", mappedBy="ProductSupplier", cascade={"remove"})
         */
        private $Product;

        /**
         * Constructor
         */
        public function __construct()
        {
            $this->Product = new \Doctrine\Common\Collections\ArrayCollection();
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
         * @return ProductMaker
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
         * Set supplier_name
         *
         * @param string|null $supplier_name
         *
         * @return ProductSupplier
         */
        public function setSupplierName($name = null)
        {
            $this->supplier_name = $name;

            return $this;
        }

        /**
         * Get supplier_name.
         *
         * @return string|null
         */
        public function getSupplierName()
        {
            return $this->supplier_name;
        }

        /**
         * Set phone_number
         *
         * @param string|null $phone_number
         *
         * @return ProductSupplier
         */
        public function setPhoneNumber($value = null)
        {
            $this->phone_number = $value;

            return $this;
        }

        /**
         * Get phone_number.
         *
         * @return string|null
         */
        public function getPhoneNumber()
        {
            return $this->phone_number;
        }

        /**
         * Set fax
         *
         * @param string|null $fax
         *
         * @return ProductSupplier
         */
        public function setFax($value = null)
        {
            $this->fax = $value;

            return $this;
        }

        /**
         * Get fax.
         *
         * @return string|null
         */
        public function getFax()
        {
            return $this->fax;
        }

        /**
         * Set email
         *
         * @param string|null $email
         *
         * @return ProductSupplier
         */
        public function setEmail($value = null)
        {
            $this->email = $value;

            return $this;
        }

        /**
         * Get email.
         *
         * @return string|null
         */
        public function getEmail()
        {
            return $this->email;
        }

        /**
         * Set postal_code
         *
         * @param string|null $postal_code
         *
         * @return ProductSupplier
         */
        public function setPostalCode($value = null)
        {
            $this->postal_code = $value;

            return $this;
        }

        /**
         * Get postal_code.
         *
         * @return string|null
         */
        public function getPostalCode()
        {
            return $this->postal_code;
        }

        /**
         * Set address
         *
         * @param string|null $address
         *
         * @return ProductSupplier
         */
        public function setAddress($value = null)
        {
            $this->address = $value;

            return $this;
        }

        /**
         * Get address.
         *
         * @return string|null
         */
        public function getAddress()
        {
            return $this->address;
        }

        /**
         * Set supplier_category_1
         *
         * @param string|null $supplier_category_1
         *
         * @return ProductSupplier
         */
        public function setSupplierCategory1($value = null)
        {
            $this->supplier_category_1 = $value;

            return $this;
        }

        /**
         * Get supplier_category_1.
         *
         * @return string|null
         */
        public function getSupplierCategory1()
        {
            return $this->supplier_category_1;
        }

        /**
         * Set supplier_category_2
         *
         * @param string|null $supplier_category_2
         *
         * @return ProductSupplier
         */
        public function setSupplierCategory2($value = null)
        {
            $this->supplier_category_2 = $value;

            return $this;
        }

        /**
         * Get supplier_category_2.
         *
         * @return string|null
         */
        public function getSupplierCategory2()
        {
            return $this->supplier_category_2;
        }

         /**
         * Set closing_date
         *
         * @param \DateTime|null $closing_date
         *
         * @return ProductSupplier
         */
        public function setClosingDate($closing_date = null)
        {
            $this->closing_date = $closing_date;

            return $this;
        }

        /**
         * Get closing_date
         *
         * @return \DateTime|null
         */
        public function getClosingDate()
        {
            return $this->closing_date;
        }



        /**
         * Set payment_date
         *
         * @param \DateTime|null $payment_date
         *
         * @return ProductSupplier
         */
        public function setPaymentDate($payment_date = null)
        {
            $this->payment_date = $payment_date;

            return $this;
        }

        /**
         * Get payment_date
         *
         * @return \DateTime|null
         */
        public function getPaymentDate()
        {
            return $this->payment_date;
        }


         /**
         * Set payment_cycle
         *
         * @param string|null $payment_cycle
         *
         * @return ProductSupplier
         */
        public function setPaymentCycle($payment_cycle = null)
        {
            $this->payment_cycle = $payment_cycle;

            return $this;
        }

        /**
         * Get payment_cycle
         *
         * @return string|null
         */
        public function getPaymentCycle()
        {
            return $this->payment_cycle;
        }


        /**
         * Set payment_term
         *
         * @param string|null $payment_term
         *
         * @return ProductSupplier
         */
        public function setPaymentTerm($payment_term = null)
        {
            $this->payment_term = $payment_term;

            return $this;
        }

        /**
         * Get payment_term
         *
         * @return string|null
         */
        public function getPaymentTerm()
        {
            return $this->payment_term;
        }


                /**
         * Set closing_process_target_category
         *
         * @param string|null $closing_process_target_category
         *
         * @return ProductSupplier
         */
        public function setclosingProcessTargetCategory($closing_process_target_category = null)
        {
            $this->closing_process_target_category = $closing_process_target_category;

            return $this;
        }

        /**
         * Get closing_process_target_category
         *
         * @return string|null
         */
        public function getclosingProcessTargetCategory()
        {
            return $this->closing_process_target_category;
        }

        /**
         * Set member.
         *
         * @param \Eccube\Entity\Member|null $member
         *
         * @return Member
         */
        public function setMember(\Eccube\Entity\Member $member = null)
        {
            $this->member = $member;

            return $this;
        }

        /**
         * Get member.
         *
         * @return \Eccube\Entity\Member|null
         */
        public function getMember()
        {
            return $this->member;
        }


        /**
         * Set payment_management_class
         *
         * @param string|null $payment_management_class
         *
         * @return ProductSupplier
         */
        public function setPaymentManagementClass($payment_management_class = null)
        {
            $this->payment_management_class = $payment_management_class;

            return $this;
        }

        /**
         * Get payment_management_class
         *
         * @return string|null
         */
        public function getPaymentManagementClass()
        {
            return $this->payment_management_class;
        }


                /**
         * Set balance_costs_beginning_year
         *
         * @param integer|null $balance_costs_beginning_year
         *
         * @return ProductSupplier
         */
        public function setBalanceCostsBeginningYear($balance_costs_beginning_year = null)
        {
            $this->balance_costs_beginning_year = $balance_costs_beginning_year;

            return $this;
        }

        /**
         * Get balance_costs_beginning_year
         *
         * @return integer|null
         */
        public function getBalanceCostsBeginningYear()
        {
            return $this->balance_costs_beginning_year;
        }


        /**
         * Set previous_payment_amount
         *
         * @param integer|null $previous_payment_amount
         *
         * @return ProductSupplier
         */
        public function setPreviousPaymentAmount($previous_payment_amount = null)
        {
            $this->previous_payment_amount = $previous_payment_amount;

            return $this;
        }

        /**
         * Get previous_payment_amount
         *
         * @return integer|null
         */
        public function getPreviousPaymentAmount()
        {
            return $this->previous_payment_amount;
        }

        /**
         * Set payment_rate
         *
         * @param integer|null $payment_rate
         *
         * @return ProductSupplier
         */
        public function setPaymentRate($payment_rate = null)
        {
            $this->payment_rate = $payment_rate;

            return $this;
        }

        /**
         * Get payment_rate
         *
         * @return integer|null
         */
        public function getPaymentRate()
        {
            return $this->payment_rate;
        }


        /**
         * Set basic_cost
         *
         * @param integer|null $basic_cost
         *
         * @return ProductSupplier
         */
        public function setBasicCost($basic_cost = null)
        {
            $this->basic_cost = $basic_cost;

            return $this;
        }

        /**
         * Get basic_cost
         *
         * @return integer|null
         */
        public function getBasicCost()
        {
            return $this->basic_cost;
        }

        /**
         * Set payment_amount_method
         *
         * @param string|null $payment_amount_method
         *
         * @return ProductSupplier
         */
        public function setPaymentAmountMethod($payment_amount_method = null)
        {
            $this->payment_amount_method = $payment_amount_method;

            return $this;
        }

        /**
         * Get payment_amount_method
         *
         * @return string|null
         */
        public function getPaymentAmountMethod()
        {
            return $this->payment_amount_method;
        }


        /**
         * Set basic_cost_amount
         *
         * @param integer|null $basic_cost_amount
         *
         * @return ProductSupplier
         */
        public function setBasicCostAmount($basic_cost_amount = null)
        {
            $this->basic_cost_amount = $basic_cost_amount;

            return $this;
        }

        /**
         * Get basic_cost_amount
         *
         * @return integer|null
         */
        public function getBasicCostAmount()
        {
            return $this->basic_cost_amount;
        }

        /**
         * Set is_enable_transfer_infomation
         *
         * @param boolean|null $is_enable_transfer_infomation
         *
         * @return ProductSupplier
         */
        public function setIsEnableTransferInfomation($is_enable_transfer_infomation = null)
        {
            $this->is_enable_transfer_infomation = $is_enable_transfer_infomation;

            return $this;
        }

        /**
         * Get is_enable_transfer_infomation
         *
         * @return boolean|null
         */
        public function getIsEnableTransferInfomation()
        {
            return $this->is_enable_transfer_infomation;
        }

        /**
         * Set payee
         *
         * @param string|null $payee
         *
         * @return ProductSupplier
         */
        public function setPayee($payee = null)
        {
            $this->payee = $payee;

            return $this;
        }

        /**
         * Get payee
         *
         * @return string|null
         */
        public function getPayee()
        {
            return $this->payee;
        }

        /**
         * Set account_classification
         *
         * @param string|null $account_classification
         *
         * @return ProductSupplier
         */
        public function setAccountClassification($account_classification = null)
        {
            $this->account_classification = $account_classification;

            return $this;
        }

        /**
         * Get account_classification
         *
         * @return string|null
         */
        public function getAccountClassification()
        {
            return $this->account_classification;
        }

        /**
         * Set debit_account_code
         *
         * @param string|null $debit_account_code
         *
         * @return ProductSupplier
         */
        public function setDebitAccountCode($debit_account_code = null)
        {
            $this->debit_account_code = $debit_account_code;

            return $this;
        }

        /**
         * Get debit_account_code
         *
         * @return string|null
         */
        public function getDebitAccountCode()
        {
            return $this->debit_account_code;
        }

                /**
         * Set cost_multiplier_calculation
         *
         * @param boolean|null $cost_multiplier_calculation
         *
         * @return ProductSupplier
         */
        public function setCostMultiplierCalculation($cost_multiplier_calculation = null)
        {
            $this->cost_multiplier_calculation = $cost_multiplier_calculation;

            return $this;
        }

        /**
         * Get cost_multiplier_calculation
         *
         * @return boolean|null
         */
        public function getCostMultiplierCalculation()
        {
            return $this->cost_multiplier_calculation;
        }

                /**
         * Set unit_cost_rate
         *
         * @param integer|null $unit_cost_rate
         *
         * @return ProductSupplier
         */
        public function setUnitCostRate($unit_cost_rate = null)
        {
            $this->unit_cost_rate = $unit_cost_rate;

            return $this;
        }

        /**
         * Get unit_cost_rate
         *
         * @return integer|null
         */
        public function getUnitCostRate()
        {
            return $this->unit_cost_rate;
        }

        /**
         * Set is_enable_unit_cost
         *
         * @param boolean|null $is_enable_unit_cost
         *
         * @return ProductSupplier
         */
        public function setIsEnableUnitCost($is_enable_unit_cost = null)
        {
            $this->is_enable_unit_cost = $is_enable_unit_cost;

            return $this;
        }

        /**
         * Get is_enable_unit_cost
         *
         * @return boolean|null
         */
        public function getIsEnableUnitCost()
        {
            return $this->is_enable_unit_cost;
        }

        /**
         * Set consumption_tax_notice
         *
         * @param boolean|null $consumption_tax_notice
         *
         * @return ProductSupplier
         */
        public function setConsumptionTaxNotice($consumption_tax_notice = null)
        {
            $this->consumption_tax_notice = $consumption_tax_notice;

            return $this;
        }

        /**
         * Get consumption_tax_notice
         *
         * @return boolean|null
         */
        public function getConsumptionTaxNotice()
        {
            return $this->consumption_tax_notice;
        }

        /**
         * Set calculation_of_consumption_tax_payable
         *
         * @param boolean|null $calculation_of_consumption_tax_payable
         *
         * @return ProductSupplier
         */
        public function setCalculationofConsumptionTaxPayable($calculation_of_consumption_tax_payable = null)
        {
            $this->calculation_of_consumption_tax_payable = $calculation_of_consumption_tax_payable;

            return $this;
        }

        /**
         * Get calculation_of_consumption_tax_payable
         *
         * @return boolean|null
         */
        public function getCalculationofConsumptionTaxPayable()
        {
            return $this->calculation_of_consumption_tax_payable;
        }

        /**
         * Set unit_cost_class
         *
         * @param string|null $unit_cost_class
         *
         * @return ProductSupplier
         */
        public function setUnitCostClass($unit_cost_class = null)
        {
            $this->unit_cost_class = $unit_cost_class;

            return $this;
        }

        /**
         * Get unit_cost_class
         *
         * @return string|null
         */
        public function getUnitCostClass()
        {
            return $this->unit_cost_class;
        }

                /**
         * Set unit_cost
         *
         * @param string|null $unit_cost
         *
         * @return ProductSupplier
         */
        public function setUnitCost($unit_cost = null)
        {
            $this->unit_cost = $unit_cost;

            return $this;
        }

        /**
         * Get unit_cost_class
         *
         * @return string|null
         */
        public function getUnitCost()
        {
            return $this->unit_cost;
        }

        /**
         * Set subscription_class
         *
         * @param string|null $subscription_class
         *
         * @return ProductSupplier
         */
        public function setSubscriptionClass($subscription_class = null)
        {
            $this->subscription_class = $subscription_class;

            return $this;
        }

        /**
         * Get subscription_class
         *
         * @return string|null
         */
        public function getSubscriptionClass()
        {
            return $this->subscription_class;
        }

        /**
         * Set vat_class
         *
         * @param string|null $vat_class
         *
         * @return ProductSupplier
         */
        public function setVatClass($vat_class = null)
        {
            $this->vat_class = $vat_class;

            return $this;
        }

        /**
         * Get vat_class
         *
         * @return string|null
         */
        public function getVatClass()
        {
            return $this->vat_class;
        }

                /**
         * Set vat_calculation_class
         *
         * @param string|null $vat_calculation_class
         *
         * @return ProductSupplier
         */
        public function setVatCalculationClass($vat_calculation_class = null)
        {
            $this->vat_calculation_class = $vat_calculation_class;

            return $this;
        }

        /**
         * Get vat_calculation_class
         *
         * @return string|null
         */
        public function getVatCalculationClass()
        {
            return $this->vat_calculation_class;
        }

        /**
         * Set consumption_vat_culture_class
         *
         * @param string|null $consumption_vat_culture_class
         *
         * @return ProductSupplier
         */
        public function setConsumptionVatCultureClass($consumption_vat_culture_class = null)
        {
            $this->consumption_vat_culture_class = $consumption_vat_culture_class;

            return $this;
        }

        /**
         * Get consumption_vat_culture_class
         *
         * @return string|null
         */
        public function getConsumptionVatCultureClass()
        {
            return $this->consumption_vat_culture_class;
        }

                /**
         * Set designated_payment_cost_use_class
         *
         * @param string|null $designated_payment_cost_use_class
         *
         * @return ProductSupplier
         */
        public function setDesignatedPaymentCostUseClass($designated_payment_cost_use_class = null)
        {
            $this->designated_payment_cost_use_class = $designated_payment_cost_use_class;

            return $this;
        }

        /**
         * Get designated_payment_cost_use_class
         *
         * @return string|null
         */
        public function getDesignatedPaymentCostUseClass()
        {
            return $this->designated_payment_cost_use_class;
        }

        /**
        * Set designated_payment_form_no
        *
        * @param integer|null $designated_payment_form_no
        *
        * @return ProductSupplier
        */
       public function setDesignatedPaymentFormNo($designated_payment_form_no = null)
       {
           $this->designated_payment_form_no = $designated_payment_form_no;

           return $this;
       }

       /**
        * Get designated_payment_form_no
        *
        * @return integer|null
        */
       public function getDesignatedPaymentFormNo()
       {
           return $this->designated_payment_form_no;
       }

        /**
         * Set payment_statement_output_type
         *
         * @param string|null $payment_statement_output_type
         *
         * @return ProductSupplier
         */
        public function setPaymentStatementOutputType($payment_statement_output_type = null)
        {
            $this->payment_statement_output_type = $payment_statement_output_type;

            return $this;
        }

        /**
         * Get payment_statement_output_type
         *
         * @return string|null
         */
        public function getPaymentStatementOutputType()
        {
            return $this->payment_statement_output_type;
        }

        /**
         * Set payment_statement_output_format
         *
         * @param string|null $payment_statement_output_format
         *
         * @return ProductSupplier
         */
        public function setPaymentStatementOutputFormat($payment_statement_output_format = null)
        {
            $this->payment_statement_output_format = $payment_statement_output_format;

            return $this;
        }

        /**
         * Get payment_statement_output_format
         *
         * @return string|null
         */
        public function getPaymentStatementOutputFormat()
        {
            return $this->payment_statement_output_format;
        }

        /**
         * Set date_classification
         *
         * @param string|null $date_classification
         *
         * @return ProductSupplier
         */
        public function setDateClassification($date_classification = null)
        {
            $this->date_classification = $date_classification;

            return $this;
        }

        /**
         * Get date_classification
         *
         * @return string|null
         */
        public function getDateClassification()
        {
            return $this->date_classification;
        }

        /**
         * Set order_output_document_class
         *
         * @param string|null $order_output_document_class
         *
         * @return ProductSupplier
         */
        public function setOrderOutputDocumentClass($order_output_document_class = null)
        {
            $this->order_output_document_class = $order_output_document_class;

            return $this;
        }

        /**
         * Get order_output_document_class
         *
         * @return string|null
         */
        public function getOrderOutputDocumentClass()
        {
            return $this->order_output_document_class;
        }

        /**
         * Set transaction
         *
         * @param string|null $transaction
         *
         * @return ProductSupplier
         */
        public function setTransaction($transaction = null)
        {
            $this->transaction = $transaction;

            return $this;
        }

        /**
         * Get transaction
         *
         * @return string|null
         */
        public function getTransaction()
        {
            return $this->transaction;
        }

                /**
         * Set individual_setting_of_entry_levels
         *
         * @param string|null $individual_setting_of_entry_levels
         *
         * @return ProductSupplier
         */
        public function setIndividualSettingOfEntryLevels($individual_setting_of_entry_levels = null)
        {
            $this->individual_setting_of_entry_levels = $individual_setting_of_entry_levels;

            return $this;
        }

        /**
         * Get individual_setting_of_entry_levels
         *
         * @return string|null
         */
        public function getIndividualSettingOfEntryLevels()
        {
            return $this->individual_setting_of_entry_levels;
        }


        /**
         * Set classification
         *
         * @param string|null $classification
         *
         * @return ProductSupplier
         */
        public function setClassification($classification = null)
        {
            $this->classification = $classification;

            return $this;
        }

        /**
         * Get classification
         *
         * @return string|null
         */
        public function getClassification()
        {
            return $this->classification;
        }




        /**
         * Set sort_no
         *
         * @param integer|null $sort_no
         *
         * @return ProductMaker
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
         * @return ProductSupplier
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
         * @return ProductSupplier
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
         * Add Product.
         *
         * @param \Eccube\Entity\Product $product
         *
         * @return Product
         */
        public function addProduct(Product $product)
        {
            $this->Product[] = $product;

            return $this;
        }

        /**
         * Remove Product.
         *
         * @param \Eccube\Entity\Product $product
         *
         * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
         */
        public function removeProduct(Product $product)
        {
            return $this->Product->removeElement($product);
        }

        /**
         * Get Product.
         *
         * @return \Doctrine\Common\Collections\Collection
         */
        public function getProduct()
        {
            return $this->Product;
        }
    }
}
