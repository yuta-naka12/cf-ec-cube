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
use Customize\Entity\Master\DecompressionMethod;
use Customize\Entity\Master\DeliveryCalculation;
use Customize\Entity\Master\IntroduceGood;
use Customize\Entity\Master\NewProductCategory;
use Customize\Entity\Master\SubscriptionPurchase;
use Customize\Entity\Product\ProductIcon;
use Customize\Entity\Product\ProductSupplier;
use Customize\Entity\Setting\PurchaseGroup;
use Customize\Form\Type\Admin\Product\IntroduceGoodType;
use Customize\Form\Type\Admin\Product\NewProductCategoryType;
use Customize\Form\Type\Admin\Product\ProductSupplierType;
use Customize\Form\Type\Admin\Product\PurchaseGroupType;
use Customize\Form\Type\Admin\Product\SubscriptionPurchaseType;
use Customize\Form\Type\Admin\Product\TemperatureRangeType;
use Customize\Form\Type\FilterChoiceType;
use Customize\Repository\Admin\Master\NewProductCategoryRepository;
use Customize\Repository\Admin\Master\SubscriptionPurchaseRepository;
use Customize\Repository\Master\DecompressionMethodRepository;
use Customize\Repository\Master\DeliveryCalculationRepository;
use Customize\Repository\Master\IntroduceGoodRepository;
use Doctrine\DBAL\Types\IntegerType;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Category;
use Eccube\Entity\ClassCategory;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Form\DataTransformer;
use Eccube\Form\Type\Master\DeliveryDurationType;
use Eccube\Form\Type\Master\ProductStatusType;
use Eccube\Form\Type\Master\SaleTypeType;
use Eccube\Form\Type\PriceType;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\Master\BulkBuyingRepository;
use Eccube\Repository\ProductCategoryRepository;
use Eccube\Repository\ProductIconRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Repository\ProductSupplierRepository;
use Eccube\Repository\PurchaseGroupRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductClassType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ProductCategoryRepository
     */
    protected $productCategoryRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var PurchaseGroupRepository
     */
    protected $purchaseGroupRepository;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * @var IntroduceGoodRepository
     */
    protected $introduceGoodRepository;

    /**
     * @var NewProductCategoryRepository
     */
    protected $newProductCategoryRepository;

    /**
     * @var SubscriptionPurchaseRepository
     */
    protected $subscriptionPurchaseRepository;

    /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * @var ProductIconRepository
     */
    protected $productIconRepository;

    /**
     * @var DeliveryCalculationRepository
     */
    protected $deliveryCalculationRepository;

    /**
     * @var DecompressionMethodRepository
     */
    protected $decompressionMethodRepository;

    /**
     * ProductClassType constructor.
     *
     * @param EntityManagerInterface $entityManager
     * @param ProductRepository $productRepository
     * @param ProductCategoryRepository $productCategoryRepository
     * @param CategoryRepository $categoryRepository
     * @param PurchaseGroupRepository $purchaseGroupRepository
     * @param ProductSupplierRepository $productSupplierRepository
     * @param IntroduceGoodRepository $introduceGoodRepository
     * @param NewProductCategoryRepository $newProductCategoryRepository
     * @param SubscriptionPurchaseRepository $subscriptionPurchaseRepository
     * @param BulkBuyingRepository $bulkBuyingRepository
     * @param ProductIconRepository $productIconRepository
     * @param DeliveryCalculationRepository $deliveryCalculationRepository
     * @param DecompressionMethodRepository $decompressionMethodRepository
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        ProductRepository $productRepository,
        ProductCategoryRepository $productCategoryRepository,
        CategoryRepository $categoryRepository,
        PurchaseGroupRepository $purchaseGroupRepository,
        ProductSupplierRepository $productSupplierRepository,
        IntroduceGoodRepository $introduceGoodRepository,
        NewProductCategoryRepository $newProductCategoryRepository,
        SubscriptionPurchaseRepository $subscriptionPurchaseRepository,
        BulkBuyingRepository $bulkBuyingRepository,
        ProductIconRepository $productIconRepository,
        DeliveryCalculationRepository $deliveryCalculationRepository,
        DecompressionMethodRepository $decompressionMethodRepository
    ) {
        $this->entityManager = $entityManager;
        $this->productRepository = $productRepository;
        $this->productCategoryRepository = $productCategoryRepository;
        $this->categoryRepository = $categoryRepository;
        $this->purchaseGroupRepository = $purchaseGroupRepository;
        $this->productSupplierRepository = $productSupplierRepository;
        $this->introduceGoodRepository = $introduceGoodRepository;
        $this->newProductCategoryRepository = $newProductCategoryRepository;
        $this->subscriptionPurchaseRepository = $subscriptionPurchaseRepository;
        $this->bulkBuyingRepository = $bulkBuyingRepository;
        $this->productIconRepository = $productIconRepository;
        $this->deliveryCalculationRepository = $deliveryCalculationRepository;
        $this->decompressionMethodRepository = $decompressionMethodRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // 基本情報 //
            // パンフレットコード
            ->add('pamphlet_code', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 商品コード
            ->add('Product', FilterChoiceType::class, [
                'choice_label' => 'name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->productRepository->findBy([],['code' => 'asc']),
                'choice_value' => function (Product $product = null) {
                    return $product ? $product->getCode() : null;
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 商品名
            ->add('product_name', TextType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'readonly' => true,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // サイトカテゴリ
            ->add('Category', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'required' => true,
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->categoryRepository->getList(null, true),
                'choice_value' => function (Category $Category = null) {
                    return $Category ? $Category->getId() : null;
                },
                'attr' => [
                    'disabled' => true,
                ]
            ])
            // 販売期間（開始）
            ->add('sales_start_period', DateType::class, [
                'required' => true,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('discount_start_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // 販売期間（終了）
            ->add('sales_end_period', DateType::class, [
                'required' => true,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 紹介品区分
            ->add('IntroduceGoods', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->introduceGoodRepository->findAll(),
                'choice_value' => function (IntroduceGood $introduceGood = null) {
                    return $introduceGood ? $introduceGood->getId() : null;
                },
            ])
            // 新商品区分
            ->add('NewProductCategory', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->newProductCategoryRepository->findAll(),
                'choice_value' => function (NewProductCategory $newProductCategory = null) {
                    return $newProductCategory ? $newProductCategory->getId() : null;
                },
            ])
            // 定期購入区分
            ->add('SubscriptionPurchase', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->subscriptionPurchaseRepository->findAll(),
                'choice_value' => function (SubscriptionPurchase $subscriptionPurchase = null) {
                    return $subscriptionPurchase ? $subscriptionPurchase->getId() : null;
                },
            ])
            // 通常価格
            ->add('price', PriceType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                    new Assert\NotBlank(),
                ],
            ])
            // 会員区分別価格０
            ->add('member_price_0', PriceType::class, [
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ]
            ])
            // 会員区分別価格１
            ->add('member_price_1', PriceType::class, [
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ]
            ])
            // 会員区分別価格２
            ->add('member_price_2', PriceType::class, [
                'required'  => false,
                'attr' => [
                    'readonly' => true,
                ]
            ])
            // 会員区分別価格３
            ->add('member_price_3', PriceType::class, [
                'required' => false,
                'attr' => [
                    'readonly' => true,
                ]
            ])
            // 割引期間（開始）
            ->add('discount_start_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // 割引期間（終了）
            ->add('discount_end_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // 割引期間価値
            ->add('discount_period_price', PriceType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // まとめ買いグループ
            ->add('BulkBuying', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->bulkBuyingRepository->findAll(),
                'choice_value' => function (BulkBuying $bulkPurchaseGroup = null) {
                    return $bulkPurchaseGroup ? $bulkPurchaseGroup->getId() : null;
                },
            ])
            // まとめ買い価格
            ->add('bulk_buying_price', PriceType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // キャンペーン期間（開始）
            ->add('campaign_start_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // キャンペーン期間（終了）
            ->add('campaign_end_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // ポイント
            ->add('point', NumberType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],

            ])
            // キャンペーンポイント
            ->add('campaign_point', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 仕入額
            ->add('cost', PriceType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 仕入期間（開始）
            ->add('cost_start_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // 仕入期間（終了）
            ->add('cost_end_period', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            // 期間仕入額
            ->add('cost_period_price', PriceType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data['cost_start_period'] !== null || $data['cost_end_period'] !== null) {
                    $costPeriodPrice = $form->get('cost_period_price')->getData();

                    if (empty($costPeriodPrice)) {
                        $form->get('cost_period_price')->addError(new FormError('入力されていません'));
                    }
                }
            })
            // 包材
            ->add('packing_material', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 決済
            ->add('settlement', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 返済
            ->add('pay_back_debt', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // ドライ
            ->add('dry', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 保管料
            ->add('discount_fee', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 金利
            ->add('interest_rates', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // マージン
            ->add('margin', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            //! ECサイト情報 //
            // 購入限定数
            ->add('purchase_limit', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 購入最低数
            ->add('purchase_minimum', NumberType::class, [
                'required' => false,
                'data' => 1,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 掲載日（開始）
            ->add('insert_start_date', DateTimeType::class, [
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'with_seconds' => true,
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ])
            // 掲載日（終了）
            ->add('insert_end_date', DateTimeType::class, [
                'required' => false,
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'with_seconds' => true,
                'format' => 'yyyy-MM-dd HH:mm:ss',
            ])
            // 一覧画面有無
            ->add('is_list_page', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'あり' => true,
                    'なし' => false,
                ],
                'data' => true,
            ])
            // 詳細画面有無
            ->add('is_detail_page', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'あり' => true,
                    'なし' => false,
                ],
                'data' => true,
            ])
            // 状態
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'クリア' => 0,
                    '通常' => 1,
                    '非表示' => 2,
                ],
                'data' => 1,
            ])
            // 商品説明１
            ->add('description_detail', TextareaType::class, [
                'required' => true,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 商品説明２（補足説明）
            ->add('description_detail_2', TextareaType::class, [
                'required' => false,
            ])
            // アイコン１
            ->add('ProductIcon1', FilterChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'expanded' => true,
                'choices' => $this->productIconRepository->findAll(),
                'choice_label' => 'name',
                'choice_value' => function (ProductIcon $icon = null) {
                    return $icon ? $icon->getId() : null;
                },
            ])
            // アイコン２
            ->add('ProductIcon2', FilterChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'expanded' => true,
                'choices' => $this->productIconRepository->findAll(),
                'choice_label' => 'name',
                'choice_value' => function (ProductIcon $icon = null) {
                    return $icon ? $icon->getId() : null;
                },
            ])
            // アイコン３
            ->add('ProductIcon3', FilterChoiceType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'expanded' => true,
                'choices' => $this->productIconRepository->findAll(),
                'choice_label' => 'name',
                'choice_value' => function (ProductIcon $icon = null) {
                    return $icon ? $icon->getId() : null;
                },
            ])
            // かご投入時メッセージ
            ->add('cart_insert_message', TextareaType::class, [
                'required' => false,
            ])
            // キーワード
            ->add('keyword', TextType::class, [
                'required' => false,
            ])

            //! 在庫情報 //
            // 在庫数
            ->add('stock', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 在庫扱いの種別
            ->add('stock_type', ChoiceType::class, [
                'required' => false,
            ])
            // 在庫通常コメント
            ->add('normal_stock_comment', TextareaType::class, [
                'required' => false,
            ])
            // 在庫少量時コメント
            ->add('low_stock_comment', TextareaType::class, [
                'required' => false,
            ])
            // 在庫切れコメント
            ->add('out_of_stock_comment', TextareaType::class, [
                'required' => false,
            ])
            // コメントしきい値
            ->add('comment_threshold', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[0-9]+$/',
                        'message' => 'admin.product_class.form.pamphlet_code.regex',
                    ]),
                ],
            ])
            // 実数表示フラグ
            ->add('is_real_indicator', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'コメント' => true,
                    '字数表示' => false,
                ],
                'data' => true,
            ])
            //! マスタ情報 //
            // 送料計算用区分
            ->add('DeliveryCalculation', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->deliveryCalculationRepository->findAll(),
                'choice_value' => function (DeliveryCalculation $deliveryCalculation = null) {
                    return $deliveryCalculation ? $deliveryCalculation->getId() : null;
                },
                'attr' => [
                    'disabled' => true
                ],
            ])
            // 仕入先
            ->add('ProductSupplier', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'required' => false,
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->productSupplierRepository->findAll(),
                'choice_value' => function (ProductSupplier $ProductSupplier = null) {
                    return $ProductSupplier ? $ProductSupplier->getId() : null;
                },
                'attr' => [
                    'disabled' => true
                ],
            ])
            // 量目
            ->add('weight', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 加工場所
            ->add('processing_place', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 調理方法
            ->add('cooking_method', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 解凍区分
            ->add('DecompressionMethod', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->decompressionMethodRepository->findAll(),
                'choice_value' => function (DecompressionMethod $decompressionMethod = null) {
                    return $decompressionMethod ? $decompressionMethod->getId() : null;
                },
                'attr' => [
                    'disabled' => true
                ],
            ])
            // 塩分
            ->add('salt_amount', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // カロリー
            ->add('calorie', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // アレルギー
            ->add('allergy', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 原材料
            ->add('raw_materials', TextAreaType::class, [
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'readOnly' => true
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ]);
        // ->add('code', TextType::class, [
        //     'required' => false,
        //     'constraints' => [
        //         new Assert\Length([
        //             'max' => 255,
        //         ]),
        //     ],
        // ])
        // ->add('Product', FilterChoiceType::class, [
        //     'choice_label' => 'name',
        //     'multiple' => false,
        //     'mapped' => false,
        //     'expanded' => true,
        //     'choices' => $this->productRepository->findAll(),
        //     'choice_value' => function (Product $product = null) {
        //         return $product ? $product->getId() : null;
        //     },
        // ])
        // ->add('Category', FilterChoiceType::class, [
        //     'choice_label' => 'name',
        //     'multiple' => true,
        //     'required' => false,
        //     'mapped' => false,
        //     'expanded' => true,
        //     'choices' => $this->categoryRepository->findAll(),
        //     'choice_value' => function (Category $category = null) {
        //         return $category ? $category->getId() : null;
        //     },
        //     'attr' => [
        //         'name' => '[]',
        //     ]
        // ])
        // ->add('stock', NumberType::class, [
        //     'required' => false,
        //     'constraints' => [
        //         new Assert\Regex([
        //             'pattern' => "/^\d+$/u",
        //             'message' => 'form_error.numeric_only',
        //         ]),
        //     ],
        // ])
        // ->add('normal_stock_comment', TextType::class, [
        //     'required' => false,
        //     'constraints' => [],
        // ])
        // ->add('low_stock_comment', TextType::class, [
        //     'required' => false,
        //     'constraints' => [],
        // ])
        // ->add('out_of_stock_comment', TextType::class, [
        //     'required' => false,
        //     'constraints' => [],
        // ])
        // ->add('comment_threshold', TextType::class, [
        //     'required' => false,
        //     'constraints' => [],
        // ])
        // ->add('is_real_indicator', ChoiceType::class, [
        //     'choices' => [
        //         '有り' => 1,
        //         '無し' => 0,
        //     ],
        //     'required' => true,
        //     'constraints' => [],
        // ])
        // ->add('is_stock_import', ChoiceType::class, [
        //     'choices' => [
        //         '有り' => 1,
        //         '無し' => 0,
        //     ],
        //     'required' => true,
        //     'constraints' => [],
        // ])
        // ->add('stock_unlimited', CheckboxType::class, [
        //     'label' => 'admin.product.stock_unlimited__short',
        //     'value' => '1',
        //     'required' => false,
        // ])
        // ->add('sale_limit', NumberType::class, [
        //     'required' => false,
        //     'constraints' => [
        //         new Assert\Length([
        //             'max' => 10,
        //         ]),
        //         new Assert\GreaterThanOrEqual([
        //             'value' => 1,
        //         ]),
        //         new Assert\Regex([
        //             'pattern' => "/^\d+$/u",
        //             'message' => 'form_error.numeric_only',
        //         ]),
        //     ],
        // ])
        // ->add('price01', PriceType::class, [
        //     'required' => false,
        // ])
        // ->add('price02', PriceType::class, [
        //     'required' => false,
        // ])
        // ->add('tax_rate', TextType::class, [
        //     'required' => false,
        //     'constraints' => [
        //         new Assert\Range(['min' => 0, 'max' => 100]),
        //         new Assert\Regex([
        //             'pattern' => "/^\d+(\.\d+)?$/",
        //             'message' => 'form_error.float_only',
        //         ]),
        //     ],
        // ])
        // ->add('delivery_fee', PriceType::class, [
        //     'required' => false,
        // ])
        // ->add('sale_type', SaleTypeType::class, [
        //     'multiple' => false,
        //     'expanded' => false,
        //     'constraints' => [
        //         new Assert\NotBlank(),
        //     ],
        // ])
        // ->add('delivery_duration', DeliveryDurationType::class, [
        //     'required' => false,
        //     'placeholder' => 'common.select__unspecified',
        // ])
        // ->add('product_comment_1', TextareaType::class, [
        //     'required' => false,
        // ])
        // ->add('product_comment_2', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('supplier', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('weight', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('processing_place', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('cooking_method', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('decompression_method', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('salt_amount', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('calorie', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('allergy', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('raw_materials', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('in_cart_message', TextAreaType::class, [
        //     'required' => false,
        // ])
        // ->add('pamphlet_code', TextType::class, [
        //     'required' => false
        // ])
        // ->add('discount_start_period', DateType::class, [
        //     'required' => false
        // ])
        // ->add('discount_end_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('discount_period_price', TextType::class, [
        //     'required' => false
        // ])
        // ->add('campaign_start_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('campaign_end_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('point', TextType::class, [
        //     'required' => false
        // ])
        // ->add('campaign_point', TextType::class, [
        //     'required' => false
        // ])
        // ->add('cost', TextType::class, [
        //     'required' => false
        // ])
        // ->add('cost_start_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('cost_end_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('cost_period_price', TextType::class, [
        //     'required' => false
        // ])
        // ->add('packing_material', TextType::class, [
        //     'required' => false
        // ])
        // ->add('settlement', TextType::class, [
        //     'required' => false
        // ])
        // ->add('pay_back_debt', TextType::class, [
        //     'required' => false
        // ])
        // ->add('dry', TextType::class, [
        //     'required' => false
        // ])
        // ->add('discount_fee', TextType::class, [
        //     'required' => false
        // ])
        // ->add('interest_rates', TextType::class, [
        //     'required' => false
        // ])
        // ->add('margin', TextType::class, [
        //     'required' => false
        // ])
        // ->add('purchase_limit', TextType::class, [
        //     'required' => false
        // ])
        // ->add('purchase_minimum', TextType::class, [
        //     'required' => false
        // ])
        // ->add('insert_start_date', TextType::class, [
        //     'required' => false
        // ])
        // ->add('insert_end_date', TextType::class, [
        //     'required' => false
        // ])
        // ->add('is_list_page', ChoiceType::class, [
        //     'choices' => [
        //         'あり' => 1,
        //         'なし' => 0,
        //         'ブランク' => null,
        //     ],
        //     'required' => false,
        // ])
        // ->add('is_detail_page', ChoiceType::class, [
        //     'choices' => [
        //         'あり' => 1,
        //         'なし' => 0,
        //         'ブランク' => null,
        //     ],
        //     'required' => false,
        // ])
        // ->add('insert_end_date', TextType::class, [
        //     'required' => false
        // ])
        // ->add('pamphlet_code', TextType::class, [
        //     'label' => 'パンフレットコード',
        //     'required' => false,
        // ])
        // ->add('PurchaseGroup', FilterChoiceType::class, [
        //     'choice_label' => 'name',
        //     'required' => false,
        //     'mapped' => false,
        //     'expanded' => true,
        //     'choices' => $this->purchaseGroupRepository->findAll(),
        //     'choice_value' => function (PurchaseGroup $purchaseGroup = null) {
        //         return $purchaseGroup ? $purchaseGroup->getId() : null;
        //     },
        // ])
        // ->add('sales_start_period', DateType::class, [
        //     'label' => '販売期間(開始)',
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('sales_end_period', DateType::class, [
        //     'label' => '販売期間(終了)',
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('IntroduceGoods', IntroduceGoodType::class, [
        //     'label' => '紹介品区分',
        // ])
        // ->add('NewProductCategory', NewProductCategoryType::class, [
        //     'label' => '新商品区分',
        // ])
        // ->add('SubscriptionPurchase', SubscriptionPurchaseType::class, [
        //     'label' => '定期購入区分',
        // ])
        // ->add('discount_start_period', DateType::class, [
        //     'label' => '割引期間(開始)',
        //     'required' => false,
        //     'widget' => 'single_text',
        // ])
        // ->add('discount_end_period', DateType::class, [
        //     'label' => '割引期間(終了)',
        //     'required' => false,
        //     'widget' => 'single_text',
        // ])
        // ->add('discount_period_price', PriceType::class, [
        //     'label' => '割引期間価格',
        //     'required' => false,
        // ])
        // ->add('campaign_start_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('campaign_end_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('point', NumberType::class, [
        //     'label' => 'ポイント',
        //     'required' => false,
        // ])
        // ->add('campaign_point', NumberType::class, [
        //     'label' => 'キャンペーンポイント',
        //     'required' => false,
        // ])
        // ->add('cost', NumberType::class, [
        //     'label' => '仕入額',
        //     'required' => false,
        // ])
        // ->add('cost_start_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('cost_end_period', DateType::class, [
        //     'required' => false,
        //     'widget' => 'single_text',
        //     'format' => 'yyyy-MM-dd',
        // ])
        // ->add('cost_period_price', NumberType::class, [
        //     'label' => '期間仕入額',
        //     'required' => false,
        // ])
        // ->add('packing_material', NumberType::class, [
        //     'label' => '包材',
        //     'required' => false,
        // ])
        // ->add('settlement', NumberType::class, [
        //     'label' => '決済',
        //     'required' => false,
        // ])
        // ->add('pay_back_debt', NumberType::class, [
        //     'label' => '返済',
        //     'required' => false,
        // ])
        // ->add('dry', NumberType::class, [
        //     'label' => 'ドライ',
        //     'required' => false,
        // ])
        // ->add('discount_fee', NumberType::class, [
        //     'label' => '補完量',
        //     'required' => false,
        // ])
        // ->add('interest_rates', NumberType::class, [
        //     'label' => '金利',
        //     'required' => false,
        // ])
        // ->add('margin', NumberType::class, [
        //     'label' => 'マージン',
        //     'required' => false,
        // ])
        // ->add('purchase_limit', NumberType::class, [
        //     'label' => '購入限定数',
        //     'required' => false,
        // ])
        // ->add('purchase_minimum', NumberType::class, [
        //     'label' => '購入最低数',
        //     'required' => false,
        // ])
        // ->add('insert_start_date', DateType::class, [
        //     'label' => '掲載開始日',
        //     'required' => false,
        //     'widget' => 'single_text',
        // ])
        // ->add('insert_end_date', DateType::class, [
        //     'label' => '掲載終了日',
        //     'required' => false,
        //     'widget' => 'single_text',
        // ])
        // ->add('is_list_page', CheckboxType::class, [
        //     'label' => '一覧画面フラグ',
        //     'required' => false,
        //     'data' => false
        // ])
        // ->add('is_detail_page', CheckboxType::class, [
        //     'label' => '詳細画面フラグ',
        //     'required' => false,
        //     'data' => false
        // ])
        // ->add('status', EntityType::class, [
        //     'label' => '状態',
        //     'class' => ProductStatus::class,
        //     'choice_label' => 'name',
        //     'required' => false,
        // ])
        // ->add('TemperatureRange', TemperatureRangeType::class, [
        //     'label' => '温度帯',
        //     'required' => false,
        // ])
        // ->add('ProductSupplier', FilterChoiceType::class, [
        //     'choice_label' => 'Name',
        //     'required' => false,
        //     'multiple' => false,
        //     'mapped' => false,
        //     'expanded' => true,
        //     'choices' => $this->productSupplierRepository->findAll(),
        //     'choice_value' => function (ProductSupplier $ProductSupplier = null) {
        //         return $ProductSupplier ? $ProductSupplier->getId() : null;
        //     },
        // ])
        // ->add('keyword', TextType::class, [
        //     'label' => 'キーワード',
        //     'required' => false,
        // ])
        // ->add('product_image', FileType::class, [
        //     'multiple' => true,
        //     'required' => false,
        //     'mapped' => true,
        // ])
        // ->addEventListener(FormEvents::POST_SUBMIT, function ($event) {
        //     $form = $event->getForm();
        //     $data = $form->getData();

        //     if (empty($data['stock_unlimited']) && is_null($data['stock'])) {
        //         $form['stock_unlimited']->addError(new FormError(trans('admin.product.product_class_set_stock_quantity')));
        //     }
        // });

        // $transformer = new DataTransformer\EntityToIdTransformer($this->entityManager, ClassCategory::class);
        // $builder
        //     ->add(
        //         $builder->create('ClassCategory1', HiddenType::class)
        //             ->addModelTransformer($transformer)
        //     )
        //     ->add(
        //         $builder->create('ClassCategory2', HiddenType::class)
        //             ->addModelTransformer($transformer)
        //     );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Eccube\Entity\ProductClass',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_product_class';
    }
}
