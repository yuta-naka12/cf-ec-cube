<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="dtb_pamphlet")
 */
class Pamphlet

{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

       /**
     * 索引
     * @var integer
     *
     * @ORM\Column(name="sort_no", type="integer", nullable=true)
     */
    private $sort_no;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $referralCategory;

    /**
     * @ORM\Column(type="integer")
     */
    private $newProductCategory;

    /**
     * @ORM\Column(type="integer")
     */
    private $subscription;

    /**
     * @ORM\Column(type="integer")
     */
    private $basicPrice;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $discountStartDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $discountEndDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $periodPrice;

    /**
     * @ORM\ManyToOne(targetEntity="BulkBuying")
     * @ORM\JoinColumn(name="bulk_buying_group_id", referencedColumnName="id")
     */
    private $bulkBuyingGroup;

    /**
     * @ORM\Column(type="integer")
     */
    private $point;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $campaignStartDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $campaignEndDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $purchaseAmount;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $periodStartDate;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $periodEndDate;

    /**
     * @ORM\Column(type="integer")
     */
    private $packaging;

    /**
     * @ORM\Column(type="integer")
     */
    private $settlement;

    /**
     * @ORM\Column(type="integer")
     */
    private $repayment;

    /**
     * @ORM\Column(type="integer")
     */
    private $dry;

    /**
     * @ORM\Column(type="integer")
     */
    private $storageFee;

    /**
     * @ORM\Column(type="integer")
     */
    private $interestRate;

    /**
     * @ORM\Column(type="integer")
     */
    private $margin;

    // Getters and setters for each property

    public function getId()
    {
        return $this->id;
    }


        /**
     * Set sort_no
     *
     * @param integer|null $sort_no
     *
     * @return Course
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

    public function getStartDate()
    {
        return $this->startDate;
    }

    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    public function getEndDate()
    {
        return $this->endDate;
    }

    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    public function getReferralCategory()
    {
        return $this->referralCategory;
    }

    public function setReferralCategory($referralCategory)
    {
        $this->referralCategory = $referralCategory;
    }

    public function getNewProductCategory()
    {
        return $this->newProductCategory;
    }

    public function setNewProductCategory($newProductCategory)
    {
        $this->newProductCategory = $newProductCategory;
    }

    public function getSubscription()
    {
        return $this->subscription;
    }

    public function setSubscription($subscription)
    {
        $this->subscription = $subscription;
    }

    public function getBasicPrice()
    {
        return $this->basicPrice;
    }

    public function setBasicPrice($basicPrice)
    {
        $this->basicPrice = $basicPrice;
    }

    public function getDiscountStartDate()
    {
        return $this->discountStartDate;
    }

    public function setDiscountStartDate($discountStartDate)
    {
        $this->discountStartDate = $discountStartDate;
    }

    public function getDiscountEndDate()
    {
        return $this->discountEndDate;
    }

    public function setDiscountEndDate($discountEndDate)
    {
        $this->discountEndDate = $discountEndDate;
    }

    public function getPeriodPrice()
    {
        return $this->periodPrice;
    }

    public function setPeriodPrice($periodPrice)
    {
        $this->periodPrice = $periodPrice;
    }

    public function getBulkBuyingGroup()
    {
        return $this->bulkBuyingGroup;
    }

    public function setBulkBuyingGroup($bulkBuyingGroup)
    {
        $this->bulkBuyingGroup = $bulkBuyingGroup;
    }

    public function getPoint()
    {
        return $this->point;
    }

    public function setPoint($point)
    {
        $this->point = $point;
    }

    public function getCampaignStartDate()
    {
        return $this->campaignStartDate;
    }

    public function setCampaignStartDate($campaignStartDate)
    {
        $this->campaignStartDate = $campaignStartDate;
    }

    public function getCampaignEndDate()
    {
        return $this->campaignEndDate;
    }

    public function setCampaignEndDate($campaignEndDate)
    {
        $this->campaignEndDate = $campaignEndDate;
    }

    public function getPurchaseAmount()
    {
        return $this->purchaseAmount;
    }

    public function setPurchaseAmount($purchaseAmount)
    {
        $this->purchaseAmount = $purchaseAmount;
    }

    public function getPeriodStartDate()
    {
        return $this->periodStartDate;
    }

    public function setPeriodStartDate($periodStartDate)
    {
        $this->periodStartDate = $periodStartDate;
    }

    public function getPeriodEndDate()
    {
        return $this->periodEndDate;
    }

    public function setPeriodEndDate($periodEndDate)
    {
        $this->periodEndDate = $periodEndDate;
    }

    public function getPackaging()
    {
        return $this->packaging;
    }

    public function setPackaging($packaging)
    {
        $this->packaging = $packaging;
    }

    public function getSettlement()
    {
        return $this->settlement;
    }

    public function setSettlement($settlement)
    {
        $this->settlement = $settlement;
    }

    public function getRepayment()
    {
        return $this->repayment;
    }

    public function setRepayment($repayment)
    {
        $this->repayment = $repayment;
    }

    public function getDry()
    {
        return $this->dry;
    }

    public function setDry($dry)
    {
        $this->dry = $dry;
    }

    public function getStorageFee()
    {
        return $this->storageFee;
    }

    public function setStorageFee($storageFee)
    {
        $this->storageFee = $storageFee;
    }

    public function getInterestRate()
    {
        return $this->interestRate;
    }

    public function setInterestRate($interestRate)
    {
        return $this->interestRate = $interestRate;
    }

    public function getMargin()
    {
        return $this->margin;
    }
    public function setMargin($margin)
    {
        return $this->margin = $margin;
    }
}



