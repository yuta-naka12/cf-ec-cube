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
use Eccube\Entity\Customer;
use Eccube\Entity\Order;
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Form\Type\Master\CustomerStatusType;
use Eccube\Form\Type\Master\PrefType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\PriceType;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\PointHistoryRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class SearchPointHistoryType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var PointHistoryRepository
     */
    protected $pointHistoryRepository;

    /**
     * SearchCustomerType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        CustomerRepository $customerRepository,
        PointHistoryRepository $pointHistoryRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->customerRepository = $customerRepository;
        $this->pointHistoryRepository = $pointHistoryRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('customer', EntityType::class, [
            'class' => Customer::class,
            'choice_label' => 'name01',

        ])
        ->add('order', EntityType::class, [
            'class' => Order::class,
            'choice_label' => 'id',
        ])
        // ->add('', IntegerType::class, [])

        ->add('point', IntegerType::class, [])

        ->add('record_type', ChoiceType::class, [
            'required' => true,
            'choices'=>[
                '獲得' => 1,
                '利用' => 2,
            ],
            'placeholder' => '選択してください'
        ])

        ->add('record_event', ChoiceType::class, [
            'required' => true,
            'choices'=>[
                '単品購入' => 1,
                '会員登録 特典' => 2,
                'ご注文取り消し' => 3,
                '管理画面で編集' => 4,
                '期限切れ' => 5,
                'ポイント移行' => 6,
                'レビュー投稿' => 7,
            ],
            'placeholder' => '選択してください'
        ])

        // ->add('dtype', TextType::class, [])

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
