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

namespace Eccube\Form\Type\Admin;

use Customize\Entity\Master\BulkBuying;
use Customize\Entity\Master\DeliveryCalculation;
use Customize\Entity\Master\IntroduceGood;
use Customize\Entity\Master\NewProductCategory;
use Customize\Entity\Master\SubscriptionPurchase;
use Customize\Entity\Product\ProductIcon;
use Customize\Entity\Product\ProductSupplier;
use Customize\Form\Type\FilterChoiceType;
use Customize\Repository\Admin\Master\NewProductCategoryRepository;
use Customize\Repository\Admin\Master\SubscriptionPurchaseRepository;
use Customize\Repository\Master\DecompressionMethodRepository;
use Customize\Repository\Master\DeliveryCalculationRepository;
use Customize\Repository\Master\IntroduceGoodRepository;
use DateTime;
use Eccube\Entity\Category;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\Master\BulkBuyingRepository;
use Eccube\Repository\Master\ProductStatusRepository;
use Eccube\Repository\ProductCategoryRepository;
use Eccube\Repository\ProductIconRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Repository\ProductSupplierRepository;
use Eccube\Repository\TagRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchProductClassType extends AbstractType
{
    /**
     * @var ProductStatusRepository
     */
    protected $productStatusRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductCategoryRepository
     */
    protected $productCategoryRepository;

    /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * @var DeliveryCalculationRepository
     */
    protected $deliveryCalculationRepository;

    /**
     * @var SubscriptionPurchaseRepository
     */
    protected $subscriptionPurchaseRepository;

    /**
     * @var ProductIconRepository
     */
    protected $productIconRepository;

    /**
     * @var NewProductCategoryRepository
     */
    protected $newProductCategoryRepository;

    /**
     * @var IntroduceGoodRepository
     */
    protected $introduceGoodRepository;

    /**
     * SearchProductType constructor.
     *
     * @param ProductStatusRepository $productStatusRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductSupplierRepository $productSupplierRepository
     * @param TagRepository $tagRepository
     * @param ProductRepository $productRepository
     * @param ProductCategoryRepository $productCategoryRepository
     * @param BulkBuyingRepository $bulkBuyingRepository
     * @param DeliveryCalculationRepository $deliveryCalculationRepository
     * @param SubscriptionPurchaseRepository $subscriptionPurchaseRepository
     * @param ProductIconRepository $productIconRepository
     * @param NewProductCategoryRepository $newProductCategoryRepository
     * @param IntroduceGoodRepository $introduceGoodRepository
     */
    public function __construct(
        ProductStatusRepository $productStatusRepository,
        CategoryRepository $categoryRepository,
        ProductSupplierRepository $productSupplierRepository,
        TagRepository $tagRepository,
        ProductRepository $productRepository,
        ProductCategoryRepository $productCategoryRepository,
        BulkBuyingRepository $bulkBuyingRepository,
        DeliveryCalculationRepository $deliveryCalculationRepository,
        SubscriptionPurchaseRepository $subscriptionPurchaseRepository,
        ProductIconRepository $productIconRepository,
        NewProductCategoryRepository $newProductCategoryRepository,
        IntroduceGoodRepository $introduceGoodRepository
    ) {
        $this->productStatusRepository = $productStatusRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productSupplierRepository = $productSupplierRepository;
        $this->tagRepository = $tagRepository;
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->bulkBuyingRepository = $bulkBuyingRepository;
        $this->deliveryCalculationRepository = $deliveryCalculationRepository;
        $this->subscriptionPurchaseRepository = $subscriptionPurchaseRepository;
        $this->productIconRepository = $productIconRepository;
        $this->newProductCategoryRepository = $newProductCategoryRepository;
        $this->introduceGoodRepository = $introduceGoodRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // パンフレットコード
            ->add('pamphlet_code', TextType::class, [
                'label' => 'admin.product.pamphlet_code',
                'required' => false,
            ])
            // 商品コード
            ->add('code', TextType::class, [
                'label' => 'admin.product.product_id',
                'required' => false,
                'mapped' => false,
            ])
            // 商品名
            ->add('name', TextType::class, [
                'label' => 'admin.product.name',
                'required' => false,
                'mapped' => false,
            ])
            // サイトカテゴリ
            ->add('Category', FilterChoiceType::class, [
                'label' => 'admin.product.category',
                'choice_label' => 'name',
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->categoryRepository->findAll(),
                'choice_value' => function (Category $category = null) {
                    return $category ? $category->getId() : null;
                },
                'attr' => [
                    'name' => '[]',
                    'style' => 'width: 100%;'
                ]
            ])
            // 仕入先
            ->add('ProductSupplier', FilterChoiceType::class, [
                'label' => 'admin.product.product_supplier',
                'choice_label' => 'name',
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->productSupplierRepository->findAll(),
                'choice_value' => function (ProductSupplier $productSupplier = null) {
                    return $productSupplier ? $productSupplier->getId() : null;
                },
                'attr' => [
                    'style' => 'width: 100%;'
                ]
            ])
            // まとめ買いグループ
            ->add('BulkBuying', FilterChoiceType::class, [
                'label' => 'admin.product.bulk_buying',
                'choice_label' => 'name',
                'required' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->bulkBuyingRepository->findAll(),
                'choice_value' => function (BulkBuying $bulkBuying = null) {
                    return $bulkBuying ? $bulkBuying->getId() : null;
                },
                'attr' => [
                    'name' => '[]',
                    'style' => 'width: 100%;'
                ]
            ])
            // 状態
            ->add('status', ChoiceType::class, [
                'label' => 'admin.product.status',
                'choices' => [
                    '通常' => 1,
                    '非表示' => 2,
                    '下書き' => 3,
                ],
                'required' => false,
            ])
            // 温度帯（送料計算用区分）
            ->add('DeliveryCalculation', FilterChoiceType::class, [
                'label' => 'admin.product.delivery_calculation',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->deliveryCalculationRepository->findAll(),
                'choice_value' => function (DeliveryCalculation $deliveryCalculation = null) {
                    return $deliveryCalculation ? $deliveryCalculation->getId() : null;
                },
            ])
            // 定期購入品区分
            ->add('SubscriptionPurchase', FilterChoiceType::class, [
                'label' => 'admin.product.subscription_purchase',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->subscriptionPurchaseRepository->findAll(),
                'choice_value' => function (SubscriptionPurchase $subscriptionPurchase = null) {
                    return $subscriptionPurchase ? $subscriptionPurchase->getId() : null;
                },
            ])
            // 通常価格（開始）
            ->add('price_start', NumberType::class, [
                'label' => 'admin.product.price_start',
                'required' => false,
                'mapped' => false,
            ])
            // 通常価格（終了）
            ->add('price_end', NumberType::class, [
                'label' => 'admin.product.price_end',
                'required' => false,
                'mapped' => false,
            ])
            // 割引期間価格（開始）
            ->add('discount_period_price_start', NumberType::class, [
                'label' => 'admin.product.discount_period_price_start',
                'required' => false,
                'mapped' => false,
            ])
            // 割引期間価格（終了）
            ->add('discount_period_price_end', NumberType::class, [
                'label' => 'admin.product.discount_period_price_end',
                'required' => false,
                'mapped' => false,
            ])
            // 購入限定数（開始）
            ->add('purchase_limit_start', NumberType::class, [
                'label' => 'admin.product.purchase_limit_start',
                'required' => false,
                'mapped' => false,
            ])
            // 購入限定数（終了）
            ->add('purchase_limit_end', NumberType::class, [
                'label' => 'admin.product.purchase_limit_end',
                'required' => false,
                'mapped' => false,
            ])
            // 購入最低数（開始）
            ->add('purchase_minimum_start', NumberType::class, [
                'label' => 'admin.product.purchase_minimum_start',
                'required' => false,
                'mapped' => false,
            ])
            // 購入最低数（終了）
            ->add('purchase_minimum_end', NumberType::class, [
                'label' => 'admin.product.purchase_minimum_end',
                'required' => false,
                'mapped' => false,
                ])
            // アイコン
            ->add('ProductIcon', FilterChoiceType::class, [
                'label' => 'admin.product.icon_id',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->productIconRepository->findAll(),
                'choice_value' => function (ProductIcon $icon = null) {
                    return $icon ? $icon->getId() : null;
                },
            ])
            // 新商品区分
            ->add('NewProductCategory', FilterChoiceType::class, [
                'label' => 'admin.product.new_product_category',
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->newProductCategoryRepository->findAll(),
                'choice_value' => function (NewProductCategory $newProductCategory = null) {
                    return $newProductCategory ? $newProductCategory->getId() : null;
                },
            ])
            // 紹介品区分
            ->add('IntroduceGoods', FilterChoiceType::class, [
                'label' => 'admin.product.introduce_good_id',
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->introduceGoodRepository->findAll(),
                'choice_value' => function (IntroduceGood $introduceGood = null) {
                    return $introduceGood ? $introduceGood->getId() : null;
                },
            ])
            // キーワード
            ->add('keyword', TextType::class, [
                'label' => 'admin.product.keyword',
                'required' => false,
            ])
            // 登録日(開始)
            ->add('create_date_start', DateType::class, [
                'label' => 'admin.product.create_date_start',
                'required' => false,
                'mapped' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            // 登録日(終了)
            ->add('create_date_end', DateType::class, [
                'label' => 'admin.product.create_date_end',
                'required' => false,
                'mapped' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            // 更新日(開始)
            ->add('update_date_start', DateType::class, [
                'label' => 'admin.product.update_start',
                'required' => false,
                'mapped' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_update_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            // 更新日(終了)
            ->add('update_date_end', DateType::class, [
                'label' => 'admin.product.update_end',
                'required' => false,
                'mapped' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_update_date_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            // 掲載日（開始）
            ->add('insert_start_date', DateTimeType::class, [
                'label' => 'admin.product.insert_start_date',
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'with_seconds' => true,
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'w'
                ]
            ])
            // 掲載日（終了）
            ->add('insert_end_date', DateTimeType::class, [
                'label' => 'admin.product.insert_end_date',
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'with_seconds' => true,
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ])
            // 在庫数(開始)
            ->add('stock_start', NumberType::class, [
                'label' => 'admin.product.stock_start',
                'required' => false,
                'mapped' => false,
            ])
            // 在庫数(終了)
            ->add('stock_end', NumberType::class, [
                'label' => 'admin.product.stock_end',
                'required' => false,
                'mapped' => false,
            ])
            // 在庫インポートフラグ
            ->add('stock_import_flag', ChoiceType::class, [
                'label' => 'admin.product.stock_import_flag',
                'required' => false,
                'mapped' => false,
                'choices' => [
                    'ある' => true,
                    'なし' => false,
                ],
            ])
            // 在庫扱いの種別
            ->add('stock_type', ChoiceType::class, [
                'label' => 'admin.product.stock_type',
                'required' => false,
                'choices' => [
                    'ある' => true,
                    'なし' => false,
                ],
            ])
            // キャンペーン設定
            ->add('campaign_config', ChoiceType::class, [
                'label' => 'admin.product.campaign_config',
                'required' => false,
                'mapped' => false,
                'choices' => [
                    'ある' => true,
                    'なし' => false,
                ],
            ])
            // イベント
            ->add('event', ChoiceType::class, [
                'label' => 'admin.product.event',
                'required' => false,
                'mapped' => false,
                'choices' => [
                    'ある' => true,
                    'なし' => false,
                ],
            ])
            // タグ名
            ->add('tag_name', TextType::class, [
                'label' => 'admin.product.tag_name',
                'required' => false,
                'mapped' => false,
            ])
            //     // ソート用
            ->add('sortkey', HiddenType::class, [
                'label' => 'admin.list.sort.key',
                'required' => false,
            ])
            ->add('sorttype', HiddenType::class, [
                'label' => 'admin.list.sort.type',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_search_product';
    }
}
