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

namespace Customize\Form\Type\Admin\Setting;

use Eccube\Common\EccubeConfig;

use Eccube\Entity\Payment;
use Eccube\Form\Type\Master\PaymentType;
use Eccube\Repository\Master\CourseRepository;
use Eccube\Repository\PaymentRepository;
use Eccube\Repository\PurchaseGroupRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PurchaseGroupType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var PurchaseGroupRepository
     */
    protected $purchaseGroupRepository;

    /**
     * @var PaymentRepository
     */
    protected $paymentRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param CourseRepository $purchaseGroupRepository
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        CourseRepository $purchaseGroupRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->purchaseGroupRepository = $purchaseGroupRepository;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('delivery_method', ChoiceType::class, [
                'choices' => [
                    '倉庫' => 1,
                    '直送' => 2,
                ],
                'required' => false,
            ])
            ->add('is_delivery_separate', ChoiceType::class, [
                'choices' => [
                    '無し' => 0,
                    '有り' => 1,
                ],
                'required' => false,
            ])
            ->add('is_change_sender', ChoiceType::class, [
                'choices' => [
                    '無し' => 0,
                    '有り' => 1,
                ],
                'required' => false,
            ])
            ->add('is_single', ChoiceType::class, [
                'choices' => [
                    'いいえ' => 0,
                    'はい' => 1,
                ],
                'required' => false,
            ])
            ->add('is_single', ChoiceType::class, [
                'choices' => [
                    'いいえ' => 0,
                    'はい' => 1,
                ],
                'required' => false,
            ])
            ->add('is_specify_delivery_date', ChoiceType::class, [
                'choices' => [
                    'いいえ' => 0,
                    'はい' => 1,
                ],
                'required' => false,
            ])
            ->add('lead_time_01', TextareaType::class, [
                'required' => false,
            ])
            ->add('lead_time_02', TextareaType::class, [
                'required' => false,
            ])
            ->add('payments', PaymentType::class, [
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('icon_1', TextType::class, [
                'required' => false,
            ])
            ->add('icon_2', TextType::class, [
                'required' => false,
            ])
            ->add('icon_3', TextType::class, [
                'required' => false,
            ])
            ->add('detail_comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('complete_comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('thanks_comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('shipping_comment', TextareaType::class, [
                'required' => false,
            ])
            ->add('memo', TextareaType::class, [
                'required' => false,
            ])
            ->add('sort_no', TextType::class, []);
    }
}
