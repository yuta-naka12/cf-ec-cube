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

use Eccube\Repository\ProductIconRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class ProductIconType extends AbstractType {
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * @var ProductIconRepository
     */
    protected $productIconRepository;

    /**
     * MemberType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     * @param ProductIconRepository $productIconRepository
     */
    public function __construct(
        EccubeConfig $eccubeConfig,
        ProductIconRepository $productIconRepository
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->productIconRepository = $productIconRepository;
    }

    /**
     * {@inheritdoc} 
     */
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('icon_name', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['max' => $this->eccubeConfig['eccube_stext_len']]),
                ],
            ])
            ->add('icon_image', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            ->add('add_image', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('delete_image', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('comment', TextareaType::class, [
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
            $tempImgDir = $this->eccubeConfig['eccube_temp_image_dir'];
            $this->validateFilePath($form->get('add_image'), [$tempImgDir]);
        });
    }

    /**
     * 指定された複数ディレクトリのうち、いずれかのディレクトリ以下にファイルが存在するかを確認。
     *
     * @param $form FormInterface
     * @param $dirs array
     */
    private function validateFilePath($form, $dirs) {
        $fileName = $form->getData();
        $fileInDir = array_filter($dirs, function ($dir) use ($fileName) {
            $filePath = realpath($this->eccubeConfig['eccube_save_image_dir'] . '/' . $fileName);
            if (!$filePath) {
                $filePath = realpath($dir . '/' . $fileName);
                $topDirPath = realpath($dir);
                return strpos($filePath, $topDirPath) === 0 && $filePath !== $topDirPath;
            }
            return true;
        });
        if (!$fileInDir) {
            $form->getRoot()['icon_image']->addError(new FormError(trans('admin.product.image__invalid_path')));
        }
    }
}
