<?php

namespace Customize\Entity\Master;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\Product;

/**
 * BulkBuying
 *
 * @ORM\Table(name="dtb_bulk_buying")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="discriminator_type", type="string", length=255)
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity(repositoryClass="Eccube\Repository\Master\BulkBuyingRepository")
 */
class BulkBuying extends \Eccube\Entity\AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * まとめ買いグループ名
     * @var string
     *
     * @ORM\Column(name="name", type="string", nullable=true)
     */
    private $name;

    /**
     * コース名略称
     * @var string
     *
     * @ORM\Column(name="short_name", type="integer", nullable=true)
     */
    private $class;

    /**
     * コース名略称
     * @var int
     *
     * @ORM\Column(name="discount_rate", type="integer", nullable=true)
     */
    private $discount_rate;

    /**
     * コース名略称
     * @var int
     *
     * @ORM\Column(name="quantity", type="integer", nullable=true)
     */
    private $quantity;

    /**
     * 索引
     * @var integer
     *
     * @ORM\Column(name="sort_no", type="integer", nullable=true)
     */
    private $sort_no;

    /**
     * @var \Doctrine\Common\Collections\Collection
     * 
     * @ORM\OneToMany(targetEntity="Eccube\Entity\Product", mappedBy="BulkBuying")
     */
    private $Product;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->Product = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set name
     *
     * @param string|null $name
     *
     * @return Course
     */
    public function setName($value = null)
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set class
     *
     * @param integer|null $class
     *
     * @return Course
     */
    public function setClass($value = null)
    {
        $this->class = $value;

        return $this;
    }

    /**
     * Get discount_rate.
     *
     * @return string|null
     */
    public function getDiscountRate()
    {
        return $this->discount_rate;
    }

    /**
     * Set discount_rate
     *
     * @param integer|null $discount_rate
     *
     * @return Course
     */
    public function setDiscountRate($value = null)
    {
        $this->discount_rate = $value;

        return $this;
    }

    /**
     * Get quantity.
     *
     * @return string|null
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * Set quantity
     *
     * @param integer|null $quantity
     *
     * @return Course
     */
    public function setQuantity($value = null)
    {
        $this->quantity = $value;

        return $this;
    }








    /**
     * Get class
     *
     * @return integer|null
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set sort_no
     *
     * @param integer|null $sort_no
     *
     * @return Course
     */
    public function setSortNo($value = null)
    {
        $this->sort_no = $value;

        return $this;
    }

    /**
     * Get sort_no.
     *
     * @return integer|null
     */
    public function getSortNo()
    {
        return $this->sort_no;
    }


    /**
     * Add Product.
     *
     * @param \Eccube\Entity\Product $product
     *
     * @return ProductBrand
     */
    public function addProduct(Product $product)
    {
        $this->Product[] = $product;

        return $this;
    }

    /**
     * Remove Product.
     *
     * @param \Eccube\Entity\Product $product
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeProduct(Product $product)
    {
        return $this->Product->removeElement($product);
    }

    /**
     * Get Product.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    // public function getProduct()
    // {
    //     return $this->Product;
    // }

    public function getProducts()
    {
        $products = array();

        foreach ($this->Product as $product) {
            $products[] = $product;
        }

        return $products;
    }

}

