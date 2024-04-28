<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;


/**
 * FinancialInstitution
 *
 * @ORM\Table(name="dtb_financial_institution")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\Master\FinancialInstitutionRepository")
 */
class FinancialInstitution extends \Eccube\Entity\AbstractEntity
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
     * 金融機関名
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * 略称
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
     * @return FinancialInstitution
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
     * @return FinancialInstitution
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

