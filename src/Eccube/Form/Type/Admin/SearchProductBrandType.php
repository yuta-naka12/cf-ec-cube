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

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SearchProductBrandType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function  buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id',TextType::class,[
                'label' => 'admin.product.brand.id',
                'required' => false,
            ])
            ->add('name',TextType::class,[
                'label' => 'admin.product.brand.name',
                'required' => false,
            ])
            ->add('brand_name',TextType::class,[
                'label' => 'admin.product.brand.brand_name',
                'required' => false,
            ])
            ->add('brand_name2',TextType::class,[
                'label' => 'admin.product.brand.brand_name2',
                'required' => false,
            ])
            ->add('link',TextType::class,[
                'label' => 'admin.product.brand.link',
                'required' => false,
            ])
            ->add('image_url',TextType::class,[
                'label' => 'admin.product.brand.image_url',
                'required' => false,
            ])
            ->add('comment',TextareaType::class,[
                'label' => 'admin.product.brand.comment',
                'required' => false,
            ])
            ->add('free_comment1',TextareaType::class,[
                'label' => 'admin.product.brand.free_comment1',
                'required' => false,
            ])
            ->add('free_comment2',TextareaType::class,[
                'label' => 'admin.product.brand.free_comment2',
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_search_product_brand';
    }
}
