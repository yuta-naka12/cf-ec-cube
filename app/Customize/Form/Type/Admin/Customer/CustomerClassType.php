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

namespace Customize\Form\Type\Admin\Customer;

use Eccube\Common\EccubeConfig;


use Eccube\Entity\Payment;
use Eccube\Form\Type\Master\PaymentType;
use Eccube\Repository\CustomerClassRepository;
use Eccube\Repository\PaymentRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CustomerClassType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var CustomerClassRepository
     */
    protected $customerClassRepository;

    /**
     * @var PaymentRepository
     */
    protected $paymentRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param CustomerClassRepository $customerClassRepository
     * @param PaymentRepository $paymentRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        CustomerClassRepository $customerClassRepository,
        PaymentRepository $paymentRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->customerClassRepository = $customerClassRepository;
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
            ->add('Payment', PaymentType::class, [
                'multiple' => true,
                'mapped' => false,
                'expanded' => true,
                'choices' => $this->paymentRepository->getList(null, true),
                'choice_value' => function (Payment $Payment = null) {
                    return $Payment ? $Payment->getId() : null;
                },
            ])
            ->add('partition_rate', NumberType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    '通常' => 0,
                    '廃止' => 1,
                ],
                'required' => false,
            ])
            ->add('sort_no', NumberType::class, []);
    }
}
