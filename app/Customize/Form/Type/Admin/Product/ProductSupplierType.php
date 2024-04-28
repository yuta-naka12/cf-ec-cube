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

namespace Customize\Form\Type\Admin\Product;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\Member;
use Eccube\Repository\ProductSupplierRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductSupplierType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ProductSupplierRepository $productSupplierRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ProductSupplierRepository $productSupplierRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->productSupplierRepository = $productSupplierRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('supplier_name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('phone_number', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('fax', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('email', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('postal_code', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('supplier_category_1', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('supplier_category_2', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('closing_date', DateTimeType::class, [
                'date_label' => 'Closing Date',
            ])

            ->add('payment_date', DateTimeType::class, [
                    'date_label' => 'Payment Date',
            ])
            ->add('payment_cycle', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('payment_term', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
               ],
            ])
            ->add('closing_process_target_category', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
            ],
           ])

           ->add('member', EntityType::class, [
            'required' => false,
            'class' => Member::class,
            'choice_label' => 'name',
            ])

           ->add('payment_management_class', TextType::class, [
            'required' => false,
            'constraints' => [
                new Assert\NotBlank(),
                new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
            ],
            ])
        
           ->add('balance_costs_beginning_year', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
             ])
            
            ->add('previous_payment_amount', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
             ])

           ->add('payment_rate', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
             ])
        
           ->add('basic_cost', IntegerType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
             ])
           ->add('payment_amount_method', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
                ])
            ->add('basic_cost_amount', IntegerType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                    ],
                 ])
            ->add('is_enable_transfer_infomation', CheckboxType::class, [
                    'label' => 'is information transfer enabled?',
                    'required' => false,
                 ])
            ->add('payee', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
            ->add('account_classification', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])

             ->add('debit_account_code', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
            ->add('cost_multiplier_calculation', CheckboxType::class, [
                    'label' => 'is cost multiplier calculation done?',
                    'required' => false,
                 ])
            ->add('unit_cost_rate', IntegerType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                    ],
                 ])

            ->add('is_enable_unit_cost', CheckboxType::class, [
                    'label' => 'is unit cost enabled?',
                    'required' => false,
                 ])
            
            ->add('consumption_tax_notice', CheckboxType::class, [
                    'label' => 'consumption tax notice?',
                    'required' => false,
                 ])
            ->add('calculation_of_consumption_tax_payable', CheckboxType::class, [
                    'label' => 'calculation of consumption tax payable?',
                    'required' => false,
                 ])

            ->add('unit_cost_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
            ->add('unit_cost', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
            ->add('subscription_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('vat_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('vat_calculation_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('consumption_vat_culture_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('designated_payment_cost_use_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('designated_payment_form_no', IntegerType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('payment_statement_output_type', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('payment_statement_output_format', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('date_classification', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('order_output_document_class', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('transaction', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('individual_setting_of_entry_levels', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])
                ->add('classification', TextType::class, [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                 ],
                ])


       
             
            ->add('sort_no', TextType::class, []);
    }
} 
