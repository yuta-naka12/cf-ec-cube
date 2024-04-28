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

namespace Customize\Form\Type\Admin\Master;

use Customize\Entity\Master\CourseMaster;
use Customize\Repository\Admin\Master\MtbAddressRepository;
use Eccube\Common\EccubeConfig;

use Eccube\Repository\Master\FinancialInstitutionRepository;
use SebastianBergmann\CodeCoverage\Report\Text;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class MtbAddressType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var MtbAddressRepository
     */
    protected $mtbAddressRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param MtbAddressRepository $mtbAddressRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        MtbAddressRepository $mtbAddressRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->mtbAddressRepository = $mtbAddressRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dc_code', TextType::class, [
                'required' => true,
            ])
            ->add('CourseMaster', EntityType::class, [
                'class' => CourseMaster::class,
                'choice_label' => 'name',
                'expanded' => false,
                'placeholder' => 'コースを選択してください',
                'required' => false,
            ])
            ->add('absence_rate', NumberType::class, [
                'required' => false,
            ])
            ->add('delivery_week_1', NumberType::class, [
                'required' => false,
            ])
            ->add('delivery_week_2', NumberType::class, [
                'required' => false,
            ])
            ->add('calculation_item', TextType::class, [
                'required' => false,
            ])
            ->add('delivery_index', NumberType::class, [
                'required' => false,
            ])
            ->add('driver_code', NumberType::class, [
                'required' => false,
            ])
            ->add('packing_district_area_code', TextType::class, [
                'required' => false,
            ])
            ->add('warehouse_export_classification', TextType::class, [
                'required' => false,
            ])
            ->add('sort_no', NumberType::class, [
                'required' => false,
            ]);
    }
}
