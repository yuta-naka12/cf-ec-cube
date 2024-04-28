<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mtb_broad_category")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\BroadCategoryRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class BroadCategory extends \Eccube\Entity\Master\AbstractMasterEntity
{
}
