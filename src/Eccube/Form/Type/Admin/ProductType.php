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

use Customize\Form\Type\FilterChoiceType;
use Customize\Entity\Master\BroadCategory;
use Customize\Entity\Master\MiddleCategory;
use Customize\Entity\Master\ProductDelivery;
use Customize\Entity\Master\ProductShortName;
use Customize\Entity\Master\EcLinkage;
use Customize\Entity\Master\DeliveryCalculation;
use Customize\Entity\Master\PackingManagement;
use Customize\Entity\Master\Repack;
use Customize\Entity\Master\ProcessedProductCategory;
use Customize\Entity\Master\DecompressionMethod;
use Customize\Entity\Product\ProductSupplier;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Category;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Form\Type\Master\ProductStatusType;
use Eccube\Repository\CategoryRepository;
use Customize\Repository\Master\TemperatureRangeRepository;
use Customize\Repository\Master\RegularPurchaseCategoryRepository;
use Customize\Repository\Master\IntroduceGoodRepository;
use Customize\Repository\Master\CyclePurchaseRepository;
use Customize\Repository\Master\ListPageRepository;
use Customize\Repository\Master\DetailPageRepository;
use Customize\Repository\Master\StatusRepository;
use Customize\Repository\Master\ImportRepository;
use Customize\Repository\Master\ProductIndexRepository;
use Customize\Repository\Master\UnitRepository;
use Customize\Repository\Master\BroadCategoryRepository;
use Customize\Repository\Master\MiddleCategoryRepository;
use Customize\Repository\Master\ProductShortNameRepository;
use Customize\Repository\Master\EcLinkageRepository;
use Customize\Repository\Master\DeliveryCalculationRepository;
use Customize\Repository\Master\PackingManagementRepository;
use Customize\Repository\Master\RepackRepository;
use Customize\Repository\Master\ProcessedProductCategoryRepository;
use Customize\Repository\Master\DecompressionMethodRepository;

use Eccube\Repository\ProductBrandRepository;
use Customize\Repository\Admin\Product\ProductTopicRepository;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\Master\SaleTypeType;
use Eccube\Repository\ProductMakerRepository;
use Eccube\Repository\ProductSupplierRepository;
use Eccube\Repository\ProductGiftRepository;
use Eccube\Repository\Master\BulkBuyingRepository;
use Eccube\Repository\Master\PurchasingGroupRepository;
use Eccube\Repository\ProductIconRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Class ProductType.
 */
class ProductType extends AbstractType
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var PurchasingGroupRepository
     */
    protected $purchasingGroupRepository;

    /**
     * @var ProductBrandRepository
     */
    protected $productBrandRepository;

    /**
     * @var ProductTopicRepository
     */
    protected $productTopicRepository;

    /**
     * @var ProductMakerRepository
     */
    protected $productMakerRepository;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * @var ProductGiftRepository
     */
    protected $productGiftRepository;

    /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * @var ProductIconRepository
     */
    protected $productIconRepository;

    /**
     * @var TemperatureRangeRepository
     */
    protected $temperatureRangeRepository;

    /**
     * @var IntroduceGoodRepository
     */
    protected $introduceGoodRepository;

    /**
     * @var CyclePurchaseRepository
     */
    protected $cyclePurchaseRepository;

    /**
     * @var ListPageRepository
     */
    protected $listPageRepository;

    /**
     * @var DetailPageRepository
     */
    protected $detailPageRepository;

    /**
     * @var StatusRepository
     */
    protected $statusRepository;

    /**
     * @var ImportRepository
     */
    protected $importRepository;

    /**
     * @var ProductIndexRepository
     */
    protected $productIndexRepository;

    /**
     * @var UnitRepository
     */
    protected $unitRepository;

    /**
     * @var BroadCategoryRepository
     */
    protected $broadCategoryRepository;

    /**
     * @var MiddleCategoryRepository
     */
    protected $middleCategoryRepository;

    /**
     * @var ProductShortNameRepository
     */
    protected $productShortNameRepository;

    /**
     * @var EcLinkageRepository
     */
    protected $ecLinkageRepository;
    /**
     * @var DeliveryCalculationRepository
     */
    protected $deliveryCalculationRepository;

    /**
     * @var PackingManagementRepository
     */
    protected $packingManagementRepository;

    /**
     * @var RepackRepository
     */
    protected $repackRepository;

    /**
     * @var ProcessedProductCategoryRepository
     */
    protected $processedProductCategoryRepository;

    /**
     * @var DecompressionMethodRepository
     */
    protected $decompressionMethodRepository;

    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * ProductType constructor.
     *
     * @param CategoryRepository $categoryRepository
     * @param PurchasingGroupRepository $purchasingGroupRepository
     * @param ProductBrandRepository $productBrandRepository
     * @param ProductMakerRepository $productMakerRepository
     * @param ProductSupplierRepository $productSupplierRepository
     * @param ProductGiftRepository $productGiftRepository
     * @param BulkBuyingRepository $bulkBuyingRepository
     * @param ProductIconRepository $productIconRepository
     * @param TemperatureRangeRepository $temperatureRangeRepository
     * @param RegularPurchaseCategoryRepository $regularPurchaseCategoryRepository
     * @param IntroduceGoodRepository $introduceGoodRepository
     * @param CyclePurchaseRepository $cyclePurchaseRepository
     * @param ListPageRepository $listPageRepository
     * @param DetailPageRepository $detailPageRepository
     * @param StatusRepository $statusRepository
     * @param ImportRepository $importRepository
     * @param ProductIndexRepository $productIndexRepository
     * @param UnitRepository $unitRepository
     * @param BroadCategoryRepository $broadCategoryRepository
     * @param MiddleCategoryRepository $middleCategoryRepository
     * @param ProductShortNameRepository $productShortNameRepository
     * @param EcLinkageRepository $ecLinkageRepository
     * @param DeliveryCalculationRepository $deliveryCalculationRepository
     * @param PackingManagementRepository $packingManagementRepository
     * @param RepackRepository $repackRepository
     * @param ProcessedProductCategoryRepository $processedProductCategoryRepository
     * @param DecompressionMethodRepository $decompressionMethodRepository
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        CategoryRepository $categoryRepository,
        PurchasingGroupRepository $purchasingGroupRepository,
        ProductBrandRepository $productBrandRepository,
        ProductMakerRepository $productMakerRepository,
        ProductSupplierRepository $productSupplierRepository,
        ProductGiftRepository $productGiftRepository,
        BulkBuyingRepository $bulkBuyingRepository,
        ProductIconRepository $productIconRepository,
        TemperatureRangeRepository $temperatureRangeRepository,
        RegularPurchaseCategoryRepository $regularPurchaseCategoryRepository,
        IntroduceGoodRepository $introduceGoodRepository,
        CyclePurchaseRepository $cyclePurchaseRepository,
        ListPageRepository $listPageRepository,
        DetailPageRepository $detailPageRepository,
        StatusRepository $statusRepository,
        ImportRepository $importRepository,
        ProductIndexRepository $productIndexRepository,
        UnitRepository $unitRepository,
        BroadCategoryRepository $broadCategoryRepository,
        MiddleCategoryRepository $middleCategoryRepository,
        ProductShortNameRepository $productShortNameRepository,
        EcLinkageRepository $ecLinkageRepository,
        DeliveryCalculationRepository $deliveryCalculationRepository,
        PackingManagementRepository $packingManagementRepository,
        RepackRepository $repackRepository,
        ProcessedProductCategoryRepository $processedProductCategoryRepository,
        DecompressionMethodRepository $decompressionMethodRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->categoryRepository = $categoryRepository;
        $this->purchasingGroupRepository = $purchasingGroupRepository;
        $this->productBrandRepository = $productBrandRepository;
        $this->productMakerRepository = $productMakerRepository;
        $this->productSupplierRepository = $productSupplierRepository;
        $this->productGiftRepository = $productGiftRepository;
        $this->bulkBuyingRepository = $bulkBuyingRepository;
        $this->productIconRepository = $productIconRepository;
        $this->temperatureRangeRepository = $temperatureRangeRepository;
        $this->regularPurchaseCategoryRepository = $regularPurchaseCategoryRepository;
        $this->introduceGoodRepository = $introduceGoodRepository;
        $this->cyclePurchaseRepository = $cyclePurchaseRepository;
        $this->listPageRepository = $listPageRepository;
        $this->detailPageRepository = $detailPageRepository;
        $this->statusRepository = $statusRepository;
        $this->importRepository = $importRepository;
        $this->productIndexRepository = $productIndexRepository;
        $this->unitRepository = $unitRepository;
        $this->broadCategoryRepository = $broadCategoryRepository;
        $this->middleCategoryRepository = $middleCategoryRepository;
        $this->productShortNameRepository = $productShortNameRepository;
        $this->deliveryCalculationRepository = $deliveryCalculationRepository;
        $this->packingManagementRepository = $packingManagementRepository;
        $this->repackRepository = $repackRepository;
        $this->processedProductCategoryRepository = $processedProductCategoryRepository;
        $this->decompressionMethodRepository = $decompressionMethodRepository;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $data = $builder->getData();
        $builder
            // 商品コード
            ->add('code', NumberType::class, [
            ])
            // 商品名
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            //　索引
            //TODO productindexクラス削除
            ->add('product_index', TextType::class, [
                'constraints' => [
                    new Assert\Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) {
                            if (!empty($value) && !preg_match('/^[ｦ-ﾟ\s]+$/u', $value)) {
                                $context->buildViolation('半角ｶﾀｶﾅを指定してください')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
            ])
            // ->add('product_index', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->productIndexRepository->findAll(),
            //     'choice_value' => function (productIndex $productIndex = null) {
            //         return $productIndex ? $productIndex->getId() : null;
            //     },
            // ])
            // 単位
            //TODO unitクラス削除
            ->add('unit', TextType::class, [])
            // 大分類
            ->add('BroadCategory', EntityType::class, [
                'class' => BroadCategory::class,
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
            ])
            // 中分類
            ->add('MiddleCategory', EntityType::class, [
                'class' => MiddleCategory::class,
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
            ])
            // サイトカテゴリ
            ->add('Category', EntityType::class, [
                'class' => Category::class,
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
            ])
            // 商品名略称
            //TODO productshortnameクラス削除
            ->add('product_shortname', TextType::class, [
            ])
            // 送料計算用区分
            ->add('DeliveryCalculation', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->deliveryCalculationRepository->findAll(),
                'choice_value' => function (deliveryCalculation $deliveryCalculation = null) {
                    return $deliveryCalculation ? $deliveryCalculation->getId() : null;
                },
            ])
            // 詰込管理区分
            ->add('PackingManagement', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->packingManagementRepository->findAll(),
                'choice_value' => function (packingManagement $packingManagement = null) {
                    return $packingManagement ? $packingManagement->getId() : null;
                },
            ])
            // リパック区分
            ->add('Repack', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->repackRepository->findAll(),
                'choice_value' => function (repack $repack = null) {
                    return $repack ? $repack->getId() : null;
                },
            ])
            // 加工区分
            ->add('ProcessedProductCategory', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->processedProductCategoryRepository->findAll(),
                'choice_value' => function (processedProductCategory $processedProductCategory = null) {
                    return $processedProductCategory ? $processedProductCategory->getId() : null;
                },
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
            ])
            // 量目
            ->add('weight', TextAreaType::class, [
                'required' => false,
            ])
            // 加工場所
            ->add('processing_place', TextAreaType::class, [
                'required' => false,
            ])
            // 調理方法
            ->add('cooking_method', TextAreaType::class, [
                'required' => false,
            ])
            // 解凍区分
            ->add('DecompressionMethod', FilterChoiceType::class, [
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->decompressionMethodRepository->findAll(),
                'choice_value' => function (decompressionMethod $decompressionMethod = null) {
                    return $decompressionMethod ? $decompressionMethod->getId() : null;
                },
            ])
            // 塩分
            ->add('salt_amount', TextAreaType::class, [
                'required' => false,
            ])
            // カロリー
            ->add('calorie', TextAreaType::class, [
                'required' => false,
            ])
            // アレルギー
            ->add('allergy', TextAreaType::class, [
                'required' => false,
            ])
            // 原材料
            ->add('raw_materials', TextAreaType::class, [
                'required' => false,
            ])
            // 商品説明
            ->add('description_detail', TextareaType::class, [
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
                ],
            ])
            // 商品説明(一覧)
            ->add('description_list', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
                ],
            ])
            // 商品画像小
            ->add('product_image_small', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            // 商品画像大
            ->add('product_image_large', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            // 商品パッケージ画像
            ->add('package_image', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            // 販売種別
            ->add('sale_type', SaleTypeType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            // 備考
            ->add('note', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
                ],
            ])
            // !================================================


            // 詳細な説明
            // ->add('Tag', EntityType::class, [
            //     'class' => 'Eccube\Entity\Tag',
            //     'query_builder' => function ($er) {
            //         return $er->createQueryBuilder('t')
            //             ->orderBy('t.sort_no', 'DESC');
            //     },
            //     'required' => false,
            //     'multiple' => true,
            //     'expanded' => true,
            //     'mapped' => false,
            // ])
            // ->add('search_word', TextType::class, [
            //     'required' => false,
            //     'constraints' => [
            //         new Assert\Length(['max' => $this->eccubeConfig['eccube_ltext_len']]),
            //     ],
            // ])
            // サブ情報
            // ->add('free_area', TextareaType::class, [
            //     'required' => false,
            //     'constraints' => [
            //         new TwigLint(),
            //     ],
            // ])

            // 右ブロック
            ->add('Status', ProductStatusType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])

            // タグ
            // ->add('tags', CollectionType::class, [
            //     'entry_type' => HiddenType::class,
            //     'prototype' => true,
            //     'mapped' => false,
            //     'allow_add' => true,
            //     'allow_delete' => true,
            // ])
            // 画像用 mapped false ========
            ->add('images', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('add_images', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('delete_images', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            // =================================
            ->add('return_link', HiddenType::class, [
                'mapped' => false,
            ])
            // ->add('variation_group', NumberType::class, [
            //     'required' => false,
            // ])
            // ->add('variation_priority', NumberType::class, [
            //     'required' => false,
            // ])
            // サイズ
            // ->add('size', TextType::class, [
            //     'required' => false,
            // ])
            // カラー
            // ->add('color', ColorType::class, [
            //     'required' => false,
            // ])
            // 品番
            // ->add('part_number_1', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('part_number_2', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('part_number_3', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('ProductBrand', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'required' => false,
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->productBrandRepository->findAll(),
            //     'choice_value' => function (ProductBrand $ProductBrand = null) {
            //         return $ProductBrand ? $ProductBrand->getId() : null;
            //     },
            // ])
            // ->add('ProductTopic', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->productTopicRepository ? $this->productTopicRepository->findAll() : [],
            //     'choice_value' => function (ProductTopic $ProductTopic = null) {
            //         return $ProductTopic ? $ProductTopic->getId() : null;
            //     },
            // ])
            // ->add('ProductMaker', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'required' => false,
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->productMakerRepository->findAll(),
            //     'choice_value' => function (ProductMaker $ProductMaker = null) {
            //         return $ProductMaker ? $ProductMaker->getId() : null;
            //     },
            // ])
            // ->add('ProductGift', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'required' => false,
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->productGiftRepository->findAll(),
            //     'choice_value' => function (ProductGift $ProductGift = null) {
            //         return $ProductGift ? $ProductGift->getId() : null;
            //     },
            // ])
            // ->add('BulkBuying', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'required' => false,
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->bulkBuyingRepository->findAll(),
            //     'choice_value' => function (BulkBuying $BulkBuying = null) {
            //         return $BulkBuying ? $BulkBuying->getId() : null;
            //     },
            // ])
            // 詳細Url
            // ->add('detail_url', TextType::class, [
            //     'required' => false,
            // ])
            // 一言コメント
            // ->add('one_word_comment', TextType::class, [
            //     'required' => false,
            // ])

            // 温度帯
            // ->add('temperature_range_id', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->temperatureRangeRepository->findAll(),
            //     'choice_value' => function (TemperatureRange $TemperatureRange = null) {
            //         return $TemperatureRange ? $TemperatureRange->getId() : null;
            //     },
            // ])

            // 定期購入品区分
            // ->add('regular_purchase_category_id', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->regularPurchaseCategoryRepository->findAll(),
            //     'choice_value' => function (regularPurchaseCategory $regularPurchaseCategory = null) {
            //         return $regularPurchaseCategory ? $regularPurchaseCategory->getId() : null;
            //     },
            // ])

            // ->add('attribute_1', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('attribute_2', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('attribute_3', TextType::class, [
            //     'required' => false,
            // ])
            // アイコン
            // ->add('ProductIcon', FilterChoiceType::class, [
            //     'multiple' => true,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->productIconRepository->findAll(),
            //     'choice_value' => function (ProductIcon $ProductIcon = null) {
            //         return $ProductIcon ? $ProductIcon->getId() : null;
            //     },
            //     'constraints' => [
            //         new Assert\Choice([
            //             'choices' => $this->productIconRepository->findAll(),
            //             'multiple' => true,
            //             'max' => 3,
            //         ]),
            //     ],
            // ])
            // 備考
            // ->add('note', TextAreaType::class, [
            //     'required' => false,
            // ])
            // 定期購入品区分
            // ->add('introduce_good_id', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->introduceGoodRepository->findAll(),
            //     'choice_value' => function (introduceGood $introduceGood = null) {
            //         return $introduceGood ? $introduceGood->getId() : null;
            //     },
            // ])

            // 定期購入品区分
            // ->add('cycle_purchase_id', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->cyclePurchaseRepository->findAll(),
            //     'choice_value' => function (cyclePurchase $cyclePurchase = null) {
            //         return $cyclePurchase ? $cyclePurchase->getId() : null;
            //     },
            // ])

            //            ->add('released_at', DateTimeType::class, [
            //                'required' => false,
            //            ])
            // ->add('purchase_limited_number', NumberType::class, [
            //     'required' => false,
            // ])
            // ->add('purchase_minimum_number', NumberType::class, [
            //     'required' => false,
            // ])
            // ->add('list_price', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('cost', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('delivery_cost', TextType::class, [
            //     'required' => false,
            // ])
            // ->add('delivery_calculation_number', NumberType::class, [
            //     'required' => false,
            // ])
            // ->add('publication_start_date', DateTimeType::class, [
            //     'required' => false,
            // ])
            // ->add('publication_end_date', DateTimeType::class, [
            //     'required' => false,
            // ])
            // ->add('priority', NumberType::class, [
            //     'required' => false,
            // ])
            // list page
            // ->add('is_list_page', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->listPageRepository->findAll(),
            //     'choice_value' => function (listPage $listPage= null) {
            //         return $listPage ? $listPage->getId() : null;
            //     },
            // ])

            //  detail page
            // ->add('is_detail_page', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->detailPageRepository->findAll(),
            //     'choice_value' => function (detailPage $detailPage= null) {
            //         return $detailPage ? $detailPage->getId() : null;
            //     },
            // ])

            // status
            // ->add('status_id', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->statusRepository->findAll(),
            //     'choice_value' => function (status $status = null) {
            //         return $status ? $status->getId() : null;
            //     },
            // ])

            // is import

            // ->add('is_import', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->importRepository->findAll(),
            //     'choice_value' => function (import $import = null) {
            //         return $import ? $import->getId() : null;
            //     },
            // ])

            // middle category

            // ->add('middle_category_id', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->middleCategoryRepository->findAll(),
            //     'choice_value' => function (middleCategory $middleCategory = null) {
            //         return $middleCategory ? $middleCategory->getId() : null;
            //     },
            // ])
            // ec linkage
            // ->add('ec_linkage', FilterChoiceType::class, [
            //     'choice_label' => 'Name',
            //     'multiple' => false,
            //     'mapped' => false,
            //     'expanded' => true,
            //     'choices' => $this->ecLinkageRepository->findAll(),
            //     'choice_value' => function (ecLinkage $ecLinkage = null) {
            //         return $ecLinkage ? $ecLinkage->getId() : null;
            //     },
            // ])
            ;

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            /** @var FormInterface $form */
            $form = $event->getForm();
            $saveImgDir = $this->eccubeConfig['eccube_save_image_dir'];
            $tempImgDir = $this->eccubeConfig['eccube_temp_image_dir'];
            $this->validateFilePath($form->get('delete_images'), [$saveImgDir, $tempImgDir]);
            $this->validateFilePath($form->get('add_images'), [$tempImgDir]);
        });
    }

    /**
     * 指定された複数ディレクトリのうち、いずれかのディレクトリ以下にファイルが存在するかを確認。
     *
     * @param $form FormInterface
     * @param $dirs array
     */
    private function validateFilePath($form, $dirs)
    {

        foreach ($form->getData() as $value) {
            $object = json_decode($value);
            $file = $object->file;
            $fileInDir = array_filter($dirs, function ($dir) use ($file) {
                $filePath = realpath($dir . '/' . $file);
                $topDirPath = realpath($dir);

                return strpos($filePath, $topDirPath) === 0 && $filePath !== $topDirPath;
            });
            if (!$fileInDir) {
                // $form->getRoot()['product_image']->addError(new FormError(trans('admin.product.image__invalid_path')));
                $form->getRoot()['product_image_small']->addError(new FormError(trans('admin.product.image__invalid_path')));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_product';
    }
}
