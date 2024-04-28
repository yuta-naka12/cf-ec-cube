<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
#DBにアクセスするためのライブラリなどを読み込み
use Eccube\Annotation as Eccube;
use Eccube\Entity\DeliveryFee;

/**
 * @Eccube\EntityExtension("\Eccube\Entity\DeliveryFee")
 */

trait DeliveryFeeTrait //ファイル名と合わせる
{
    /**
     * 割引送料
     * @var integer
     *
     * @ORM\Column(name="campaign_fee", type="integer", nullable=true)
     */
    private $campaign_fee;

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
     * Set fee
     *
     * @param integer|null $fee
     *
     * @return DeliveryFeeTrait
     */
    public function setFee($value = null)
    {
        $this->fee = $value;

        return $this;
    }

    /**
     * Set campaign_fee
     *
     * @param integer|null $campaign_fee
     *
     * @return DeliveryFeeTrait
     */
    public function setCampaignFee($value = null)
    {
        $this->campaign_fee = $value;

        return $this;
    }

    /**
     * Get campaign_fee.
     *
     * @return integer|null
     */
    public function getCampaignFee()
    {
        return $this->campaign_fee;
    }
}
