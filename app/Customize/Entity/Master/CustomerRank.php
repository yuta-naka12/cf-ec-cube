<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;


/**
 * CustomerRank
 *
 * @ORM\Table(name="dtb_customer_rank")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\Master\CustomerRankRepository")
 */
class CustomerRank extends \Eccube\Entity\AbstractEntity
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
     * 会員ランク名
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * コース名略称
     * @var string
     *
     * @ORM\Column(name="short_name", type="integer", nullable=true)
     */
    private $class;

    /**
     * 索引
     * @var integer
     *
     * @ORM\Column(name="unit_price_rate", type="decimal", nullable=true, scale=3)
     */
    private $unit_price_rate;

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

}

