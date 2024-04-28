<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;


/**
 * Course
 *
 * @ORM\Table(name="mtb_withdraw_reason")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\WithdrawalReasonRepository")
 */
class WithdrawalReason extends \Eccube\Entity\Master\AbstractMasterEntity
{
}

