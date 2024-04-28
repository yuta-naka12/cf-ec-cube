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
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Form\Type\Master\CustomerStatusType;
use Eccube\Form\Type\Master\PrefType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\PriceType;
use Eccube\Repository\Master\CustomerStatusRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchPointerHistoryType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var CustomerStatusRepository
     */
    protected $customerStatusRepository;

    /**
     * SearchCustomerType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param CustomerStatusRepository $customerStatusRepository
     */
    public function __construct(
        CustomerStatusRepository $customerStatusRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->customerStatusRepository = $customerStatusRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months = range(1, 12);
        $builder
            // 会員ID・メールアドレス・名前・名前(フリガナ)
            ->add('user', TextType::class, [
                'label' => 'admin.customer.user01',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
                'placeholder' => false,
                'data' => $this->customerStatusRepository->findBy([
                    'id' => [
                        CustomerStatus::PROVISIONAL,
                        CustomerStatus::REGULAR,
                    ],
                ]),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_search_customer';
    }
}
