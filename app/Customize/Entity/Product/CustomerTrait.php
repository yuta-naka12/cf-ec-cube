<?php

namespace Customize\Entity\Product;

#DBにアクセスするためのライブラリなどを読み込み
use Customize\Entity\CallList\CallList;
use Customize\Entity\Customer\CustomerClass;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Eccube\Entity\Customer;
use Eccube\Entity\Member;
use Eccube\Entity\Product;

#拡張をする対象エンティティの指定
/**
 * @Eccube\EntityExtension("Eccube\Entity\Customer")
 */


trait CustomerTrait //ファイル名と合わせる
{
    /**
     * ビル名
     * @var string
     * @ORM\Column(name="addr03", type="string", precision=12, nullable=true, length=32)
     */
    private $addr03;

    /**
     * 部署名
     * @var string
     * @ORM\Column(name="department", type="string", nullable=true, length=32)
     */
    private $department;

    /**
     * どこで知りましたか
     * @var string
     * @ORM\Column(name="where_hear_about_this_site", type="string", nullable=true, length=32)
     */
    private $where_hear_about_this_site;

    /**
     * アンケート1
     * @var string
     * @ORM\Column(name="survey_1", type="string", nullable=true, length=125)
     */
    private $survey_1;

    /**
     * アンケート2
     * @var string
     * @ORM\Column(name="survey_2", type="string", nullable=true, length=125)
     */
    private $survey_2;

    /**
     * その他の連絡先電話番号
     * @var string
     * @ORM\Column(name="sub_tel", type="string", nullable=true, length=13)
     */
    private $sub_tel;

    /**
     * DM購読
     * @var boolean
     * @ORM\Column(name="is_dm_subscription", type="boolean", nullable=true)
     */
    private $is_dm_subscription;

    /**
     * DM購読2
     * @var boolean
     * @ORM\Column(name="is_dm_subscription_2", type="boolean", nullable=true)
     */
    private $is_dm_subscription_2;

    /**
     * 会員区分
     * @var string
     * @ORM\Column(name="base_class", type="string", nullable=true, length=3)
     */
    private $base_class;

    /**
     * 配達種別
     * @var string
     * @ORM\Column(name="delivery_type", type="string", nullable=true, length=5)
     */
    private $delivery_type;

    // /**
    //  * 状態
    //  * @var integer
    //  * @ORM\Column(name="status", type="integer", nullable=true)
    //  */
    // private $status;

    /**
     * サイト識別子
     * @var integer
     * @ORM\Column(name="site_identifier", type="integer", nullable=true)
     */
    private $site_identifier;

    /**
     * 会員番号２
     * @var integer
     * @ORM\Column(name="user_id_2", type="integer", nullable=true)
     */
    private $user_id_2;

    /**
     * 注文種別
     * @var integer
     * @ORM\Column(name="order_type_id", type="integer", nullable=true)
     */
    private $order_type_id;

    /**
     * 入会種別
     * @var integer
     * @ORM\Column(name="admission_type_id", type="integer", nullable=true)
     */
    private $admission_type_id;

    /**
     * 決済種別
     * @var \Customize\Entity\Master\SettlementType
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\SettlementType", inversedBy="Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="settlement_type_id", referencedColumnName="id", nullable=true)
     * })
     */
    private $SettlementType;

    /**
     * WEB注文
     * @var integer
     * @ORM\Column(name="is_web_order", type="boolean", nullable=true)
     */
    private $is_web_order;

    /**
     * WEB注文
     * @var string
     * @ORM\Column(name="sa_code", type="string", nullable=true)
     */
    private $sa_code;

    /**
     * 配送週1
     * @var integer
     * @ORM\Column(name="delivery_week_1", type="integer", nullable=true)
     */
    private $delivery_week_1;

    /**
     * 配送週2
     * @var integer
     * @ORM\Column(name="delivery_week_2", type="integer", nullable=true)
     */
    private $delivery_week_2;

    /**
     * 配送曜日
     * @var string
     * @ORM\Column(name="delivery_week_day", type="string", nullable=true, length=1)
     */
    private $delivery_week_day;

    /**
     * コース名
     * @var string
     * @ORM\Column(name="course_name", type="string", nullable=true, length=1)
     */
    private $course_name;

    /**
     * DC名
     * @var string
     * @ORM\Column(name="delivery_code_name", type="string", nullable=true, length=12)
     */
    private $delivery_code_name;

    /**
     * SA紐付け
     * @var \Eccube\Entity\Member
     *
     * @ORM\ManyToOne(targetEntity="Eccube\Entity\Member", inversedBy="Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="member_id", referencedColumnName="id")
     * })
     */
    private $Member;

    /**
     * 時間帯
     * @var \Customize\Entity\Master\OrderTimeZone
     *
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\OrderTimeZone", inversedBy="Customer")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="order_time_zone_id", referencedColumnName="id")
     * })
     */
    private $OrderTimeZone;

    /**
     * 配達希望時間
     * @var string
     * @ORM\Column(name="delivery_preferred_time", type="string", nullable=true)
     */
    private $delivery_preferred_time;

    /**
     * 配達希望時間
     * @var string
     * @ORM\Column(name="no_consecutive_order_count", type="integer", nullable=true)
     */
    private $no_consecutive_order_count;

    /**
     * Call list note
     * @var string
     * @ORM\Column(name="call_list_note", type="string", nullable=true)
     */
    private $call_list_note;

    /**
     * Call map_page
     * @var string
     * @ORM\Column(name="map_page", type="string", nullable=true)
     */
    private $map_page;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="entry_date", type="datetimetz", nullable=true)
     */
    private $entry_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="withdraw_date", type="datetimetz", nullable=true)
     */
    private $withdraw_date;

    /**
     * 初回公募
     * initial_public_offering
     * @var string
     * @ORM\Column(name="initial_public_offering_id", type="integer", nullable=true)
     */
    private $initial_public_offering_id;

    /**
     * コールリスト掲載日
     * @var \DateTime
     *
     * @ORM\Column(name="call_list_public_date", type="datetimetz", nullable=true)
     */
    private $call_list_public_date;

    /**
     * 初回購入日
     * @var \DateTime
     * @ORM\Column(name="first_purchase_date", type="datetimetz", nullable=true)
     */
    private $first_purchase_date;

    /**
     * @var integer
     *
     * @ORM\Column(name="nonpayment_times", type="integer", nullable=true)
     */
    private $nonpayment_times;

    /**
     * Set addr03.
     *
     * @param null $value
     * @return Product
     */
    public function setAddr03($value = null)
    {
        $this->addr03 = $value;

        return $this;
    }

    /**
     * Get addr03.
     *
     * @return string|null
     */
    public function getAddr03()
    {
        return $this->addr03;
    }


    /**
     * Set department.
     *
     * @param null $value
     * @return Product
     */
    public function setDepartment($value = null)
    {
        $this->department = $value;

        return $this;
    }

    /**
     * Get department.
     *
     * @return string|null
     */
    public function getDepartment()
    {
        return $this->department;
    }

    /**
     * Set where_hear_about_this_site.
     *
     * @param null $value
     * @return Product
     */
    public function setWhereHearAboutThisSite($value = null)
    {
        $this->where_hear_about_this_site = $value;

        return $this;
    }

    /**
     * Get where_hear_about_this_site.
     *
     * @return string|null
     */
    public function getWhereHearAboutThisSite()
    {
        return $this->where_hear_about_this_site;
    }

    /**
     * Set survey_1.
     *
     * @param null $value
     * @return Product
     */
    public function setSurvey1($value = null)
    {
        $this->survey_1 = $value;

        return $this;
    }

    /**
     * Get survey_1.
     *
     * @return string|null
     */
    public function getSurvey1()
    {
        return $this->survey_1;
    }

    /**
     * Set survey_2.
     *
     * @param null $value
     * @return Product
     */
    public function setSurvey2($value = null)
    {
        $this->survey_2 = $value;

        return $this;
    }

    /**
     * Get survey_2.
     *
     * @return string|null
     */
    public function getSurvey2()
    {
        return $this->survey_2;
    }

    /**
     * Set sub_tel.
     *
     * @param null $value
     * @return Product
     */
    public function setSubTel($value = null)
    {
        $this->sub_tel = $value;

        return $this;
    }

    /**
     * Get sub_tel.
     *
     * @return string|null
     */
    public function getSubTel()
    {
        return $this->sub_tel;
    }

    /**
     * Set is_dm_subscription.
     *
     * @param null $value
     * @return Product
     */
    public function setIsDmSubscription($value = null)
    {
        $this->is_dm_subscription = $value;

        return $this;
    }

    /**
     * Get is_dm_subscription.
     *
     * @return string|null
     */
    public function getIsDmSubscription()
    {
        return $this->is_dm_subscription;
    }

    /**
     * Set is_dm_subscription_2.
     *
     * @param null $value
     * @return Product
     */
    public function setIsDmSubscription2($value = null)
    {
        $this->is_dm_subscription_2 = $value;

        return $this;
    }

    /**
     * Get is_dm_subscription_2.
     *
     * @return string|null
     */
    public function getIsDmSubscription2()
    {
        return $this->is_dm_subscription_2;
    }

    /**
     * Set base_class.
     *
     * @param null $value
     * @return Product
     */
    public function setBaseClass($value = null)
    {
        $this->base_class = $value;

        return $this;
    }

    /**
     * Get base_class.
     *
     * @return string|null
     */
    public function getBaseClass()
    {
        return $this->base_class;
    }

    /**
     * Set delivery_type.
     *
     * @param null $value
     * @return Product
     */
    public function setDeliveryType($value = null)
    {
        $this->delivery_type = $value;

        return $this;
    }

    /**
     * Get delivery_type.
     *
     * @return string|null
     */
    public function getDeliveryType()
    {
        return $this->delivery_type;
    }

    /**
     * Set status.
     *
     * @param null $value
     * @return Product
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
     * Set site_identifier.
     *
     * @param null $value
     * @return Product
     */
    public function setSiteIdentifier($value = null)
    {
        $this->site_identifier = $value;

        return $this;
    }

    /**
     * Get site_identifier.
     *
     * @return string|null
     */
    public function getSiteIdentifier()
    {
        return $this->site_identifier;
    }

    /**
     * Set user_id_2.
     *
     * @param null $value
     * @return Product
     */
    public function setUserId2($value = null)
    {
        $this->user_id_2 = $value;

        return $this;
    }

    /**
     * Get order_type_id.
     *
     * @return string|null
     */
    public function getUserId2()
    {
        return $this->user_id_2;
    }

    /**
     * Set order_type_id.
     *
     * @param null $value
     * @return Product
     */
    public function setOrderTypeId($value = null)
    {
        $this->order_type_id = $value;

        return $this;
    }

    /**
     * Get user_id_2.
     *
     * @return string|null
     */
    public function getOrderTypeId()
    {
        return $this->order_type_id;
    }

    /**
     * Set admission_type_id.
     *
     * @param null $value
     * @return Product
     */
    public function setAdmissionTypeId($value = null)
    {
        $this->admission_type_id = $value;

        return $this;
    }

    /**
     * Get admission_type_id.
     *
     * @return string|null
     */
    public function getAdmissionTypeId()
    {
        return $this->admission_type_id;
    }

    /**
     * Set SettlementType.
     *
     * @param \Customize\Entity\Master\SettlementType|null $settlementType
     *
     * @return Customer
     */
    public function setSettlementType(\Customize\Entity\Master\SettlementType $settlementType = null)
    {
        $this->SettlementType = $settlementType;

        return $this;
    }

    /**
     * Get SettlementType.
     *
     * @return \Customize\Entity\Master\SettlementType|null
     */
    public function getSettlementType()
    {
        return $this->SettlementType;
    }

    /**
     * Set is_web_order.
     *
     * @param null $value
     * @return Product
     */
    public function setIsWebOrder($value = null)
    {
        $this->is_web_order = $value;

        return $this;
    }

    /**
     * Get is_web_order.
     *
     * @return string|null
     */
    public function getIsWebOrder()
    {
        return $this->is_web_order;
    }

    /**
     * Set sa_code.
     *
     * @param null $value
     * @return Product
     */
    public function setSaCode($value = null)
    {
        $this->sa_code = $value;

        return $this;
    }

    /**
     * Get sa_code.
     *
     * @return string|null
     */
    public function getSaCode()
    {
        return $this->sa_code;
    }

    /**
     * Set delivery_week_1.
     *
     * @param null $value
     * @return Product
     */
    public function setDeliveryWeek1($value = null)
    {
        $this->delivery_week_1 = $value;

        return $this;
    }

    /**
     * Get delivery_week_1.
     *
     * @return string|null
     */
    public function getDeliveryWeek1()
    {
        return $this->delivery_week_1;
    }

    /**
     * Set delivery_week_2.
     *
     * @param null $value
     * @return Product
     */
    public function setDeliveryWeek2($value = null)
    {
        $this->delivery_week_2 = $value;

        return $this;
    }

    /**
     * Get delivery_week_2.
     *
     * @return string|null
     */
    public function getDeliveryWeek2()
    {
        return $this->delivery_week_2;
    }

    /**
     * Set delivery_week_day.
     *
     * @param null $value
     * @return Product
     */
    public function setDeliveryWeekDay($value = null)
    {
        $this->delivery_week_day = $value;

        return $this;
    }

    /**
     * Get delivery_week_day.
     *
     * @return string|null
     */
    public function getDeliveryWeekDay()
    {
        return $this->delivery_week_day;
    }

    /**
     * Set course_name.
     *
     * @param null $value
     * @return Product
     */
    public function setCourseName($value = null)
    {
        $this->course_name = $value;

        return $this;
    }

    /**
     * Get course_name.
     *
     * @return string|null
     */
    public function getCourseName()
    {
        return $this->course_name;
    }

    /**
     * Set delivery_code_name.
     *
     * @param null $value
     * @return Product
     */
    public function setDeliveryCodeName($value = null)
    {
        $this->delivery_code_name = $value;

        return $this;
    }

    /**
     * Get delivery_code_name.
     *
     * @return string|null
     */
    public function getDeliveryCodeName()
    {
        return $this->delivery_code_name;
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
        $this->Member = $member;

        return $this;
    }

    /**
     * Get member.
     *
     * @return \Eccube\Entity\Member|null
     */
    public function getMember()
    {
        return $this->Member;
    }

    /**
     * Set member_id
     *
     * @param string|null $value
     *
     * @return Member
     */
    public function setMemberId($value = null)
    {
        $this->member_id = $value;

        return $this;
    }

    /**
     * Get member_id.
     *
     * @return string|null
     */
    public function getMemberId()
    {
        return $this->member_id;
    }

    /**
     * Set OrderTimeZone.
     *
     * @param \Customize\Entity\Master\OrderTimeZone|null $timeZone
     *
     * @return Member
     */
    public function setOrderTimeZone(\Customize\Entity\Master\OrderTimeZone $timeZone = null)
    {
        $this->OrderTimeZone = $timeZone;

        return $this;
    }

    /**
     * Get member.
     *
     * @return \Customize\Entity\Master\OrderTimeZone|null
     */
    public function getOrderTimeZone()
    {
        return $this->OrderTimeZone;
    }

    /**
     * Set order_time_zone_id
     *
     * @param string|null $value
     *
     * @return integer
     */
    public function setOrderTimeZoneId($value = null)
    {
        $this->order_time_zone_id = $value;

        return $this;
    }

    /**
     * Get order_time_zone_id.
     *
     * @return string|null
     */
    public function getOrderTimeZoneId()
    {
        return $this->order_time_zone_id;
    }

    /**
     * Set delivery_preferred_time
     *
     * @param string|null $value
     *
     * @return Member
     */
    public function setDeliveryPreferredTime($value = null)
    {
        $this->delivery_preferred_time = $value;

        return $this;
    }

    /**
     * Get delivery_preferred_time.
     *
     * @return string|null
     */
    public function getDeliveryPreferredTime()
    {
        return $this->delivery_preferred_time;
    }

    /**
     * Set no_consecutive_order_count
     *
     * @param string|null $value
     *
     * @return Member
     */
    public function setNoConsecutiveOrderCount($value = null)
    {
        $this->no_consecutive_order_count = $value;

        return $this;
    }

    /**
     * Get no_consecutive_order_count.
     *
     * @return integer|null
     */
    public function getNoConsecutiveOrderCount()
    {
        return $this->no_consecutive_order_count;
    }

    /**
     * Set call_list_note
     *
     * @param string|null $value
     *
     * @return Customer
     */
    public function setCallListNote($value = null)
    {
        $this->call_list_note = $value;

        return $this;
    }

    /**
     * Get call_list_note.
     *
     * @return integer|null
     */
    public function getCallListNote()
    {
        return $this->call_list_note;
    }

    /**
     * Set map_page
     *
     * @param string|null $value
     *
     * @return Customer
     */
    public function setMapPage($value = null)
    {
        $this->map_page = $value;

        return $this;
    }

    /**
     * Get entry_date.
     *
     * @return string|null
     */
    public function getMapPage()
    {
        return $this->map_page;
    }

    /**
     * Set entry_date
     *
     * @param string|null $value
     *
     * @return Customer
     */
    public function setEntryDate($value = null)
    {
        $this->entry_date = $value;

        return $this;
    }

    /**
     * Get entry_date.
     *
     * @return string|null
     */
    public function getEntryDate()
    {
        return $this->entry_date;
    }

    /**
     * Set withdraw_date
     *
     * @param string|null $value
     * @return Customer
     */
    public function setWithdrawDate($value = null)
    {
        $this->withdraw_date = $value;

        return $this;
    }

    /**
     * Get withdraw_date.
     *
     * @return string|null
     */
    public function getWithdrawDate()
    {
        return $this->withdraw_date;
    }

    /**
     * Set initial_public_offering_id
     *
     * @param string|null $value
     * @return Customer
     */
    public function setInitialPublicOfferingId($value = null)
    {
        $this->initial_public_offering_id = $value;

        return $this;
    }

    /**
     * Get initial_public_offering_id.
     *
     * @return string|null
     */
    public function getInitialPublicOfferingId()
    {
        return $this->initial_public_offering_id;
    }

    /**
     * Set call_list_public_date
     *
     * @param string|null $value
     * @return Customer
     */
    public function setCallListPublicDate($value = null)
    {
        $this->call_list_public_date = $value;

        return $this;
    }

    /**
     * Get call_list_public_date.
     *
     * @return string|null
     */
    public function getCallListPublicDate()
    {
        return $this->call_list_public_date;
    }

    /**
     * Set first_purchase_date
     *
     * @param string|null $value
     * @return Customer
     */
    public function setFirstPurchaseDate($value = null)
    {
        $this->first_purchase_date = $value;

        return $this;
    }

    /**
     * Get first_purchase_date.
     *
     * @return string|null
     */
    public function getFirstPurchaseDate()
    {
        return $this->first_purchase_date;
    }

    /**
     * Set nonpayment_times
     *
     * @param string|null $value
     * @return Customer
     */
    public function setNonpaymentTimes($value = null)
    {
        $this->nonpayment_times = $value;

        return $this;
    }

    /**
     * Get nonpayment_times.
     *
     * @return string|null
     */
    public function getNonpaymentTimes()
    {
        return $this->nonpayment_times;
    }
}
