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

use Customize\Entity\Master\BroadCategory;
use Customize\Entity\Master\DecompressionMethod;
use Customize\Entity\Master\DeliveryCalculation;
use Customize\Entity\Master\MiddleCategory;
use Customize\Entity\Master\PackingManagement;
use Customize\Entity\Master\ProcessedProductCategory;
use Customize\Entity\Master\Repack;
use Customize\Entity\Product\ProductSupplier;
use Customize\Form\Type\FilterChoiceType;
use Customize\Repository\Master\BroadCategoryRepository;
use Customize\Repository\Master\DecompressionMethodRepository;
use Customize\Repository\Master\DeliveryCalculationRepository;
use Customize\Repository\Master\MiddleCategoryRepository;
use Customize\Repository\Master\PackingManagementRepository;
use Customize\Repository\Master\ProcessedProductCategoryRepository;
use Customize\Repository\Master\RepackRepository;
use Eccube\Entity\Category;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\ProductStock;
use Eccube\Entity\Tag;
use Eccube\Form\Type\Master\CategoryType as MasterCategoryType;
use Eccube\Form\Type\Master\ProductStatusType;
use Eccube\Form\Type\Master\SaleTypeType;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\Master\ProductStatusRepository;
use Eccube\Repository\ProductSupplierRepository;
use Eccube\Repository\TagRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchProductType extends AbstractType
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
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @var BroadCategoryRepository
     */
    protected $broadCategoryRepository;

    /**
     * @var MiddleCategoryRepository
     */
    protected $middleCategoryRepository;

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
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * @var DecompressionMethodRepository
     */
    protected $decompressionMethodRepository;

    /**
     * SearchProductType constructor.
     *
     * @param ProductStatusRepository $productStatusRepository
     * @param CategoryRepository $categoryRepository
     * @param TagRepository $tagRepository
     * @param BroadCategoryRepository $broadCategoryRepository
     * @param MiddleCategoryRepository $middleCategoryRepository
     * @param DeliveryCalculationRepository $deliveryCalculationRepository
     * @param PackingManagementRepository $packingManagementRepository
     * @param RepackRepository $repackRepository
     * @param ProcessedProductCategoryRepository $processedProductCategoryRepository
     * @param ProductSupplierRepository $productSupplierRepository
     * @param DecompressionMethodRepository $decompressionMethodRepository
     */
    public function __construct(
        ProductStatusRepository $productStatusRepository,
        CategoryRepository $categoryRepository,
        TagRepository $tagRepository,
        BroadCategoryRepository $broadCategoryRepository,
        MiddleCategoryRepository $middleCategoryRepository,
        DeliveryCalculationRepository $deliveryCalculationRepository,
        PackingManagementRepository $packingManagementRepository,
        RepackRepository $repackRepository,
        ProcessedProductCategoryRepository $processedProductCategoryRepository,
        ProductSupplierRepository $productSupplierRepository,
        DecompressionMethodRepository $decompressionMethodRepository
    ) {
        $this->productStatusRepository = $productStatusRepository;
        $this->categoryRepository = $categoryRepository;
        $this->tagRepository = $tagRepository;
        $this->broadCategoryRepository = $broadCategoryRepository;
        $this->middleCategoryRepository = $middleCategoryRepository;
        $this->deliveryCalculationRepository = $deliveryCalculationRepository;
        $this->packingManagementRepository = $packingManagementRepository;
        $this->repackRepository = $repackRepository;
        $this->processedProductCategoryRepository = $processedProductCategoryRepository;
        $this->productSupplierRepository = $productSupplierRepository;
        $this->decompressionMethodRepository = $decompressionMethodRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name_code', TextType::class, [
                'label' => 'admin.product.multi_search_label',
                'required' => false,
                'mapped' => false,
            ])
            ->add('code', TextType::class, [
                'label' => 'admin.product.code',
                'required' => false,
                'mapped' => false,
            ])
            //　索引
            ->add('product_index', TextType::class, [
                'label' => 'admin.product.product_index',
                'required' => false,
                'constraints' => [
                    new Assert\Callback([
                        'callback' => function ($value, ExecutionContextInterface $context) {
                            if ($value !== null && !preg_match('/^[ァ-ヾ\s]+$/u', $value)) {
                                $context->buildViolation('This value should be Katakana characters')
                                    ->addViolation();
                            }
                        },
                    ]),
                ],
            ])
            // 大分類
            ->add('BroadCategory', FilterChoiceType::class, [
                'label' => 'admin.product.broad_category_id',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->broadCategoryRepository->findAll(),
                'choice_value' => function (BroadCategory $broadCategory = null) {
                    return $broadCategory ? $broadCategory->getId() : null;
                },
            ])
            // 中分類
            ->add('MiddleCategory', FilterChoiceType::class, [
                'label' => 'admin.product.middle_category_id',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->middleCategoryRepository->findAll(),
                'choice_value' => function (MiddleCategory $middleCategory = null) {
                    return $middleCategory ? $middleCategory->getId() : null;
                },
            ])
            // サイトカテゴリ
            ->add('Category', FilterChoiceType::class, [
                'label' => 'admin.product.category',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => true,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->categoryRepository->getList(null, true),
                'choice_value' => function (Category $Category = null) {
                    return $Category ? $Category->getId() : null;
                },
                'attr' => [
                    'name' => '[]',
                ]
            ])
            // 商品名略称
            ->add('product_shortname', TextType::class, [
                'label' => 'admin.product.product_shortname',
                'required' => false,
            ])
            // 送料計算用区分
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
            // 詰込管理区分
            ->add('PackingManagement', FilterChoiceType::class, [
                'label' => 'admin.product.packing_management',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->packingManagementRepository->findAll(),
                'choice_value' => function (PackingManagement $packingManagement = null) {
                    return $packingManagement ? $packingManagement->getId() : null;
                },
            ])
            // リパック区分
            ->add('Repack', FilterChoiceType::class, [
                'label' => 'admin.product.repack',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->repackRepository->findAll(),
                'choice_value' => function (Repack $repack = null) {
                    return $repack ? $repack->getId() : null;
                },
            ])
            // 加工区分
            ->add('ProcessedProductCategory', FilterChoiceType::class, [
                'label' => 'admin.product.processed_product_category_id',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->processedProductCategoryRepository->findAll(),
                'choice_value' => function (ProcessedProductCategory $processedProductCategory = null) {
                    return $processedProductCategory ? $processedProductCategory->getId() : null;
                },
            ])
            // 仕入先
            ->add('ProductSupplier', FilterChoiceType::class, [
                'label' => 'admin.product.supplier_id',
                'required' => false,
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
                'label' => 'admin.product.weight',
                'required' => false,
            ])
            // 加工場所
            ->add('processing_place', TextAreaType::class, [
                'label' => 'admin.product.processing_place',
                'required' => false,
            ])
            // 調理方法
            ->add('cooking_method', TextAreaType::class, [
                'label' => 'admin.product.cooking_method',
                'required' => false,
            ])
            // 解凍区分
            ->add('DecompressionMethod', FilterChoiceType::class, [
                'label' => 'admin.product.decompression_method_id',
                'required' => false,
                'choice_label' => 'Name',
                'multiple' => false,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->decompressionMethodRepository->findAll(),
                'choice_value' => function (DecompressionMethod $decompressionMethod = null) {
                    return $decompressionMethod ? $decompressionMethod->getId() : null;
                },
            ])
            // 塩分
            ->add('salt_amount', TextAreaType::class, [
                'label' => 'admin.product.salt_amount',
                'required' => false,
            ])
            // カロリー
            ->add('calorie', TextAreaType::class, [
                'label' => 'admin.product.calorie',
                'required' => false,
            ])
            // アレルギー
            ->add('allergy', TextAreaType::class, [
                'label' => 'admin.product.allergy',
                'required' => false,
            ])
            // 原材料
            ->add('raw_materials', TextAreaType::class, [
                'label' => 'admin.product.raw_materials',
                'required' => false,
            ])
            // 商品説明
            ->add('description_detail', TextareaType::class, [
                'label' => 'admin.product.description_detail',
                'required' => false,
            ])
            // 販売種別
            ->add('sale_type', SaleTypeType::class, [
                'label' => 'admin.product.sale_type',
                'required' => false,
            ])
            // 備考
            ->add('note', TextareaType::class, [
                'label' => 'admin.product.note',
                'required' => false,
            ])
            // ソート用
            ->add('sortkey', HiddenType::class, [
                'label' => 'admin.list.sort.key',
                'required' => false,
            ])
            ->add('sorttype', HiddenType::class, [
                'label' => 'admin.list.sort.type',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'admin_search_product',
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
