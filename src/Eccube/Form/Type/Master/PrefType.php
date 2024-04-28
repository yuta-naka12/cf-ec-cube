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

namespace Eccube\Form\Type\Master;

use Customize\Form\Type\FilterChoiceType;
use Eccube\Entity\Master\Pref;
use Eccube\Form\Type\MasterType;
use Eccube\Repository\Master\PrefRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PrefType
 */
class PrefType extends AbstractType
{
    /**
     * @var PrefRepository
     */
    protected $prefRepository;

    /**
     * PrefType constructor.
     * @param PrefRepository $prefRepository
     */
    public function __construct(
        PrefRepository $prefRepository
    ) {
        $this->prefRepository = $prefRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'class' => 'Eccube\Entity\Master\Pref',
            'placeholder' => 'common.select__pref',
            'choices' => $this->prefRepository->findAll(),
            'choice_label' => function ($value, $key) {
                return ($key + 1) . ': '  . $value;
            },
            'choice_value' => function (?Pref $pref) {
                return $pref ? $pref->getPrefNumber() : '';
            },
            'attr' => [
                'class' => 'pref',
            ],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'pref';
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FilterChoiceType::class;
    }
}
