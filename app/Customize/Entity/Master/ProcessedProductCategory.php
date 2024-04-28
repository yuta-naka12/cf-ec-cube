<?php

namespace Customize\Entity\Master;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mtb_processed_product_category")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\ProcessedProductCategoryRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class ProcessedProductCategory extends \Eccube\Entity\Master\AbstractMasterEntity
{
} 