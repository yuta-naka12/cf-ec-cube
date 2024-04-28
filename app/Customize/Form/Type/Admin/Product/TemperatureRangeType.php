<?php

namespace Customize\Form\Type\Admin\Product;

use Customize\Entity\Setting\PurchaseGroup;
use Customize\Entity\Master\TemperatureRange;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemperatureRangeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => TemperatureRange::class,
            'choice_label' => 'name',
            'placeholder' => '購入グループを選択',
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
