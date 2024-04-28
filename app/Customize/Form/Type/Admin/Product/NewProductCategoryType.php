<?php

namespace Customize\Form\Type\Admin\Product;

use Customize\Entity\Master\NewProductCategory;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NewProductCategoryType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => NewProductCategory::class,
            'choice_label' => 'name',
            'placeholder' => '新商品区分を選択',
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
