<?php

namespace Customize\Form\Type\Admin\Product;

use Customize\Entity\Master\IntroduceGood;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class IntroduceGoodType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => IntroduceGood::class,
            'choice_label' => 'name',
            'placeholder' => '紹介品区分を選択',
        ]);
    }

    public function getParent()
    {
        return EntityType::class;
    }
}
