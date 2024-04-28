<?php

namespace Customize\Form\Type\Admin\Product;

use Customize\Entity\Setting\PurchaseGroup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseGroupType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => PurchaseGroup::class,
            'choice_label' => 'name',
            'placeholder' => '購入グループを選択',
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
