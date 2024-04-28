<?php

namespace Customize\Entity\Master;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="mtb_list_page")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Customize\Repository\Master\ListPageRepository")
 * @ORM\Cache(usage="NONSTRICT_READ_WRITE")
 */
class ListPage extends \Eccube\Entity\Master\AbstractMasterEntity
{
} 