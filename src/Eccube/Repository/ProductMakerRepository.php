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

namespace Eccube\Repository;

use Customize\Entity\Product\ProductMaker;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ProductMakerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductMakerRepository extends AbstractRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductMaker::class);
    }

    /**
     * メーカーの表示順を一つ上げる.
     *
     * @param ProductMaker $ProductMaker
     *
     * @throws \Exception 更新対象のメーカーより上位のメーカーが存在しない場合.
     */
    public function up(ProductMaker $ProductMaker)
    {
        $sortNo = $ProductMaker->getSortNo();
        $ProductMaker2 = $this->findOneBy(['sort_no' => $sortNo + 1]);

        if (!$ProductMaker2) {
            throw new \Exception(sprintf('%s より上位のメーカーが存在しません.', $ProductMaker->getId()));
        }

        $ProductMaker->setSortNo($sortNo + 1);
        $ProductMaker2->setSortNo($sortNo);

        $em = $this->getEntityManager();
        $em->persist($ProductMaker);
        $em->persist($ProductMaker2);
        $em->flush();
    }

    /**
     * メーカーの表示順を一つ下げる.
     *
     * @param ProductMaker $ProductMaker
     *
     * @throws \Exception 更新対象のメーカーより下位のメーカーが存在しない場合.
     */
    public function down(ProductMaker $ProductMaker)
    {
        $sortNo = $ProductMaker->getSortNo();
        $ProductMaker2 = $this->findOneBy(['sort_no' => $sortNo - 1]);

        if (!$ProductMaker2) {
            throw new \Exception(sprintf('%s より下位のメーカーが存在しません.', $ProductMaker->getId()));
        }

        $ProductMaker->setSortNo($sortNo - 1);
        $ProductMaker2->setSortNo($sortNo);

        $em = $this->getEntityManager();
        $em->persist($ProductMaker);
        $em->persist($ProductMaker2);
        $em->flush();
    }

    /**
     * 登録.
     *
     * @param ProductMaker $ProductMaker
     */
    public function save($ProductMaker)
    {
        if (!$ProductMaker->getId()) {
            $sortNo = $this->createQueryBuilder('c')
                ->select('COALESCE(MAX(c.sort_no), 0)')
                ->getQuery()
                ->getSingleScalarResult();
            $ProductMaker
                ->setSortNo($sortNo + 1);
        }

        $em = $this->getEntityManager();
        $em->persist($ProductMaker);
        $em->flush();
    }

    /**
     * 削除.
     *
     * @param ProductMaker $ProductMaker
     *
     * @throws ForeignKeyConstraintViolationException 外部キー制約違反の場合
     * @throws DriverException SQLiteの場合, 外部キー制約違反が発生すると, DriverExceptionをthrowします.
     */
    public function delete($ProductMaker)
    {
        $this->createQueryBuilder('c')
            ->update()
            ->set('c.sort_no', 'c.sort_no - 1')
            ->where('c.sort_no > :sort_no')
            ->setParameter('sort_no', $ProductMaker->getSortNo())
            ->getQuery()
            ->execute();

        $em = $this->getEntityManager();

        $em->flush();

        $em->remove($ProductMaker);
        $em->flush();
    }
}
