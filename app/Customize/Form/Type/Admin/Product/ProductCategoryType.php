<?php

namespace Customize\Form\Type\Admin\Product;

use Eccube\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductCategoryType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => Category::class,
            'choice_label' => 'name',
            'placeholder' => 'カテゴリを選択',
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
