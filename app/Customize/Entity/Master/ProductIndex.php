<?php

namespace Customize\Entity\Master;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mtb_product_index")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\ProductIndexRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class ProductIndex extends \Eccube\Entity\Master\AbstractMasterEntity
{
} 