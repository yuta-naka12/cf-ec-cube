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

namespace Customize\Form\Type\Admin\Contact;

use Eccube\Common\EccubeConfig;

use Eccube\Repository\ContactRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ContactRepository
     */
    protected $contactRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ContactRepository $contactRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ContactRepository $contactRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->contactRepository = $contactRepository;
    }

    /**
     * {@inheritdoc} 
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('family_name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('given_name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('order_id', TextType::class, [
                'required' => false,
            ])
            ->add('email', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('tel', TextType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    '非表示' => 0,
                    '通常' => 1,
                    '保留' => 2,
                    '対応済み' => 3,
                ],
                'required' => false,
            ])
            ->add('contact_class_1', ChoiceType::class, [
                'choices' => [
                    'クレーム' => 1,
                    'ご質問' => 2,
                    'ご意見' => 3,
                ],
                'required' => false,
            ])
            ->add('contact_class_2', ChoiceType::class, [
                'choices' => [
                    'Web' => 1,
                    '電話' => 2,
                ],
                'required' => false,
            ])
            ->add('memo', TextareaType::class, [
                'required' => false,
            ])
            ->add('title', TextType::class, [
                'required' => false,
            ])
            ->add('content', TextareaType::class, [
                'required' => false,
            ])
            ->add('classification', ChoiceType::class, [
                'choices' => [
                    '担当者回答' => 1,
                    '担当者質問' => 2,
                    'お客様お問合せ' => 3,
                    'その他' => 4,
                ],
                'required' => false,
            ])
            ->add('is_display', ChoiceType::class, [
                'choices' => [
                    '非表示' => 0,
                    '通常' => 1,
                ],
                'required' => false,
            ])
            ->add('sort_no', TextType::class, []);
    }
}
