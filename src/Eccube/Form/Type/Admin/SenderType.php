<?php

namespace Eccube\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Eccube\Entity\BaseInfo;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\KanaType;
use Eccube\Form\Type\NameType;
use Eccube\Form\Type\PhoneNumberType;
use Eccube\Form\Type\PostalType;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\DeliveryRepository;
use Eccube\Repository\DeliveryTimeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class SenderType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var DeliveryRepository
     */
    protected $deliveryRepository;

    /**
     * @var DeliveryTimeRepository
     */
    protected $deliveryTimeRepository;

    /**
     * @var BaseInfo
     */
    protected $BaseInfo;

    /**
     * ShippingType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param DeliveryRepository $deliveryRepository
     * @param DeliveryTimeRepository $deliveryTimeRepository
     * @param BaseInfoRepository $baseInfoRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        DeliveryRepository $deliveryRepository,
        DeliveryTimeRepository $deliveryTimeRepository,
        BaseInfoRepository $baseInfoRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->deliveryRepository = $deliveryRepository;
        $this->deliveryTimeRepository = $deliveryTimeRepository;
        $this->BaseInfo = $baseInfoRepository->get();
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class, [
                'required' => false,
            ])
            ->add('name', NameType::class, [
                'required' => false,
                'options' => [
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            ])
            ->add('kana', KanaType::class, [
                'required' => false,
                'options' => [
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                ],
            ])
            ->add('postal_code', PostalType::class, [
                'required' => true,
            ])
            ->add('address', AddressType::class, [
                'required' => false,
                'pref_options' => [
                    'constraints' => [
                        new Assert\NotBlank(),
                    ],
                    'attr' => ['class' => 'p-region-id'],
                ],
                'addr01_options' => [
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => $this->eccubeConfig['eccube_mtext_len'],
                        ]),
                    ],
                    'attr' => [
                        'class' => 'p-locality p-street-address',
                        'placeholder' => 'admin.common.address_sample_01',
                    ],
                ],
                'addr02_options' => [
                    'required' => false,
                    'constraints' => [
                        new Assert\NotBlank(),
                        new Assert\Length([
                            'max' => $this->eccubeConfig['eccube_mtext_len'],
                        ]),
                    ],
                    'attr' => [
                        'class' => 'p-extended-address',
                        'placeholder' => 'admin.common.address_sample_02',
                    ],
                ],
            ])
            ->add('phone_number', PhoneNumberType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('company_name', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_stext_len'],
                    ]),
                ],
            ])
            ->add('department', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_stext_len'],
                    ]),
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Customize\Entity\Order\Sender',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'sender';
    }
}
