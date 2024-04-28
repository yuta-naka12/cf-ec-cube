<?php

namespace Customize\Entity\Customer;

#DBにアクセスするためのライブラリなどを読み込み
use Customize\Entity\Master\CustomerRank;
use Customize\Entity\Master\WithdrawalReason;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Customize\Entity\Order\Sender;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\PurchasingGroup;

#拡張をする対象エンティティの指定
/**
 * @Eccube\EntityExtension("Eccube\Entity\Customer")
 */

trait CustomerTrait //ファイル名と合わせる
{
    /**
     * FAX番号
     * @var string
     * @ORM\Column(name="fax", type="string", precision=12, nullable=true, length=32)
     */
    private $fax;

    /**
     * 会員コメント２
     * @var string
     * @ORM\Column(name="note_2", type="string", nullable=true, length=255)
     */
    private $note_2;

    /**
     * 会員コメント３
     * @var string
     * @ORM\Column(name="note_3", type="string", nullable=true, length=255)
     */
    private $note_3;

    /**
     * 配送曜日
     * @var string
     * @ORM\Column(name="delivery_date", type="string", precision=12, nullable=true, length=32)
     */
    private $delivery_date;

    /**
     * 初回公募
     * @var string
     * @ORM\Column(name="initial_public_offering", type="string", precision=12, nullable=true, length=32)
     */
    private $initial_public_offering;

    /**
     * @var CustomerRank
     *
     * @ORM\OneToOne(targetEntity="\Customize\Entity\Master\CustomerRank")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="customer_rank_id", referencedColumnName="id")
     * })
     */
    private $CustomerRank;

    /**
     * @var WithdrawalReason
     *
     * @ORM\OneToOne(targetEntity="\Customize\Entity\Master\WithdrawalReason")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="withdrawal_reason_id", referencedColumnName="id")
     * })
     */
    private $WithdrawalReason;

    /**
     * 単価掛け率
     * @var integer
     * @ORM\Column(name="unit_cost_ratio", type="integer", precision=12, nullable=true, length=32)
     */
    private $unit_cost_ratio;

    /**
     * ページ数
     * @var integer
     * @ORM\Column(name="page_number", type="integer", precision=12, nullable=true, length=32)
     */
    private $page_number;

    /**
     * 貯金記号
     * @var string
     * @ORM\Column(name="bank_account_symbol", type="string", nullable=true, length=32)
     */
    private $bank_account_symbol;

    /**
     * bank_account_number
     * @var string
     * @ORM\Column(name="bank_account_number", type="string", nullable=true, length=32)
     */
    private $bank_account_number;

    /**
     * bank_account_name
     * @var string
     * @ORM\Column(name="bank_account_name", type="string", nullable=true, length=128)
     */
    private $bank_account_name;

    /**
     * bank_account_name_kana
     * @var string
     * @ORM\Column(name="bank_account_name_kana", type="string", nullable=true, length=128)
     */
    private $bank_account_name_kana;

    /**
     * Bank Account Registration Date
     * @var \DateTime|null
     *
     * @ORM\Column(name="bank_account_registration_date", type="datetimetz", nullable=true)
     */
    private $bank_account_registration_date;

    /**
     * Set fax.
     *
     * @param null $value
     * @return Customer
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
     * Set note_2.
     *
     * @param null $value
     * @return Customer
     */
    public function setNote2($value = null)
    {
        $this->note_2 = $value;

        return $this;
    }

    /**
     * Get note_2.
     *
     * @return string|null
     */
    public function getNote2()
    {
        return $this->note_2;
    }

    /**
     * Set note_3.
     *
     * @param null $value
     * @return Customer
     */
    public function setNote3($value = null)
    {
        $this->note_3 = $value;

        return $this;
    }

    /**
     * Get note_3.
     *
     * @return string|null
     */
    public function getNote3()
    {
        return $this->note_3;
    }

    /**
     * Set delivery_date.
     *
     * @param null $value
     * @return Customer
     */
    public function setDeliveryDate($value = null)
    {
        $this->delivery_date = $value;

        return $this;
    }

    /**
     * Get delivery_date.
     *
     * @return string|null
     */
    public function getDeliveryDate()
    {
        return $this->delivery_date;
    }

    /**
     * Set initial_public_offering.
     *
     * @param null $value
     * @return Customer
     */
    public function setInitialPublicOffering($value = null)
    {
        $this->initial_public_offering = $value;

        return $this;
    }

    /**
     * Get initial_public_offering.
     *
     * @return string|null
     */
    public function getInitialPublicOffering()
    {
        return $this->initial_public_offering;
    }

    /**
     * Set CustomerRank.
     *
     * @param CustomerRank|null $value
     *
     * @return Customer
     */
    public function setCustomerRank($value = null)
    {
        $this->CustomerRank = $value;

        return $this;
    }

    /**
     * Get CustomerRank.
     *
     * @return CustomerRank|null
     */
    public function getCustomerRank()
    {
        return $this->CustomerRank;
    }

    /**
     * Set WithdrawalReason.
     *
     * @param WithdrawalReason|null $value
     *
     * @return Customer
     */
    public function setWithdrawalReason($value = null)
    {
        $this->WithdrawalReason = $value;

        return $this;
    }

    /**
     * Get WithdrawalReason.
     *
     * @return WithdrawalReason|null
     */
    public function getWithdrawalReason()
    {
        return $this->WithdrawalReason;
    }

    /**
     * Set unit_cost_ratio.
     *
     * @param null $value
     * @return Customer
     */
    public function setUnitCostRatio($value = null)
    {
        $this->unit_cost_ratio = $value;

        return $this;
    }

    /**
     * Get unit_cost_ratio.
     *
     * @return string|null
     */
    public function getUnitCostRatio()
    {
        return $this->unit_cost_ratio;
    }

    /**
     * Set page_number.
     *
     * @param null $value
     * @return Customer
     */
    public function setPageNumber($value = null)
    {
        $this->page_number = $value;

        return $this;
    }

    /**
     * Get page_number.
     *
     * @return integer|null
     */
    public function getPageNumber()
    {
        return $this->page_number;
    }

    /**
     * Set bank_account_symbol.
     *
     * @param null $value
     * @return Customer
     */
    public function setBankAccountSymbol($value = null)
    {
        $this->bank_account_symbol = $value;

        return $this;
    }

    /**
     * Get bank_account_symbol.
     *
     * @return string|null
     */
    public function getBankAccountSymbol()
    {
        return $this->bank_account_symbol;
    }

    /**
     * Set bank_account_number.
     *
     * @param null $value
     * @return Customer
     */
    public function setBankAccountNumber($value = null)
    {
        $this->bank_account_number = $value;

        return $this;
    }

    /**
     * Get bank_account_number.
     *
     * @return string|null
     */
    public function getBankAccountNumber()
    {
        return $this->bank_account_number;
    }

    /**
     * Set bank_account_name.
     *
     * @param null $value
     * @return Customer
     */
    public function setBankAccountName($value = null)
    {
        $this->bank_account_name = $value;

        return $this;
    }

    /**
     * Get bank_account_name.
     *
     * @return string|null
     */
    public function getBankAccountName()
    {
        return $this->bank_account_name;
    }

    /**
     * Set bank_account_name_kana.
     *
     * @param null $value
     * @return Customer
     */
    public function setBankAccountNameKana($value = null)
    {
        $this->bank_account_name_kana = $value;

        return $this;
    }

    /**
     * Get bank_account_name_kana.
     *
     * @return string|null
     */
    public function getBankAccountNameKana()
    {
        return $this->bank_account_name_kana;
    }

    /**
     * Set bank_account_registration_date.
     *
     * @param null $value
     * @return Customer
     */
    public function setBankAccountRegistrationDate($value = null)
    {
        $this->bank_account_registration_date = $value;

        return $this;
    }

    /**
     * Get bank_account_registration_date.
     *
     * @return string|null
     */
    public function getBankAccountRegistrationDate()
    {
        return $this->bank_account_registration_date;
    }
}
