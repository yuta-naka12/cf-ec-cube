<?php

namespace Customize\Form\Type;

use Codeception\Module\Asserts;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Form\Validator\Email;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class PamphletType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(
        EccubeConfig $eccubeConfig
    ) {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name01', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'front.pamphlet.name_last_ph',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('name02', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'front.pamphlet.name_first_ph'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('kana01', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'front.pamphlet.name_last_kana_ph'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[ァ-ヶｦ-ﾟー]+$/u',
                        'message' => 'form_error.kana_only',
                    ]),
                ]
            ])
            ->add('kana02', TextType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'front.pamphlet.name_first_kana_ph'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Regex([
                        'pattern' => '/^[ァ-ヶｦ-ﾟー]+$/u',
                        'message' => 'form_error.kana_only',
                    ]),
                ]
            ])
            ->add('postal_code', PostalType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'front.pamphlet.zip_ph'
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ]
            ])
            ->add('address', AddressType::class, [
                // 'required' => true,
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Email(['strict' => $this->eccubeConfig['eccube_rfc_email_check']]),
                ],
                'attr' => [
                    'placeholder' => 'front.pamphlet.email_ph',
                ],
            ])
            ->add('phone_number', PhoneNumberType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'front.pamphlet.phone_num_ph',
                ],
            ])
            ->add('memo', TextareaType::class, [
                'required' => false,
                'attr' => [
                    'placeholder' => 'front.pamphlet.other_ph',
                ],
                'constraints' => [
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('sort_no', TextType::class, []);
    }
}