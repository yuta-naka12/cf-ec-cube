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

namespace Eccube\Form\Type\Admin;

use Customize\Entity\Customer\CustomerClass;
use Customize\Entity\Master\CourseMaster;
use Customize\Entity\Master\CustomerRank;
use Customize\Entity\Master\SettlementType;
use Customize\Entity\Master\WithdrawalReason;
use Customize\Form\Type\Admin\Master\OrderTimeZoneType;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Category;
use Eccube\Entity\Master\Authority;
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Entity\Member;
use Eccube\Entity\ProductCategory;
use Eccube\Form\Type\Master\CustomerStatusType;
use Eccube\Form\Type\Master\PrefType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\PriceType;
use Eccube\Repository\Master\CustomerStatusRepository;
use Eccube\Repository\MemberRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SearchType;


class SearchCustomerType extends AbstractType
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
     * @var MemberRepository
     */
    protected $memberRepository;

    /**
     * SearchCustomerType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param CustomerStatusRepository $customerStatusRepository
     */
    public function __construct(
        CustomerStatusRepository $customerStatusRepository,
        MemberRepository $memberRepository,
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->customerStatusRepository = $customerStatusRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $months = range(1, 12);
        $builder
            // 会員ID・メールアドレス・名前・名前(フリガナ)
            ->add('multi', TextType::class, [
                'label' => 'admin.customer.multi_search_label_plus_phone_number',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('customer_status', CustomerStatusType::class, [
                'label' => 'admin.customer.customer_status',
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
            ->add('CourseMaster', EntityType::class, [
                'required' => false,
                'class' => CourseMaster::class,
                'choice_label' => 'name',
                'expanded' => false,
                'placeholder' => 'コースを選択してください',
            ])
            ->add('sex', SexType::class, [
                'label' => 'admin.common.gender',
                'required' => false,
                'expanded' => true,
                'multiple' => true,
            ])
            // ->add('name01'::class, [
            //     'label' => 'admin.customer.name01',
            //     'required' => false,
            //     'expanded' => true,
            //     'multiple' => true,
            // ])
            ->add('birth_month', ChoiceType::class, [
                'label' => 'admin.customer.birth_month',
                'placeholder' => 'admin.common.select',
                'required' => false,
                'choices' => array_combine($months, $months),
            ])
            ->add('birth_start', BirthdayType::class, [
                'label' => 'admin.common.birth_day__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_birth_start',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            ->add('birth_end', BirthdayType::class, [
                'label' => 'admin.common.birth_day__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_birth_end',
                    'data-toggle' => 'datetimepicker',
                    'max' => "9999-12-31"
                ],
            ])
            ->add('pref', PrefType::class, [
                'label' => 'admin.common.pref',
                'required' => false,
                'placeholder' => '選択してください'
            ])
            ->add('addr01', TextType::class, [
                'required' => false,
            ])
            ->add('addr02', TextType::class, [
                'required' => false,
            ])
            ->add('addr03', TextType::class, [
                'required' => false,
            ])
            ->add('company_name', TextType::class, [
                'required' => false,
            ])
            ->add('phone_number', TextType::class, [
                'label' => 'admin.common.phone_number',
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[\d-]+$/u",
                        'message' => 'form_error.graph_and_hyphen_only',
                    ]),
                ],
            ])
            ->add('sub_tel', TextType::class, [
                'label' => 'admin.common.phone_number',
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[\d-]+$/u",
                        'message' => 'form_error.graph_and_hyphen_only',
                    ]),
                ],
            ])
            ->add('fax', TextType::class, [
                'label' => 'admin.common.phone_number',
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => "/^[\d-]+$/u",
                        'message' => 'form_error.graph_and_hyphen_only',
                    ]),
                ],
            ])
            ->add('user_id_2', TextType::class, [
                'label' => '会員ID',
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'label' => 'メールアドレス',
                'required' => false,
            ])
            ->add('buy_product_name', TextType::class, [
                'label' => 'admin.order.purchase_product',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('buy_total_start', PriceType::class, [
                'label' => 'admin.order.purchase_price__start',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_price_len']]),
                ],
            ])
            ->add('buy_total_end', PriceType::class, [
                'label' => 'admin.order.purchase_price__end',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_price_len']]),
                ],
            ])
            ->add('buy_times_start', IntegerType::class, [
                'label' => 'admin.order.purchase_count__start',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_int_len']]),
                ],
            ])
            ->add('buy_times_end', IntegerType::class, [
                'label' => 'admin.order.purchase_count__end',
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_int_len']]),
                ],
            ])
            ->add('create_date_start', DateType::class, [
                'label' => 'admin.common.create_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('create_datetime_start', DateTimeType::class, [
                'label' => 'admin.common.create_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_datetime_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('create_date_end', DateType::class, [
                'label' => 'admin.common.create_date__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_date_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('create_datetime_end', DateTimeType::class, [
                'label' => 'admin.common.create_date__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_create_datetime_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('update_date_start', DateType::class, [
                'label' => 'admin.common.update_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_update_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('update_datetime_start', DateTimeType::class, [
                'label' => 'admin.common.update_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_update_datetime_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('update_date_end', DateType::class, [
                'label' => 'admin.common.update_date__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_update_date_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('update_datetime_end', DateTimeType::class, [
                'label' => 'admin.common.update_date__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_update_datetime_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('last_buy_start', DateType::class, [
                'label' => 'admin.order.last_buy_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_last_buy_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('last_buy_datetime_start', DateTimeType::class, [
                'label' => 'admin.order.last_buy_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_last_buy_datetime_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('last_buy_end', DateType::class, [
                'label' => 'admin.order.last_buy_date__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_last_buy_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('last_buy_datetime_end', DateTimeType::class, [
                'label' => 'admin.order.last_buy_date__end',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd HH:mm:ss',
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_last_buy_datetime_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('target_buy_start', DateType::class, [
                'label' => 'admin.order.last_buy_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_last_buy_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('target_buy_end', DateType::class, [
                'label' => 'admin.order.last_buy_date__start',
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_last_buy_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            // ソート用
            ->add('sortkey', HiddenType::class, [
                'label' => 'admin.list.sort.key',
                'required' => false,
            ])
            ->add('sorttype', HiddenType::class, [
                'label' => 'admin.list.sort.type',
                'required' => false,
            ])

            ->add('name', SearchType::class, [
                'required' => false,
                'attr' => [
                    'maxlength' => 50,
                ],
            ])
            ->add('delivery_date', ChoiceType::class, [
                'label' => '曜日',
                'required' => false,
                'choices' => [
                    '火' => '火',
                    '水' => '水',
                    '木' => '木',
                    '金' => '金',
                    '土' => '土',
                ],
            ])
            ->add('delivery_code_name', TextType::class, [
                'label' => 'DCコード',
                'required' => false,
            ])
            ->add('delivery_week', TextType::class, [
                'label' => '配送週',
                'required' => false,
            ])
            ->add('is_dm_subscription', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'はい' => 1,
                    'いいえ' => 0,
                ],
            ])
            ->add('is_dm_subscription_2', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'はい' => 1,
                    'いいえ' => 0,
                ],
            ])
            ->add('base_class', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '一般' => 1,
                    '特別' => 2,
                ],
            ])
            ->add('delivery_type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '自社便' => 1,
                    '宅急便' => 2,
                ],
            ])
            ->add('order_time_zone', OrderTimeZoneType::class, [
                'required' => false,
            ])
            ->add('site_identifier', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'PC' => 1,
                    'モバイル' => 2,
                ],
            ])
            ->add('user_id_2', TextType::class, [
                'required' => false,
            ])
            ->add('order_type_id', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'ネット' => 1,
                    'FAX' => 2,
                    'フリー' => 3,
                    'LINE' => 4,
                ],
            ])
            ->add('admission_type_id', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '希望' => 1,
                    '公募' => 2,
                    '編入' => 3,
                    'ネット' => 4,
                    'ネット公募' => 5,
                    '紹介' => 6,
                    '復活' => 7,
                ],
            ])
            ->add('SettlementType', EntityType::class, [
                'class' => SettlementType::class,
                'choice_label' => 'name',
                'multiple' => false,
                'expanded' => false,
            ])
            ->add('is_web_order', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'なし' => 0,
                    'あり' => 1,
                ],
            ])
            ->add('delivery_week_1', NumberType::class, [
                'required' => false,
            ])
            ->add('delivery_week_2', NumberType::class, [
                'required' => false,
            ])
            ->add('delivery_preferred_time', TextType::class, [
                'required' => false,
            ])
            ->add('no_consecutive_order_count_start', NumberType::class, [
                'required' => false,
            ])
            ->add('no_consecutive_order_count_end', NumberType::class, [
                'required' => false,
            ])
            ->add('CustomerRank', EntityType::class, [
                'class' => CustomerRank::class,
                'choice_label' => 'name',
                'expanded' => false,
                'placeholder' => '会員区分を選択してください',
            ])
            ->add('map_page', TextType::class, [
                'required' => false,
            ])
            ->add('entry_date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control',
                ],
            ])
            ->add('withdraw_date_start', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_withdraw_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('withdraw_date_end', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_withdraw_date_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('WithdrawalReason', EntityType::class, [
                'class' => WithdrawalReason::class,
                'choice_label' => 'name',
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
            ->add('initial_public_offering_id', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'なし' => 0,
                    'あり' => 1,
                ],
            ])
            ->add('call_list_public_date_start', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_call_list_public_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('call_list_public_date_end', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_call_list_public_date_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('first_buy_date_start', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_first_buy_date_start',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('first_buy_date_end', DateType::class, [
                'required' => false,
                'input' => 'datetime',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'attr' => [
                    'class' => 'datetimepicker-input',
                    'data-target' => '#' . $this->getBlockPrefix() . '_first_buy_date_end',
                    'data-toggle' => 'datetimepicker',
                ],
            ])
            ->add('nonpayment_times_start', TextType::class, [
                'required' => false,
            ])
            ->add('nonpayment_times_end', TextType::class, [
                'required' => false,
            ])
            ->add('buy_times_start', TextType::class, [
                'required' => false,
            ])
            ->add('buy_times_end', TextType::class, [
                'required' => false,
            ])
            ->add('no_consecutive_order_count', TextType::class, [
                'required' => false,
            ])
            ->add('customer_id', TextType::class, [
                'required' => false,
            ])
            ->add('product_code', TextType::class, [
                'required' => false,
            ])
            ->add('ProductCategory', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
            ])
            ->add('total_buy_times_start', NumberType::class, [
                'required' => false,
            ])
            ->add('total_buy_times_end', NumberType::class, [
                'required' => false,
            ])
            ->add('total_purchase_price_start', PriceType::class, [
                'required' => false,
            ])
            ->add('total_purchase_price_end', PriceType::class, [
                'required' => false,
            ])
            ->add('total_purchase_price_end', PriceType::class, [
                'required' => false,
            ])
            ->add('postal_code', TextType::class, [
                'required' => false,
            ])
            ->add('where_hear_about_this_site', TextType::class, [
                'required' => false,
            ])
            ->add('is_dm_subscription', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'はい' => 1,
                    'いいえ' => 0,
                ],
                'placeholder' => '選択して下さい'
            ])
            ->add('is_dm_subscription_2', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'はい' => 1,
                    'いいえ' => 0,
                ],
                'placeholder' => '選択して下さい'
            ])
            ->add('delivery_type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '自社便' => 1,
                    '宅急便' => 2,
                ],
                'choice_label' => function ($value, $key) {
                    return $value . ': '  . $key;
                },
                'placeholder' => '選択して下さい'
            ])
            ->add('order_type_id', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'ネット' => 1,
                    'FAX' => 2,
                    'フリー' => 3,
                    'LINE' => 4,
                ],
                'choice_label' => function ($value, $key) {
                    return $value . ': '  . $key;
                },
                'placeholder' => '選択して下さい'
            ])
            ->add('SettlementType', EntityType::class, [
                'class' => SettlementType::class,
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
            ])
            ->add('Member', ChoiceType::class, [
                'placeholder' => '選択してください',
                'choices' => $this->memberRepository->getQueryBuilderBySearchData([
                    'authority_id' => Authority::SA
                ]),
                'choice_label' => function (Member $member = null) {
                    return $member->getId() . ': ' . $member->getName();
                }
            ])
            ->add('note', TextareaType::class, [
                'required' => false,
            ])
            ->add('note_2', TextareaType::class, [
                'required' => false,
            ])
            ->add('note_3', TextareaType::class, [
                'required' => false,
            ])
            ->add('point_start', TextType::class, [
                'required' => false,
            ])
            ->add('point_end', TextType::class, [
                'required' => false,
            ]);

    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_search_customer';
    }
}
