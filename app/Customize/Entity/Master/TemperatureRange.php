<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mtb_temperature_range")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\TemperatureRangeRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class TemperatureRange extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
