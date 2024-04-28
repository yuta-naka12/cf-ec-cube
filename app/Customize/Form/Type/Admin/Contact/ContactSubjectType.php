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

use Eccube\Repository\ContactSubjectRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ContactSubjectType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ContactSubjectRepository
     */
    protected $contactSubjectRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ContactSubjectRepository $contactSubjectRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ContactSubjectRepository $contactSubjectRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->contactSubjectRepository = $contactSubjectRepository;
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
            ->add('send_address', TextType::class, [
                'required' => false,
            ])
            ->add('reply_address', TextType::class, [
                'required' => false,
            ])
            ->add('reply_bcc_address', TextType::class, [
                'required' => false,
            ])
            ->add('status', ChoiceType::class, [
                'choices' => [
                    '非表示' => 0,
                    '通常' => 1,
                ],
                'required' => false,
            ])
            ->add('sort_no', TextType::class, []);
    }
}
