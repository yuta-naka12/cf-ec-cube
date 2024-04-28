<?php

namespace Customize\Entity\Product;

#DBにアクセスするためのライブラリなどを読み込み
use Customize\Entity\Master\IntroduceGood;
use Customize\Entity\Master\NewProductCategory;
use Customize\Entity\Master\SubscriptionPurchase;
use Customize\Entity\Master\TemperatureRange;
use Customize\Entity\Setting\PurchaseGroup;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation as Eccube;
use Eccube\Annotation\EntityExtension;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductImage;

#拡張をする対象エンティティの指定
/**
 * @Eccube\EntityExtension("Eccube\Entity\ProductClass")
 */


trait ProductClassTrait //ファイル名と合わせる
{
    /**
     * @var string
     *
     * @ORM\Column(name="price02", type="decimal", precision=12, scale=2, nullable=true)
     */
    private $price02;

     /**
      * @var string|null
      *
      * @ORM\Column(name="product_code", type="string", length=255, nullable=true)
      */
     private $code;

    /**
     * Set price02.
     *
     * @param string $price02
     *
     * @return ProductClass
     */
    public function setPrice02($price02)
    {
        $this->price02 = $price02;

        return $this;
    }

    /**
     * Get price02.
     *
     * @return string
     */
    public function getPrice02()
    {
        return $this->price02;
    }

     /**
      * Set code.
      *
      * @param string|null $code
      *
      * @return ProductClass
      */
     public function setCode($code = null)
     {
         $this->code = $code;

         return $this;
     }

    /**
     * Get code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }
}
