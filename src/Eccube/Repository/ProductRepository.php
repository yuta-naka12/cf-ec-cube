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

use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Common\EccubeConfig;
use Eccube\Doctrine\Query\Queries;
use Eccube\Entity\Product;
use Eccube\Entity\ProductStock;
use Eccube\Util\StringUtil;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends AbstractRepository
{
    /**
     * @var Queries
     */
    protected $queries;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    public const COLUMNS = [
        'product_id' => 'p.id', 'name' => 'p.name', 'product_code' => 'pc.code', 'stock' => 'pc.stock', 'status' => 'p.Status', 'create_date' => 'p.create_date', 'update_date' => 'p.update_date'
    ];

    /**
     * ProductRepository constructor.
     *
     * @param RegistryInterface $registry
     * @param Queries $queries
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        RegistryInterface $registry,
        Queries $queries,
        EccubeConfig $eccubeConfig
    ) {
        parent::__construct($registry, Product::class);
        $this->queries = $queries;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * Find the Product with sorted ClassCategories.
     *
     * @param integer $productId
     *
     * @return Product
     */
    public function findWithSortedClassCategories($productId)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->addSelect(['pc', 'cc1', 'cc2', 'pi', 'pt'])
            ->innerJoin('p.ProductClasses', 'pc')
            ->leftJoin('pc.ClassCategory1', 'cc1')
            ->leftJoin('pc.ClassCategory2', 'cc2')
            ->leftJoin('p.ProductImage', 'pi')
            ->leftJoin('p.ProductTag', 'pt')
            ->where('p.id = :id')
            ->andWhere('pc.visible = :visible')
            ->setParameter('id', $productId)
            ->setParameter('visible', true)
            ->orderBy('cc1.sort_no', 'DESC')
            ->addOrderBy('cc2.sort_no', 'DESC');

        $product = $qb
            ->getQuery()
            ->getSingleResult();

        return $product;
    }

    /**
     * Find the Products with sorted ClassCategories.
     *
     * @param array $ids Product in ids
     * @param string $indexBy The index for the from.
     *
     * @return ArrayCollection|array
     */
    public function findProductsWithSortedClassCategories(array $ids, $indexBy = null)
    {
        if (count($ids) < 1) {
            return [];
        }
        $qb = $this->createQueryBuilder('p', $indexBy);
        $qb->addSelect(['pc', 'cc1', 'cc2', 'pi', 'pt', 'tr', 'ps'])
            ->innerJoin('p.ProductClasses', 'pc')
            // XXX Joined 'TaxRule' and 'ProductStock' to prevent lazy loading
            ->leftJoin('pc.TaxRule', 'tr')
            ->innerJoin('pc.ProductStock', 'ps')
            ->leftJoin('pc.ClassCategory1', 'cc1')
            ->leftJoin('pc.ClassCategory2', 'cc2')
            ->leftJoin('p.ProductImage', 'pi')
            ->leftJoin('p.ProductTag', 'pt')
            ->where($qb->expr()->in('p.id', $ids))
            ->andWhere('pc.visible = :visible')
            ->setParameter('visible', true)
            ->orderBy('cc1.sort_no', 'DESC')
            ->addOrderBy('cc2.sort_no', 'DESC');

        $products = $qb
            ->getQuery()
            ->useResultCache(true, $this->eccubeConfig['eccube_result_cache_lifetime_short'])
            ->getResult();

        return $products;
    }

    /**
     * get query builder.
     *
     * @param  array $searchData
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderBySearchData($searchData)
    {
        $qb = $this->createQueryBuilder('p')
            ->andWhere('p.Status = 1');

        // category
        $categoryJoin = false;
        if (!empty($searchData['category_id']) && $searchData['category_id']) {
            $Categories = $searchData['category_id']->getSelfAndDescendants();
            if ($Categories) {
                $qb
                    ->innerJoin('p.ProductCategories', 'pct')
                    ->innerJoin('pct.Category', 'c')
                    ->andWhere($qb->expr()->in('pct.Category', ':Categories'))
                    ->setParameter('Categories', $Categories);
                $categoryJoin = true;
            }
        }

        // name
        if (isset($searchData['name']) && StringUtil::isNotBlank($searchData['name'])) {
            $keywords = preg_split('/[\s　]+/u', str_replace(['%', '_'], ['\\%', '\\_'], $searchData['name']), -1, PREG_SPLIT_NO_EMPTY);

            foreach ($keywords as $index => $keyword) {
                $key = sprintf('keyword%s', $index);
                $qb
                    ->andWhere(sprintf(
                        'NORMALIZE(p.name) LIKE NORMALIZE(:%s) OR
                        NORMALIZE(p.search_word) LIKE NORMALIZE(:%s) OR
                        EXISTS (SELECT wpc%d FROM \Eccube\Entity\ProductClass wpc%d WHERE p = wpc%d.Product AND NORMALIZE(wpc%d.code) LIKE NORMALIZE(:%s))',
                        $key,
                        $key,
                        $index,
                        $index,
                        $index,
                        $index,
                        $key
                    ))
                    ->setParameter($key, '%' . $keyword . '%');
            }
        }

        // Order By
        // 価格低い順
        $config = $this->eccubeConfig;
        if (!empty($searchData['orderby']) && $searchData['orderby']->getId() == $config['eccube_product_order_price_lower']) {
            //@see http://doctrine-orm.readthedocs.org/en/latest/reference/dql-doctrine-query-language.html
            $qb->addSelect('MIN(pc.price02) as HIDDEN price02_min');
            $qb->innerJoin('p.ProductClasses', 'pc');
            $qb->andWhere('pc.visible = true');
            $qb->groupBy('p.id');
            $qb->orderBy('price02_min', 'ASC');
            $qb->addOrderBy('p.id', 'DESC');
            // 価格高い順
        } elseif (!empty($searchData['orderby']) && $searchData['orderby']->getId() == $config['eccube_product_order_price_higher']) {
            $qb->addSelect('MAX(pc.price02) as HIDDEN price02_max');
            $qb->innerJoin('p.ProductClasses', 'pc');
            $qb->andWhere('pc.visible = true');
            $qb->groupBy('p.id');
            $qb->orderBy('price02_max', 'DESC');
            $qb->addOrderBy('p.id', 'DESC');
            // 新着順
        } elseif (!empty($searchData['orderby']) && $searchData['orderby']->getId() == $config['eccube_product_order_newer']) {
            // 在庫切れ商品非表示の設定が有効時対応
            // @see https://github.com/EC-CUBE/ec-cube/issues/1998
            if ($this->getEntityManager()->getFilters()->isEnabled('option_nostock_hidden') == true) {
                $qb->innerJoin('p.ProductClasses', 'pc');
                $qb->andWhere('pc.visible = true');
            }
            $qb->orderBy('p.create_date', 'DESC');
            $qb->addOrderBy('p.id', 'DESC');
        } else {
            if ($categoryJoin === false) {
                $qb
                    ->leftJoin('p.ProductCategories', 'pct')
                    ->leftJoin('pct.Category', 'c');
            }
            $qb
                ->addOrderBy('p.id', 'DESC');
        }

        return $this->queries->customize(QueryKey::PRODUCT_SEARCH, $qb, $searchData);
    }

    /**
     * get query builder.
     *
     * @param  array $searchData
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderBySearchDataForAdmin($searchData)
    {
        $qb = $this->createQueryBuilder('p');

        // id
        if (isset($searchData['name_code']) && StringUtil::isNotBlank($searchData['name_code'])) {
            $name_code = preg_match('/^\d{0,10}$/', $searchData['name_code']) ? $searchData['name_code'] : null;
            if ($name_code && $name_code > '2147483647' && $this->isPostgreSQL()) {
                $name_code = null;
            }
            $qb
                ->andWhere('p.name LIKE :likeid OR p.code LIKE :likeid')
                ->setParameter('likeid', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $searchData['name_code']) . '%');
        }

        // 索引
        if (!empty($searchData['product_index']) && $searchData['product_index']) {
            $qb->andWhere('p.product_index LIKE :product_index')
                ->setParameter('product_index', '%' . $searchData['product_index'] . '%');
        }

        // 大分類
        if (!empty($searchData['BroadCategory']) && $searchData['BroadCategory']) {
            $qb->join('p.BroadCategory', 'b')
                ->andWhere($qb->expr()->eq('b.id', ':broad_category_id'))
                ->setParameter('broad_category_id', $searchData['BroadCategory']);
        }

        // 中分類
        if (!empty($searchData['MiddleCategory']) && $searchData['MiddleCategory']) {
            $qb->join('p.MiddleCategory', 'm')
                ->andWhere($qb->expr()->eq('m.id', ':middle_category_id'))
                ->setParameter('middle_category_id', $searchData['MiddleCategory']);
        }

        // サイトカテゴリ
        if (!empty($searchData['Category']) && $searchData['Category']) {
            $andX = $qb->expr()->andX();
            foreach ($searchData['Category'] as $category) {
                $andX->add($qb->expr()->eq('c.id', $qb->expr()->literal($category['id'])));
            }
            $qb->join('p.ProductCategories', 'pc')
                ->join('pc.Category', 'c')
                ->andWhere($andX);
        }

        // 商品名略称
        if (!empty($searchData['product_shortname']) && $searchData['product_shortname']) {
            $qb->andWhere('p.product_shortname LIKE :product_shortname')
                ->setParameter('product_shortname', '%' . $searchData['product_shortname'] . '%');
        }

        // 送料計算用区分
        if (!empty($searchData['DeliveryCalculation']) && $searchData['DeliveryCalculation']) {
            $qb->join('p.DeliveryCalculation', 'dc')
                ->andWhere($qb->expr()->eq('dc.id', ':delivery_calculation_id'))
                ->setParameter('delivery_calculation_id', $searchData['DeliveryCalculation']);
        }

        // 詰込管理区分
        if (!empty($searchData['PackingManagement']) && $searchData['PackingManagement']) {
            $qb->join('p.PackingManagement', 'pm')
                ->andWhere($qb->expr()->eq('pm.id', ':packing_management_id'))
                ->setParameter('packing_management_id', $searchData['PackingManagement']);
        }

        // リパック区分
        if (!empty($searchData['Repack']) && $searchData['Repack']) {
            $qb->join('p.Repack', 'r')
                ->andWhere($qb->expr()->eq('r.id', ':repack_id'))
                ->setParameter('repack_id', $searchData['Repack']);
        }

        // 量目
        if (!empty($searchData['weight']) && $searchData['weight']) {
            $qb->andWhere('p.weight LIKE :weight')
                ->setParameter('weight', '%' . $searchData['weight'] . '%');
        }

        // 加工区分
        if (!empty($searchData['ProcessedProductCategory']) && $searchData['ProcessedProductCategory']) {
            $qb->join('p.ProcessedProductCategory', 'ppc')
                ->andWhere($qb->expr()->eq('ppc.id', ':processed_product_category_id'))
                ->setParameter('processed_product_category_id', $searchData['ProcessedProductCategory']);
        }

        // 仕入先
        if (!empty($searchData['ProductSupplier']) && $searchData['ProductSupplier']) {
            $qb->join('p.ProductSupplier', 'ps')
                ->andWhere($qb->expr()->eq('ps.id', ':product_supplier_id'))
                ->setParameter('product_supplier_id', $searchData['ProductSupplier']);
        }

        // 加工場所
        if (!empty($searchData['processing_place']) && $searchData['processing_place']) {
            $qb->andWhere('p.processing_place LIKE :processing_place')
                ->setParameter('processing_place', '%' . $searchData['processing_place'] . '%');
        }

        // 調理方法
        if (!empty($searchData['cooking_method']) && $searchData['cooking_method']) {
            $qb->andWhere('p.cooking_method LIKE :cooking_method')
                ->setParameter('cooking_method', '%' . $searchData['cooking_method'] . '%');
        }

        // 解凍区分
        if (!empty($searchData['DecompressionMethod']) && $searchData['DecompressionMethod']) {
            $qb->join('p.DecompressionMethod', 'dm')
                ->andWhere($qb->expr()->eq('dm.id', ':decompression_method_id'))
                ->setParameter('decompression_method_id', $searchData['DecompressionMethod']);
        }

        // 塩分
        if (!empty($searchData['salt_amount']) && $searchData['salt_amount']) {
            $qb->andWhere('p.salt_amount LIKE :salt_amount')
                ->setParameter('salt_amount', '%' . $searchData['salt_amount'] . '%');
        }

        // カロリー
        if (!empty($searchData['calorie']) && $searchData['calorie']) {
            $qb->andWhere('p.calorie LIKE :calorie')
                ->setParameter('calorie', '%' . $searchData['calorie'] . '%');
        }

        // アレルギー
        if (!empty($searchData['allergy']) && $searchData['allergy']) {
            $qb->andWhere('p.allergy LIKE :allergy')
                ->setParameter('allergy', '%' . $searchData['allergy'] . '%');
        }

        // 原材料
        if (!empty($searchData['raw_materials']) && $searchData['raw_materials']) {
            $qb->andWhere('p.raw_materials LIKE :raw_materials')
                ->setParameter('raw_materials', '%' . $searchData['raw_materials'] . '%');
        }

        // 商品説明
        if (!empty($searchData['description_detail']) && $searchData['description_detail']) {
            $qb->andWhere('p.description_detail LIKE :description_detail')
                ->setParameter('description_detail', '%' . $searchData['description_detail'] . '%');
        }

        // 販売種別
        if (!empty($searchData['sale_type']) && $searchData['sale_type']) {
            $qb->andWhere($qb->expr()->eq('p.sale_type', ':sale_type'))
                ->setParameter('sale_type', $searchData['sale_type']);
        }

        // 備考
        if (!empty($searchData['note']) && $searchData['note']) {
            $qb->andWhere('p.note LIKE :note')
                ->setParameter('note', '%' . $searchData['note'] . '%');
        }

        // Order By
        if (isset($searchData['sortkey']) && !empty($searchData['sortkey'])) {
            $sortOrder = (isset($searchData['sorttype']) && $searchData['sorttype'] == 'a') ? 'ASC' : 'DESC';

            $qb->orderBy(self::COLUMNS[$searchData['sortkey']], $sortOrder);
            $qb->addOrderBy('p.code', 'ASC');
        } else {
            $qb->addOrderBy('p.code', 'ASC');
        }

        return $this->queries->customize(QueryKey::PRODUCT_SEARCH_ADMIN, $qb, $searchData);
    }

    /**
     * 商品コードで商品検索
     *
     * @param  array $searchData
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderByCode($searchData)
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('pc')
            ->innerJoin('p.ProductClasses', 'pc')
            ->andWhere('pc.visible = :visible')
            ->setParameter('visible', true);

        // code
        if (!empty($searchData['code']) && $searchData['code']) {
            return $qb->andWhere('pc.code LIKE :code')
                ->setParameter('code', '%' . $searchData['code'] . '%')
                ->getQuery()
                ->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
        } else {
            return [];
        }
    }

    /**
     * get query builder.
     *
     * @param  array $searchData
     *
     * @return array
     */
    public function getQueryBuilderBySearchDataByArrayData($searchData)
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('pc', 'pi', 'tr', 'ps')
            ->innerJoin('p.ProductClasses', 'pc')
            ->leftJoin('p.ProductImage', 'pi')
            ->leftJoin('pc.TaxRule', 'tr')
            ->leftJoin('pc.ProductStock', 'ps')
            ->andWhere('pc.visible = :visible')
            ->setParameter('visible', true);

        // id
        if (isset($searchData['id']) && StringUtil::isNotBlank($searchData['id'])) {
            $id = preg_match('/^\d{0,10}$/', $searchData['id']) ? $searchData['id'] : null;
            if ($id && $id > '2147483647' && $this->isPostgreSQL()) {
                $id = null;
            }
            $qb
                ->andWhere('p.id = :id OR p.name LIKE :likeid OR pc.code LIKE :likeid')
                ->setParameter('id', $id)
                ->setParameter('likeid', '%' . str_replace(['%', '_'], ['\\%', '\\_'], $searchData['id']) . '%');
        }

        // code
        if (!empty($searchData['code']) && $searchData['code']) {
            $qb->andWhere('pc.code LIKE :code')
                ->setParameter('code', '%' . $searchData['code'] . '%');
        }

        // category
        if (!empty($searchData['category_id']) && $searchData['category_id']) {
            $Categories = $searchData['category_id']->getSelfAndDescendants();
            if ($Categories) {
                $qb
                    ->innerJoin('p.ProductCategories', 'pct')
                    ->innerJoin('pct.Category', 'c')
                    ->andWhere($qb->expr()->in('pct.Category', ':Categories'))
                    ->setParameter('Categories', $Categories);
            }
        }

        // status
        if (!empty($searchData['status']) && $searchData['status']) {
            $qb
                ->andWhere($qb->expr()->in('p.Status', ':Status'))
                ->setParameter('Status', $searchData['status']);
        }

        // link_status
        if (isset($searchData['link_status']) && !empty($searchData['link_status'])) {
            $qb
                ->andWhere($qb->expr()->in('p.Status', ':Status'))
                ->setParameter('Status', $searchData['link_status']);
        }

        // stock status
        if (isset($searchData['stock_status'])) {
            $qb
                ->andWhere('pc.stock_unlimited = :StockUnlimited AND pc.stock = 0')
                ->setParameter('StockUnlimited', $searchData['stock_status']);
        }

        // stock status
        if (isset($searchData['stock']) && !empty($searchData['stock'])) {
            switch ($searchData['stock']) {
                case [ProductStock::IN_STOCK]:
                    $qb->andWhere('pc.stock_unlimited = true OR pc.stock > 0');
                    break;
                case [ProductStock::OUT_OF_STOCK]:
                    $qb->andWhere('pc.stock_unlimited = false AND pc.stock <= 0');
                    break;
                default:
                    // 共に選択された場合は全権該当するので検索条件に含めない
            }
        }

        // tag
        if (!empty($searchData['tag_id']) && $searchData['tag_id']) {
            $qb
                ->innerJoin('p.ProductTag', 'pt')
                ->andWhere('pt.Tag = :tag_id')
                ->setParameter('tag_id', $searchData['tag_id']);
        }

        // crate_date
        if (!empty($searchData['create_datetime_start']) && $searchData['create_datetime_start']) {
            $date = $searchData['create_datetime_start'];
            $qb
                ->andWhere('p.create_date >= :create_date_start')
                ->setParameter('create_date_start', $date);
        } elseif (!empty($searchData['create_date_start']) && $searchData['create_date_start']) {
            $date = $searchData['create_date_start'];
            $qb
                ->andWhere('p.create_date >= :create_date_start')
                ->setParameter('create_date_start', $date);
        }

        if (!empty($searchData['create_datetime_end']) && $searchData['create_datetime_end']) {
            $date = $searchData['create_datetime_end'];
            $qb
                ->andWhere('p.create_date < :create_date_end')
                ->setParameter('create_date_end', $date);
        } elseif (!empty($searchData['create_date_end']) && $searchData['create_date_end']) {
            $date = clone $searchData['create_date_end'];
            $date = $date
                ->modify('+1 days');
            $qb
                ->andWhere('p.create_date < :create_date_end')
                ->setParameter('create_date_end', $date);
        }

        // update_date
        if (!empty($searchData['update_datetime_start']) && $searchData['update_datetime_start']) {
            $date = $searchData['update_datetime_start'];
            $qb
                ->andWhere('p.update_date >= :update_date_start')
                ->setParameter('update_date_start', $date);
        } elseif (!empty($searchData['update_date_start']) && $searchData['update_date_start']) {
            $date = $searchData['update_date_start'];
            $qb
                ->andWhere('p.update_date >= :update_date_start')
                ->setParameter('update_date_start', $date);
        }

        if (!empty($searchData['update_datetime_end']) && $searchData['update_datetime_end']) {
            $date = $searchData['update_datetime_end'];
            $qb
                ->andWhere('p.update_date < :update_date_end')
                ->setParameter('update_date_end', $date);
        } elseif (!empty($searchData['update_date_end']) && $searchData['update_date_end']) {
            $date = clone $searchData['update_date_end'];
            $date = $date
                ->modify('+1 days');
            $qb
                ->andWhere('p.update_date < :update_date_end')
                ->setParameter('update_date_end', $date);
        }

        // name
        if (!empty($searchData['name']) && $searchData['name']) {
            $keywords = preg_split('/[\s　]+/u', $searchData['name'], -1, PREG_SPLIT_NO_EMPTY);
            foreach ($keywords as $keyword) {
                $qb
                    ->andWhere('p.name LIKE :name')
                    ->setParameter('name', '%' . $keyword . '%');
            }
        }

        $customColumns = [
            'name',
            'size',
            'brand_id',
            'maker_id',
            'supplier_id',
            'gift_id',
            'bulk_buying_id',
            'detail_url',
            'one_word_comment',
            'temperature_range_id',
            'regular_purchase_category_id',
            'icon_id_1',
            'icon_id_2',
            'icon_id_3',
            'note',
            'introduce_good_id',
            'cycle_purchase_id',
            'purchase_limited_number',
            'purchase_minimum_number',
            'delivery_calculation_number',
            'unit',
        ];

        foreach ($customColumns as $column) {
            // var_dump($searchData[$column]);
            if (!empty($searchData[$column]) && $searchData[$column]) {
                $keywords = preg_split('/[\s　]+/u', $searchData[$column], -1, PREG_SPLIT_NO_EMPTY);
                foreach ($keywords as $keyword) {
                    var_dump('p.' . $column . ' LIKE :' . $column);
                    $qb
                        ->andWhere('p.' . $column . ' LIKE :' . $column)
                        ->setParameter($column, '%' . $keyword . '%');
                }
            }
        }

        // Order By
        if (isset($searchData['sortkey']) && !empty($searchData['sortkey'])) {
            $sortOrder = (isset($searchData['sorttype']) && $searchData['sorttype'] == 'a') ? 'ASC' : 'DESC';

            $qb->orderBy(self::COLUMNS[$searchData['sortkey']], $sortOrder);
            $qb->addOrderBy('p.update_date', 'DESC');
            $qb->addOrderBy('p.id', 'DESC');
        } else {
            $qb->orderBy('p.update_date', 'DESC');
            $qb->addOrderBy('p.id', 'DESC');
        }

        return $qb->getQuery()->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    }

    /**
     * get query builder.
     *
     * @param  array $searchData
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getQueryBuilderBySearchProductCodeForAdmin($searchData)
    {
        $qb = $this->createQueryBuilder('p');

        // 商品コード
        if (isset($searchData['code']) && StringUtil::isNotBlank($searchData['code'])) {
            $id = preg_match('/^\d{0,10}$/', $searchData['code']) ? $searchData['code'] : null;
            if ($id && $id > '2147483647' && $this->isPostgreSQL()) {
                $id = null;
            }
            $qb
                ->andWhere('p.code LIKE :code')
                ->setParameter('code', $searchData['code'] . '%');
        }
        return $this->queries->customize(QueryKey::PRODUCT_SEARCH_ADMIN, $qb, $searchData);
    }
}