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

use Customize\Entity\Master\BulkBuying;
use Eccube\Repository\Master\CourseRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

class BulkBuyingType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param CourseRepository $courseRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        CourseRepository $courseRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->courseRepository = $courseRepository;
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
           
            
            ->add('class', ChoiceType::class, [
                'choices' => [
                    '通常会員' => 0,
                    '特別会員' => 1,
                ],
                'required' => false,
            ])
            ->add('sort_no', TextType::class, [])
            ->add('quantity', IntegerType::class, [])
            ->add('discount_rate', IntegerType::class, [])
            
            ;
    }
}
