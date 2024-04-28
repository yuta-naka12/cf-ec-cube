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

use Eccube\Entity\Category;
use Eccube\Form\Type\Admin\CategoryType;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\ProductEventRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductEventType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ProductEventRepository
     */
    protected $productEventRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ProductEventRepository $productEventRepository
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ProductEventRepository $productEventRepository,
        CategoryRepository $categoryRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->productEventRepository = $productEventRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('event_id', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('name', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('category_id', ChoiceType::class, [
                'choice_label' => 'Name',
                'mapped' => false,
                'choices' => $this->categoryRepository->getList(null, true),
                'choice_value' => function (Category $Category = null) {
                    return $Category ? $Category->getId() : null;
                },
            ])
            ->add('started_at', DateType::class, [
                'required' => false,
                'format' => "yyyy/MM/dd",
            ])
            ->add('ended_at', DateType::class, [
                'required' => false,
                'format' => "yyyy/MM/dd",
            ])
            ->add('link', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
//            ->add('is_lpo_display', CheckboxType::class, [
//                'required' => false,
//                'constraints' => [
//                    new Assert\NotBlank(),
//                ],
//            ])
            ->add('keyword', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('display_position', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '通常' => 0,
                    '右メニュー' => 1,
                ],
            ])
            ->add('display_maximum_number', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '通常' => '通常',
                    '下書き' => '下書き',
                ],
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_1', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_2', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_3', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_4', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_5', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('sort_no', TextType::class, []);
    }
}
