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

use Eccube\Repository\ProductMakerRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductMakerType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ProductMakerRepository
     */
    protected $productMakerRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ProductMakerRepository $productMakerRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ProductMakerRepository $productMakerRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->productMakerRepository = $productMakerRepository;
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
            ->add('maker_name', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('maker_name_2', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('link', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('maker_image', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('add_images', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('delete_images', CollectionType::class, [
                'entry_type' => HiddenType::class,
                'prototype' => true,
                'mapped' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_1', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('free_comment_2', TextareaType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('sort_no', TextType::class, []);

            $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                /** @var FormInterface $form */
                $form = $event->getForm();
                $saveImgDir = $this->eccubeConfig['eccube_save_image_dir'];
                $tempImgDir = $this->eccubeConfig['eccube_temp_image_dir'];
                $this->validateFilePath($form->get('delete_images'), [$saveImgDir, $tempImgDir]);
                $this->validateFilePath($form->get('add_images'), [$tempImgDir]);
            });
    }

    /**
     * 指定された複数ディレクトリのうち、いずれかのディレクトリ以下にファイルが存在するかを確認。
     *
     * @param $form FormInterface
     * @param $dirs array
     */
    private function validateFilePath($form, $dirs)
    {
        foreach ($form->getData() as $fileName) {
            $fileInDir = array_filter($dirs, function ($dir) use ($fileName) {
                // すでに登録済みの写真はtrue
                $filePath = realpath('/var/www/html/html/upload/save_image' . '/' . $fileName);
                if (!$filePath) {
                    $filePath = realpath($dir . '/' . $fileName);
                    $topDirPath = realpath($dir);
                    return strpos($filePath, $topDirPath) === 0 && $filePath !== $topDirPath;
                }
                return true;
            });
            if (!$fileInDir) {
                $form->getRoot()['maker_image']->addError(new FormError(trans('admin.product.image__invalid_path')));
            }
        }
    }
}
