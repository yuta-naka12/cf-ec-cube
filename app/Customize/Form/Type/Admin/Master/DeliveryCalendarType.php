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

namespace Customize\Form\Type\Admin\Master;

use Eccube\Common\EccubeConfig;

use Eccube\Repository\Master\DeliveryCalendarRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class DeliveryCalendarType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var DeliveryCalendarRepository
     */
    protected $deliveryCalendarRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param DeliveryCalendarRepository $deliveryCalendarRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        DeliveryCalendarRepository $deliveryCalendarRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->deliveryCalendarRepository = $deliveryCalendarRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $years = range(Date('Y') - 20, date('Y') + 1);
        $month = range(1, 12);
        $deliveryWeeks = range(1,  4);
        $deliveryDays = [
            '日' => '日',
            '月' => '月',
            '火' => '火',
            '水' => '水',
            '木' => '木',
            '金' => '金',
            '土' => '土',
        ];

        $builder
            ->add('year', ChoiceType::class, [
                'choices' => array_combine($years, $years),
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('month', ChoiceType::class, [
                'choices' => array_combine($month, $month),
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('delivery_week', ChoiceType::class, [
                'choices' => array_combine($deliveryWeeks, $deliveryWeeks),
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('delivery_day', ChoiceType::class, [
                'choices' => $deliveryDays,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('sort_no', TextType::class, []);
    }
}
