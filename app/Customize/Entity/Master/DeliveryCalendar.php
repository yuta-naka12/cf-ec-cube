<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;


/**
 * DeliveryCalendar
 *
 * @ORM\Table(name="dtb_delivery_calendar")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\Master\DeliveryCalendarRepository")
 */
class DeliveryCalendar extends \Eccube\Entity\AbstractEntity
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
     * 年度
     * @var integer
     *
     * @ORM\Column(name="year", type="integer", nullable=true)
     */
    private $year;

    /**
     * 月
     * @var integer
     *
     * @ORM\Column(name="month", type="integer", nullable=true)
     */
    private $month;

    /**
     * 配送週
     * @var integer
     *
     * @ORM\Column(name="delivery_week", type="integer", nullable=true)
     */
    private $delivery_week;

    /**
     * 配送曜日
     * @var string
     *
     * @ORM\Column(name="delivery_day", type="string", nullable=true)
     */
    private $delivery_day;

    /**
     * 注文締日
     * @var \DateTime
     *
     * @ORM\Column(name="order_closing_date", type="date", nullable=true)
     */
    private $order_closing_date;

    /**
     * 注文日
     * @var \DateTime
     *
     * @ORM\Column(name="order_data", type="date", nullable=true)
     */
    private $order_data;

    /**
     * 出荷予定日
     * @var \DateTime
     *
     * @ORM\Column(name="estimated_shipping_date", type="date", nullable=true)
     */
    private $estimated_shipping_date;

    /**
     * 配達日
     * @var \DateTime
     *
     * @ORM\Column(name="delivery_date", type="date", nullable=true)
     */
    private $delivery_date;

    /**
     * ドライアイス係数
     * @var integer
     *
     * @ORM\Column(name="dry_ice_factor", type="integer", nullable=true)
     */
    private $dry_ice_factor;

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
     * Get year.
     *
     * @return integer|null
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set year
     *
     * @param integer|null $year
     *
     * @return DeliveryCalendar
     */
    public function setYear($value = null)
    {
        $this->year = $value;

        return $this;
    }

    /**
     * Get month.
     *
     * @return integer|null
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set month
     *
     * @param integer|null $month
     *
     * @return DeliveryCalendar
     */
    public function setMonth($value = null)
    {
        $this->month = $value;

        return $this;
    }

    /**
     * Get delivery_week.
     *
     * @return integer|null
     */
    public function getDeliveryWeek()
    {
        return $this->delivery_week;
    }

    /**
     * Set delivery_week
     *
     * @param integer|null $delivery_week
     *
     * @return DeliveryCalendar
     */
    public function setDeliveryWeek($value = null)
    {
        $this->delivery_week = $value;

        return $this;
    }

    /**
     * Get delivery_day.
     *
     * @return integer|null
     */
    public function getDeliveryDay()
    {
        return $this->delivery_day;
    }

    /**
     * Set delivery_day
     *
     * @param integer|null $delivery_day
     *
     * @return DeliveryCalendar
     */
    public function setDeliveryDay($value = null)
    {
        $this->delivery_day = $value;

        return $this;
    }

    /**
     * Get order_closing_date.
     *
     * @return integer|null
     */
    public function getOrderClosingDate()
    {
        return $this->order_closing_date;
    }

    /**
     * Set order_closing_date
     *
     * @param integer|null $order_closing_date
     *
     * @return DeliveryCalendar
     */
    public function setOrderClosingDate($value = null)
    {
        $this->order_closing_date = $value;

        return $this;
    }

    /**
     * Get order_data.
     *
     * @return integer|null
     */
    public function getOrderData()
    {
        return $this->order_data;
    }

    /**
     * Set order_data
     *
     * @param integer|null $order_data
     *
     * @return DeliveryCalendar
     */
    public function setOrderData($value = null)
    {
        $this->order_data = $value;

        return $this;
    }

    /**
     * Get estimated_shipping_date.
     *
     * @return integer|null
     */
    public function getEstimatedShippingDate()
    {
        return $this->estimated_shipping_date;
    }

    /**
     * Set estimated_shipping_date
     *
     * @param integer|null $estimated_shipping_date
     *
     * @return DeliveryCalendar
     */
    public function setEstimatedShippingDate($value = null)
    {
        $this->estimated_shipping_date = $value;

        return $this;
    }

    /**
     * Get delivery_date.
     *
     * @return integer|null
     */
    public function getDeliveryDate()
    {
        return $this->estimated_shipping_date;
    }

    /**
     * Set delivery_date
     *
     * @param integer|null $delivery_date
     *
     * @return DeliveryCalendar
     */
    public function setDeliveryDate($value = null)
    {
        $this->delivery_date = $value;

        return $this;
    }

    /**
     * Get dry_ice_factor.
     *
     * @return integer|null
     */
    public function getDryIceFactor()
    {
        return $this->estimated_shipping_date;
    }

    /**
     * Set dry_ice_factor
     *
     * @param integer|null $dry_ice_factor
     *
     * @return DeliveryCalendar
     */
    public function setDryIceFactor($value = null)
    {
        $this->dry_ice_factor = $value;

        return $this;
    }

    /**
     * Set sort_no
     *
     * @param integer|null $sort_no
     *
     * @return DeliveryCalendar
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

