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

namespace Customize\Repository\Admin\Product;

use Customize\Entity\Product\ProductTopic;
use Eccube\Repository\AbstractRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ProductTopicRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductTopicRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductTopic::class);
    }


        /**
     * ブランドの表示順を一つ上げる.
     *
     * @param ProductTopic $ProductTopic
     *
     * @throws \Exception 更新対象のブランドより上位のブランドが存在しない場合.
     */
    public function up(ProductTopic $ProductTopic)
    {
        $sortNo = $ProductTopic->getSortNo();
        $ProductTopic2 = $this->findOneBy(['sort_no' => $sortNo + 1]);

        if (!$ProductTopic2) {
            throw new \Exception(sprintf('%s より上位のブランドが存在しません.', $ProductTopic->getId()));
        }

        $ProductTopic->setSortNo($sortNo + 1);
        $ProductTopic2->setSortNo($sortNo);

        $em = $this->getEntityManager();
        $em->persist($ProductTopic);
        $em->persist($ProductTopic2);
        $em->flush();
    }

    /**
     * ブランドの表示順を一つ下げる.
     *
     * @param ProductTopic $ProductTopic
     *
     * @throws \Exception 更新対象のブランドより下位のブランドが存在しない場合.
     */
    public function down(ProductTopic $ProductTopic)
    {
        $sortNo = $ProductTopic->getSortNo();
        $ProductTopic2 = $this->findOneBy(['sort_no' => $sortNo - 1]);

        if (!$ProductTopic2) {
            throw new \Exception(sprintf('%s より下位のブランドが存在しません.', $ProductTopic->getId()));
        }

        $ProductTopic->setSortNo($sortNo - 1);
        $ProductTopic2->setSortNo($sortNo);

        $em = $this->getEntityManager();
        $em->persist($ProductTopic);
        $em->persist($ProductTopic2);
        $em->flush();
    }

    /**
     * 登録.
     *
     * @param ProductTopic $ProductTopic
     */
    public function save($ProductTopic)
    {
        if (!$ProductTopic->getId()) {
            $sortNo = $this->createQueryBuilder('c')
                ->select('COALESCE(MAX(c.sort_no), 0)')
                ->getQuery()
                ->getSingleScalarResult();
            $ProductTopic
                ->setSortNo($sortNo + 1);
        }

        $em = $this->getEntityManager();
        $em->persist($ProductTopic);
        $em->flush();
    }

    /**
     * 削除.
     *
     * @param ProductTopic $ProductTopic
     *
     * @throws ForeignKeyConstraintViolationException 外部キー制約違反の場合
     * @throws DriverException SQLiteの場合, 外部キー制約違反が発生すると, DriverExceptionをthrowします.
     */
    public function delete($ProductTopic)
    {
        $this->createQueryBuilder('c')
            ->update()
            ->set('c.sort_no', 'c.sort_no - 1')
            ->where('c.sort_no > :sort_no')
            ->setParameter('sort_no', $ProductTopic->getSortNo())
            ->getQuery()
            ->execute();

        $em = $this->getEntityManager();

        $em->flush();

        $em->remove($ProductTopic);
        $em->flush();
    }
}