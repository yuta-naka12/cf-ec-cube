<?php

namespace Customize\Form\Type\Admin\GiftAddress;

use Doctrine\ORM\EntityRepository;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\AddressType;
use Eccube\Form\Type\PostalType;
use Eccube\Repository\GiftAddressRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GiftAddressType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var GiftAddressRepository
     */
    protected $giftAddressRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param GiftAddressRepository $giftAddressRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        GiftAddressRepository $giftAddressRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->giftAddressRepository = $giftAddressRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('customer', ChoiceType::class, [
                'required' => true,
                'choices' => $options['customers'],
                'choice_label' => function ($customer) {
                    return $customer->getName01() . ' ' . $customer->getName02() . ' (' . $customer->getEmail() . ')';
                },
                'choice_value' => function ($customer) {
                    return $customer? $customer->getId() : null;
                },
            ])
            ->add('postal_code', PostalType::class, [
                'required' => true,
            ])
            ->add('address', AddressType::class, [
                'required' => true,
            ])
            ->add('addr03', TextType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'customers' => [],
        ]);
    }
}
