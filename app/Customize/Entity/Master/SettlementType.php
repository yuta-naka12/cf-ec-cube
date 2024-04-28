<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * SettlementType
 *
 * @ORM\Table(name="dtb_settlement_type")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\Master\SettlementTypeRepository")
 */
class SettlementType extends \Eccube\Entity\AbstractEntity
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
     * 決済種別名
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * 入力区分
     * @var integer
     *
     * @ORM\Column(name="class", type="integer", nullable=true)
     */
    private $class;

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
     * @return SettlementType
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
     * @return SettlementType
     */
    public function setClass($value = null)
    {
        $this->class = $value;

        return $this;
    }

    /**
     * Get class.
     *
     * @return integer|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set sort_no
     *
     * @param integer|null $sort_no
     *
     * @return FinancialInstitution
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

