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

namespace Eccube\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractType;
use Eccube\Entity\Category;
// use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class CategoryType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * CategoryType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'max' => $this->eccubeConfig['eccube_stext_len'],
                    ]),
                ],
            ])

            ->add('image_path', FileType::class, [
                'multiple' => true,
                'required' => false,
                'mapped' => false,
            ])
            ->add('parent', EntityType::class, [
                'required' => false, 
                'class' => Category::class,
                'choice_label' => 'name',
            ])
            ->add('add_image', HiddenType::class, [
                'mapped' => false,
            ])
            ->add('delete_image', HiddenType::class, [
                'mapped' => false,
            ]);

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
            $form->getRoot()['image_path']->addError(new FormError(trans('admin.product.image__invalid_path')));
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'Eccube\Entity\Category',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'admin_category';
    }
}
