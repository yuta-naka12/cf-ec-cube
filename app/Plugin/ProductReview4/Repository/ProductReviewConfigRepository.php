<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductReview4\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\ProductReview4\Entity\ProductReviewConfig;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ProductReview Config.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductReviewConfigRepository extends AbstractRepository
{
    /**
     * ProductReviewConfigRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductReviewConfig::class);
    }

    /**
     * @param int $id
     *
     * @return ProductReviewConfig|null
     */
    public function get($id = 1)
    {
        return $this->find($id);
    }
}
