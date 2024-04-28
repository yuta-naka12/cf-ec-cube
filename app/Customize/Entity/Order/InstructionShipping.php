<?php

namespace Customize\Entity\Order;

use Doctrine\ORM\Mapping as ORM;


/**
 * Course
 *
 */
class InstructionShipping extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var int
     *
     */
    private $delivery_date;

    /**
     * コース名
     * @var string
     *
     */
    private $instruction_date;


    /**
     * Set delivery_date
     *
     * @param string|null $value
     *
     * @return
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
     * Set instruction_date
     *
     * @param string|null $value
     *
     * @return InstructionShipping
     */
    public function setInstructionDate($value = null)
    {
        $this->instruction_date = $value;

        return $this;
    }

    /**
     * Get instruction_date.
     *
     * @return string|null
     */
    public function getInstructionDate()
    {
        return $this->instruction_date;
    }

}

