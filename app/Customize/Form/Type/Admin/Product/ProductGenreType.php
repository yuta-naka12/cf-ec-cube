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

use Customize\Entity\Product\ProductGenreDisplayMode;
use Eccube\Common\EccubeConfig;
 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Customize\Entity\Product\ProductGenre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Blank;
use Symfony\Component\Validator\Constraints\Choice;

class ProductGenreType extends AbstractType {
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('genre_hierarchy', EntityType::class, [ 
                'required' => true,
                'class' => ProductGenre::class,
                'choice_label' => 'name',
                // 'constraints' => [
                //     new Assert\NotBlank(),
                //     new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                // ],
            ])
            ->add('genre_name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('genre_name_2', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('comment', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('display_mode_default', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    'L:一覧' => 'L:一覧',
                    'D:詳細一覧' => 'D:詳細一覧',
                    'Q:ピックアップ（１列）' => 'Q:ピックアップ（１列）',
                    'P:ピックアップ' => 'P:ピックアップ',
                    'U:サムネイル（１列）' => 'U:サムネイル（１列）',
                    'V:サムネイル（２列）' => 'V:サムネイル（２列）',
                    'T:サムネイル' => 'T:サムネイル',
                    'S:リスト' => 'S:リスト',
                    'R:ランキング' => 'R:ランキング',
                    'M:メーカー' => 'M:メーカー',
                    'I:写真付き' => 'I:写真付き',
                    'N:商品名' => 'N:商品名',
                    'B:一括購入' => 'B:一括購入',
                    'A:チェックリスト' => 'A:チェックリスト',
                    'H:サムネイル（１列購入ボタンなし）' => 'H:サムネイル（１列購入ボタンなし）',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('ProductGenreDisplayMode', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    '一覧' => '1',
                    '詳細一覧' => '2',
                    'ピックアップ（１列）' => '3',
                    'ピックアップ' => '4',
                    'サムネイル（１列）' => '5',
                    'サムネイル（２列）' => '6',
                    'サムネイル' => '7',
                    'リスト' => '8',
                    'ランキング' => '9',
                    'メーカー' => '10',
                    '写真付き' => '11',
                    '商品名' => '12',
                    '一括購入' => '13',
                    'チェックリスト' => '14',
                    'サムネイル（１列購入ボタンなし）' => '15',
                ],
                'expanded' => true,
                'multiple' => true,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('free_space_top', TextAreaType::class, [
                'required' => false,
            ])
            ->add('free_space_bottom', TextAreaType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '通常' => '通常',
                    '廃止' => '廃止',
                ],
            ])
            ->add('sort_no', TextType::class, []);
    }
}
