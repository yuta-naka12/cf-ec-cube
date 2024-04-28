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

use Customize\Entity\Master\CourseMaster;
use Customize\Entity\Master\CustomerRank;
use Customize\Entity\Master\WithdrawalReason;
use Customize\Repository\Master\CourseMasterRepository;
use Customize\Form\Type\Admin\Customer\CourseMasterType;
use Customize\Entity\Master\SettlementType;
use Customize\Form\Type\Admin\Master\OrderTimeZoneType;
use Customize\Form\Type\FilterChoiceType;
use Customize\Repository\Master\OrderTimeZoneRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\Customer;
use Eccube\Entity\Master\Authority;
use Eccube\Entity\Member;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\Master\CustomerStatusType;
use Eccube\Form\Type\Master\JobType;
use Eccube\Form\Type\Master\SexType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Type\RepeatedPasswordType;
use Eccube\Form\Validator\Email;
use Eccube\Repository\Master\SettlementTypeRepository;
use Eccube\Repository\MemberRepository;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CustomerType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var OrderTimeZoneRepository
     */
    protected $orderTimeZoneRepository;

    /**
     * @var SettlementTypeRepository
     */
    protected $settlementTypeRepository;

    /**
     * @var CourseMasterRepository
     */
    protected $courseMasterRepository;

    /**
     * @var MemberRepository
     */
    protected $memberRepository;


    /**
     * CustomerType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        OrderTimeZoneRepository $orderTimeZoneRepository,
        SettlementTypeRepository $settlementTypeRepository,
        CourseMasterRepository $courseMasterRepository,
        MemberRepository $memberRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->orderTimeZoneRepository = $orderTimeZoneRepository;
        $this->settlementTypeRepository = $settlementTypeRepository;
        $this->courseMasterRepository = $courseMasterRepository;
        $this->memberRepository = $memberRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $data = $builder->getData();
        $builder
            ->add('name', NameType::class, [
                'required' => true,
            ])
            ->add('kana', KanaType::class, [
                'required' => true,
            ])
            ->add('company_name', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_stext_len'],
                    ]),
                ],
            ])
            ->add('postal_code', PostalType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('address', AddressType::class, [
                'required' => false,
            ])
            ->add('phone_number', PhoneNumberType::class, [
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => false,
                'constraints' => [
                    // new Assert\NotBlank(),
                    new Email(['strict' => $this->eccubeConfig['eccube_rfc_email_check']]),
                ],
                'attr' => [
                    'placeholder' => 'common.mail_address_sample',
                ],
            ])

            ->add('sex', SexType::class, [
                'required' => false,
            ])
            ->add('job', JobType::class, [
                'required' => false,
            ])
            ->add('birth', BirthdayType::class, [
                'required' => true,
                'input' => 'datetime',
                'years' => range(date('Y'), date('Y') - $this->eccubeConfig['eccube_birth_max']),
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'constraints' => [
                    new Assert\LessThanOrEqual([
                        'value' => date('Y-m-d', strtotime('-1 day')),
                        'message' => 'form_error.select_is_future_or_now_date',
                    ]),

                    new Assert\NotBlank(),
                ],
                'attr' => [
                    'max' => "9999-12-31"
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'required' => false,
                'invalid_message' => 'form_error.same_password',
                'first_options' => [
                    'label' => 'member.label.pass',

                ],
                'second_options' => [
                    'label' => 'member.label.verify_pass',

                ],
                'constraints' => [
                    new Assert\Length([
                        'min' => $this->eccubeConfig['eccube_password_min_len'],
                        'max' => $this->eccubeConfig['eccube_password_max_len'],
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[[:graph:][:space:]]+$/i',
                        'message' => 'form_error.graph_only',
                    ]),
                ],
            ])

            ->add('status', CustomerStatusType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add(
                'point',
                NumberType::class,
                [
                    'required' => false,
                    'constraints' => [
                        new Assert\Regex([
                            'pattern' => "/^\d+$/u",
                            'message' => 'form_error.numeric_only',
                        ]),
                    ],
                ]
            )
            ->add('note', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_ltext_len'],
                    ]),
                ],
            ])
            ->add('note_2', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_ltext_len'],
                    ]),
                ],
            ])
            ->add('note_3', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_ltext_len'],
                    ]),
                ],
            ])
            ->add('addr03', TextType::class, [
                'required' => false,
            ])
            ->add('department', TextType::class, [
                'required' => false,
            ])
            ->add('where_hear_about_this_site', FilterChoiceType::class, [
                'required' => false,
                'choices' => [
                    '検索サイト' => 1,
                    'チラシ' => 2,
                    '過去に利用していた' => 3,
                    '友人・知人の紹介' => 4,
                    'その他' => 5,
                    '営業の電話が来た' => 6,
                ],
            ])
            ->add('survey_1', TextType::class, [
                'required' => false,
            ])
            ->add('survey_2', TextType::class, [
                'required' => false,
            ])
            ->add('sub_tel', PhoneNumberType::class, [
                'required' => false,
            ])
            ->add('fax', PhoneNumberType::class, [
                'required' => false,
            ])
            ->add('is_dm_subscription', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'はい' => 1,
                    'いいえ' => 0,
                ],
                'data' => $data['is_dm_subscription'] ?? 1,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('is_dm_subscription_2', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'はい' => 1,
                    'いいえ' => 0,
                ],
                'data' => $data['is_dm_subscription_2'] ?? 1,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('base_class', FilterChoiceType::class, [
                'required' => false,
                'choices' => [
                    '一般' => 1,
                    '特別' => 2,
                ],
                'choice_label' => function ($value, $key) {
                    return $value . ': '  . $key;
                },
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
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('order_time_zone', OrderTimeZoneType::class, [
                'required' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('site_identifier', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    'PC' => 1,
                    'モバイル' => 2,
                ],
                'choice_label' => function ($value, $key) {
                    return $value . ': '  . $key;
                },
            ])
            ->add('user_id_2', TextType::class, [
                'required' => false,
            ])
            ->add('order_type_id', FilterChoiceType::class, [
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
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('admission_type_id', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    '希望' => 1,
                    '公募' => 2,
                    '編入' => 3,
                    'ネット' => 4,
                    'ネット公募' => 5,
                    '紹介' => 6,
                    '復活' => 7,
                ],
                'choice_label' => function ($value, $key) {
                    return $value . ': '  . $key;
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('SettlementType', EntityType::class, [
                'class' => SettlementType::class,
                'choice_label' => 'name',
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'required' => false,
            ])

            ->add('is_web_order', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '許可あり' => 1,
                    'なし' => 0,
                ],
                'data' => 1,
                'choice_label' => function ($value, $key) {
                    return $value . ': '  . $key;
                },
            ])
            ->add('Member', EntityType::class, [
                'class' => Member::class,
                'placeholder' => '選択してください',
                // 'choices' => $this->memberRepository->getQueryBuilderBySearchData([
                //     'authority_id' => Authority::SA
                // ]),
                'choice_label' => function (Member $member) {
                    return $member->getId() . ': ' . $member->getName();
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'required' => false,
            ])
            ->add('delivery_week_1', NumberType::class, [
                'required' => false,
            ])
            ->add('delivery_week_2', NumberType::class, [
                'required' => false,
            ])
            ->add('delivery_date', TextType::class, [
                'required' => false,
            ])
            ->add('course_name', TextType::class, [
                'required' => false,
            ])
            ->add('delivery_code_name', TextType::class, [
                'required' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data['delivery_type'] == 1 ||  $data['delivery_type'] == null) {
                    $deliveryCodeName = $form->get('delivery_code_name')->getData();

                    if (empty($deliveryCodeName)) {
                        $form->get('delivery_code_name')->addError(new FormError('入力されていません'));
                    }
                }
            })
            ->add('delivery_preferred_time', TextType::class, [
                'required' => false,
            ])
            ->add('no_consecutive_order_count', NumberType::class, [
                'required' => false,
            ])
            ->add('CourseMaster', EntityType::class, [
                'class' => CourseMaster::class,
                'expanded' => false,
                'placeholder' => 'コースを選択してください',
                'choice_label' => function (CourseMaster $member = null) {
                    return $member->getCourseCode() . ': ' . $member->getName();
                },
                'required' => false,
            ])
            ->add('CustomerRank', EntityType::class, [
                'class' => CustomerRank::class,
                'expanded' => false,
                'placeholder' => '選択してください',
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'required' => false,
            ])
            ->add('unit_cost_rate', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            // 自動引落情報
            ->add('bank_account_symbol', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 5, 'min' => 5]),
                ],
            ])
            ->add('bank_account_number', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length(['max' => 8, 'min' => 8]),
                ],
            ])
            ->add('bank_account_name', TextType::class, [
                'required' => false,
            ])
            ->add('bank_account_name_kana', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Regex([
                        'pattern' => '/^[ァ-ヶｦ-ﾟー]+$/u',
                        'message' => 'form_error.kana_only',
                    ]),
                ],
            ])
            ->add('bank_account_registration_date', BirthdayType::class, [
                'required' => false,
                'input' => 'datetime',
                'years' => range(date('Y'), date('Y') - $this->eccubeConfig['eccube_birth_max']),
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'placeholder' => ['year' => '----', 'month' => '--', 'day' => '--'],
                'constraints' => [
                    new Assert\LessThanOrEqual([
                        'value' => date('Y-m-d', strtotime('-1 day')),
                        'message' => 'form_error.select_is_future_or_now_date',
                    ]),
                ],
                'attr' => [
                    'max' => "9999-12-31",
                ]
            ])
            ->add('customer_id', EmailType::class, [
                'required' => false,
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
                    'max' => "9999-12-31",
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'required' => false,
            ])
            ->add('withdraw_date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control',
                    'max' => "9999-12-31"
                ],
                'required' => false,
            ])
            ->add('WithdrawalReason', EntityType::class, [
                'class' => WithdrawalReason::class,
                'placeholder' => '選択してください',
                'multiple' => false,
                'expanded' => false,
                'choice_label' => function ($value, $key) {
                    return $value->getId() . ': '  . $value->getName();
                },
                'required' => false,
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data['withdraw_date'] != null) {
                    $withdrawalReason = $form->get('WithdrawalReason')->getData();

                    if (empty($withdrawalReason)) {
                        $form->get('WithdrawalReason')->addError(new FormError('入力されていません'));
                    }
                }
            })
            ->add('initial_public_offering_id', TextType::class, [
                'required' => false,
            ])
            ->add('call_list_public_date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control',
                    'max' => "9999-12-31"
                ],
                'required' => false,
            ])
            ->add('first_buy_date', DateType::class, [
                'widget' => 'single_text',
                'html5' => true,
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control',
                    'max' => "9999-12-31"
                ],
                'required' => false,
            ])
            ->add('nonpayment_times', TextType::class, [
                'required' => false,
            ])
            ->add('buy_times', TextType::class, [
                'required' => false,
            ])
            ->add('is_duplicate_phone_number', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'hidden' => 'hidden',
                ],
            ]);


        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            /** @var Customer $Customer */
            $Customer = $event->getData();
            if ($Customer->getPassword() != '' && $Customer->getPassword() == $Customer->getEmail()) {
                $form['password']['first']->addError(new FormError(trans('common.password_eq_email')));
            }
        });

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $Customer = $event->getData();

            // ポイント数が入力されていない場合0を登録
            if (is_null($Customer->getPoint())) {
                $Customer->setPoint(0);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Eccube\Entity\Customer',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_customer';
    }
}
