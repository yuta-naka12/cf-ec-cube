<?php

namespace Customize\Form\Type\Admin\Product;

use Customize\Entity\Master\SubscriptionPurchase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SubscriptionPurchaseType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => SubscriptionPurchase::class,
            'choice_label' => 'name',
            'placeholder' => '定期購入区分を選択',
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
