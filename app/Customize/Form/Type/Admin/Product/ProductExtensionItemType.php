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

namespace Customize\Form\Type\Admin\Product;

use Eccube\Common\EccubeConfig;

use Eccube\Repository\ProductExtensionItemRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductExtensionItemType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ProductExtensionItemRepository
     */
    protected $productExtensionItemRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ProductExtensionItemRepository $productExtensionItemRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ProductExtensionItemRepository $productExtensionItemRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->productExtensionItemRepository = $productExtensionItemRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('specification_name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('is_description_page', ChoiceType::class, [
                'required' => true,
                'choices'=>[
                    '表示' => 1,
                    '非表示' => 0,
                ],
                'placeholder' => '選択してください'
            ])
            ->add('sort_no', TextType::class, [])
            ->add('gender_id', ChoiceType::class, [
                'choices'=>[
                    '男性' => 1,
                    '女性' => 2,
                ],
                'placeholder' => '選択してください'
            ])
            ->add('reason_withdrawal', TextType::class, [])
            ->add('ec_site_linked_classification_id', IntegerType::class, [])
            ->add('web_order_permission_classification_id', IntegerType::class, [])
            ->add('ordering_time', IntegerType::class, [])
            ->add('deposit_box', TextType::class, [])
            ->add('delivery_good_group', IntegerType::class, [])
            ->add('classification_shipping_cost_calculation', IntegerType::class, [])
            ->add('filling_control_table_output_classification', IntegerType::class, [])
            ->add('repack_classification', IntegerType::class, [])
            ->add('processed_product_category_id', IntegerType::class, [])
            ->add('defrosting_method_id', IntegerType::class, [])
            ->add('impoverished_area_id', IntegerType::class, [])
            ->add('pref_id', IntegerType::class, [])
            ->add('track_no', IntegerType::class, []);
    }
}
