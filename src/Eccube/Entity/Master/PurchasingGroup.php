<?php

namespace Eccube\Entity\Master;

use Doctrine\ORM\Mapping as ORM;

if (!class_exists(PurchasingGroup::class, false)) {
    /**
     * PurchasingGroup
     *
     * @ORM\Table(name="mtb_purchasing_group")
     * @ORM\InheritanceType("SINGLE_TABLE")
     * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
     * @ORM\HasLifecycleCallbacks()
     * @ORM\Entity(repositoryClass="Eccube\Repository\Master\PurchasingGroupRepository")
     * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
     */
    class PurchasingGroup extends \Eccube\Entity\Master\AbstractMasterEntity
    {
    }
}
