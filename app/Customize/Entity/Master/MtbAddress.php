<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * CustomerRank
 *
 * @ORM\Table(name="dtb_address")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Admin\Master\MtbAddressRepository")
 */
class MtbAddress extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * DCコード
     * @Groups("mtb_address")
     * @var string
     *
     * @ORM\Column(name="dc_code", type="string", nullable=true)
     */
    private $dc_code;

    /**
     * 該当住所
     * @var string

     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * コースコード
     * @Groups("mtb_address")
     * @var \Doctrine\Common\Collections\Collection
     * @ORM\ManyToOne(targetEntity="Customize\Entity\Master\CourseMaster", inversedBy="MtbAddress")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="course_id", referencedColumnName="id")
     * })
     */
    private $CourseMaster;

    /**
     * 留守宅率
     * @var string
     * @ORM\Column(name="absence_rate", type="decimal", precision=10, scale=0, options={"unsigned":true}, nullable=true)
     */
    private $absence_rate;

    /**
     * 配送週1
     * @Groups("mtb_address")
     * @var string
     * @ORM\Column(name="delivery_week_1", type="integer", nullable=true)
     */
    private $delivery_week_1;

    /**
     * 配送週2
     * @Groups("mtb_address")
     * @var string
     * @ORM\Column(name="delivery_week_2", type="integer", nullable=true)
     */
    private $delivery_week_2;

    /**
     * 計算項目
     * @Groups("mtb_address")
     * @var string
     * @ORM\Column(name="calculation_item", type="string", nullable=true)
     */
    private $calculation_item;

    /**
     * 配送順序
     * @var integer
     * @ORM\Column(name="delivery_index", type="integer", nullable=true)
     */
    private $delivery_index;

    /**
     * 配送者コード
     * @var integer
     * @ORM\Column(name="driver_code", type="integer", nullable=true)
     */
    private $driver_code;

    /**
     * 詰込地区コード
     * @var integer
     * @ORM\Column(name="packing_district_area_code", type="integer", nullable=true)
     */
    private $packing_district_area_code;

    /**
     * 倉庫出庫区分
     * @var string
     * @ORM\Column(name="warehouse_export_classification", type="string", nullable=true)
     */
    private $warehouse_export_classification;

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
     * Set dc_code
     *
     * @param string|null $value
     *
     * @return Course
     */
    public function setDcCode($value = null)
    {
        $this->dc_code = $value;

        return $this;
    }

    /**
     * Get dc_code.
     *
     * @return string|null
     */
    public function getDcCode()
    {
        return $this->dc_code;
    }

    /**
     * Set name
     *
     * @param string|null $value
     *
     * @return Course
     */
    public function setName($value = null)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set CourseMaster.
     *
     * @param CourseMaster|null $value
     *
     * @return Product
     */
    public function setCourseMaster($value = null)
    {
        $this->CourseMaster = $value;

        return $this;
    }

    /**
     * Get CourseMaster.
     *
     * @return CourseMaster|null
     */
    public function getCourseMaster()
    {
        return $this->CourseMaster;
    }

    /**
     * Set class
     *
     * @param integer|null $class
     *
     * @return Course
     */
    public function setClass($value = null)
    {
        $this->class = $value;

        return $this;
    }

    /**
     * Get class
     *
     * @return integer|null
     */
    public function getClass()
    {
        return $this->class;
    }


    /**
     * Set unit_price_rate
     *
     * @param double|null $unit_price_rate
     *
     * @return Course
     */
    public function setUnitPriceRate($value = null)
    {
        $this->unit_price_rate = $value;

        return $this;
    }

    /**
     * Get unit_price_rate
     *
     * @return double|null
     */
    public function getUnitPriceRate()
    {
        return $this->unit_price_rate;
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

    /**
     * Set absence_rate
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setAbsenceRate($value = null)
    {
        $this->absence_rate = $value;

        return $this;
    }

    /**
     * Get absence_rate
     *
     * @return double|null
     */
    public function getAbsenceRate()
    {
        return $this->absence_rate;
    }

    /**
     * Set delivery_week_1
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setDeliveryWeek1($value = null)
    {
        $this->delivery_week_1 = $value;

        return $this;
    }

    /**
     * Get delivery_week_1
     *
     * @return double|null
     */
    public function getDeliveryWeek1()
    {
        return $this->delivery_week_1;
    }

    /**
     * Set delivery_week_2
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setDeliveryWeek2($value = null)
    {
        $this->delivery_week_2 = $value;

        return $this;
    }

    /**
     * Get delivery_week_2
     *
     * @return double|null
     */
    public function getDeliveryWeek2()
    {
        return $this->delivery_week_2;
    }

    /**
     * Set calculation_item
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setCalculationItem($value = null)
    {
        $this->calculation_item = $value;

        return $this;
    }

    /**
     * Get calculation_item
     *
     * @return double|null
     */
    public function getCalculationItem()
    {
        return $this->calculation_item;
    }

    /**
     * Set delivery_index
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setDeliveryIndex($value = null)
    {
        $this->delivery_index = $value;

        return $this;
    }

    /**
     * Get delivery_index
     *
     * @return double|null
     */
    public function getDeliveryIndex()
    {
        return $this->delivery_index;
    }

    /**
     * Set driver_code
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setDriverCode($value = null)
    {
        $this->driver_code = $value;

        return $this;
    }

    /**
     * Get driver_code
     *
     * @return double|null
     */
    public function getDriverCode()
    {
        return $this->driver_code;
    }

    /**
     * Set packing_district_area_code
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setPackingDistrictAreaCode($value = null)
    {
        $this->packing_district_area_code = $value;

        return $this;
    }

    /**
     * Get packing_district_area_code
     *
     * @return double|null
     */
    public function getPackingDistrictAreaCode()
    {
        return $this->packing_district_area_code;
    }

    /**
     * Set warehouse_export_classification
     *
     * @param double|null $value
     *
     * @return Course
     */
    public function setWarehouseExportClassification($value = null)
    {
        $this->warehouse_export_classification = $value;

        return $this;
    }

    /**
     * Get warehouse_export_classification
     *
     * @return double|null
     */
    public function getWarehouseExportClassification()
    {
        return $this->warehouse_export_classification;
    }
}

