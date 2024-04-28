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

use Eccube\Common\EccubeConfig;

use Customize\Entity\Master\Pamphlet;
use Customize\Entity\Master\BulkBuying;
use Eccube\Repository\Master\PamphletRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class PamphletType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var PamphletRepository
     */
    protected $pamphletRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param PamphletRepository $pamphletRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        PamphletRepository $pamphletRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->pamphletRepository = $pamphletRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('startDate', DateType::class)
        ->add('endDate', DateType::class)
        ->add('referralCategory', IntegerType::class)
        ->add('newProductCategory', IntegerType::class)
        ->add('subscription', IntegerType::class, [
            'required' => false,
        ])
        ->add('basicPrice', MoneyType::class)
        ->add('discountStartDate', DateType::class, [
            'required' => false,
        ])
        ->add('discountEndDate', DateType::class, [
            'required' => false,
        ])
        ->add('periodPrice', MoneyType::class)
        ->add('bulkBuyingGroup', EntityType::class, [
            'class' => BulkBuying::class,
            'choice_label' => 'name' // replace with the name of the field you want to use as the label for each option
        ])
        ->add('point', IntegerType::class)
        ->add('campaignStartDate', DateType::class, [
            'required' => false,
        ])
        ->add('campaignEndDate', DateType::class, [
            'required' => false,
        ])
        ->add('purchaseAmount', MoneyType::class)
        ->add('periodStartDate', DateType::class, [
            'required' => false,
        ])
        ->add('periodEndDate', DateType::class, [
            'required' => false,
        ])
        ->add('packaging', IntegerType::class)
        ->add('settlement', IntegerType::class)
        ->add('repayment', IntegerType::class)
        ->add('dry', IntegerType::class)
        ->add('storageFee', MoneyType::class)
        ->add('interestRate', IntegerType::class,[
            'required' => false,
        ])
        ->add('margin', IntegerType::class);
    }
}
