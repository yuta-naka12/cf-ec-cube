<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;


/**
 * Course
 *
 * @ORM\Table(name="dtb_course")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\Master\CourseRepository")
 */
class Course extends \Eccube\Entity\AbstractEntity
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
     * コース名
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * コース名略称
     * @var string
     *
     * @ORM\Column(name="short_name", type="string", nullable=true)
     */
    private $short_name;

    /**
     * 索引
     * @var integer
     *
     * @ORM\Column(name="sort_no", type="integer", nullable=true)
     */
    private $sort_no;

    /**
     * 納品書用電話番号
     * @var string
     *
     * @ORM\Column(name="delivery_tel", type="string", nullable=true)
     */
    private $delivery_tel;

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
     * @return Course
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
     * Set short_name
     *
     * @param string|null $brand_name
     *
     * @return Course
     */
    public function setShortName($value = null)
    {
        $this->short_name = $value;

        return $this;
    }

    /**
     * Get short_name.
     *
     * @return string|null
     */
    public function getShortName()
    {
        return $this->short_name;
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
     * Set delivery_tel
     *
     * @param integer|null $sort_no
     *
     * @return Course
     */
    public function setDeliveryTel($value = null)
    {
        $this->delivery_tel = $value;

        return $this;
    }

    /**
     * Get delivery_tel.
     *
     * @return integer|null
     */
    public function getDeliveryTel()
    {
        return $this->delivery_tel;
    }

}

