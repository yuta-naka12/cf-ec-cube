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

namespace Customize\Controller\Admin\Product;

use Customize\Entity\Product\ProductIcon;
use Customize\Form\Type\Admin\Product\ProductIconType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ProductIconRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ProductIconController extends AbstractController
{
    /**
     * @var ProductIconRepository
     */
    protected $productIconRepository;

    /**
     * ProductIconController constructor.
     *
     * @param ProductIconRepository $productIconRepository
     */
    public function __construct(
        ProductIconRepository $productIconRepository
    ) {
        $this->productIconRepository = $productIconRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon", name="admin_product_icon", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductIcon/icon.twig")
     */
    public function index(Request $request)
    {
        $ProductIcons = $this->productIconRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductIcons' => $ProductIcons,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductIcons' => $ProductIcons
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon/new", name="admin_product_icon_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductIcon/icon_edit.twig")
     */
    public function create(Request $request) 
    {
        $icon = new ProductIcon();
        $builder = $this->formFactory
            ->createBuilder(ProductIconType::class, $icon);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // 画像の登録
            $add_image = $form->get('add_image')->getData();

            $icon->setFileName($add_image);
            // 移動
            $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $add_image);
            $file->move($this->eccubeConfig['eccube_save_image_dir']);

            $this->productIconRepository->save($icon);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'icon' => $icon,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_icon_edit', ['id' => $icon->getId()]);
        }

        return [
            'form' => $form->createView(),
            'icon' => $icon,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_icon_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductIcon/icon_edit.twig")
     */
    public function edit(Request $request, ProductIcon $icon)
    {
        $builder = $this->formFactory
        ->createBuilder(ProductIconType::class, $icon);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $delete_image = $form->get('delete_image')->getData();
            // 画像の登録
            $add_image = $form->get('add_image')->getData();
            $file_path = $this->eccubeConfig['eccube_save_image_dir'] . '/' . $add_image;
            if (!file_exists($file_path)) {
                $icon->setFileName($add_image);
                $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $add_image);
                $file->move($this->eccubeConfig['eccube_save_image_dir']);
                unlink($this->eccubeConfig['eccube_save_image_dir'] . '/' . $delete_image);
            }

            $this->productIconRepository->save($icon);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'icon' => $icon,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_icon_edit', ['id' => $icon->getId()]);
        };

        return [
            'form' => $form->createView(),
            'icon' => $icon,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon/{id}/up", requirements={"id" = "\d+"}, name="admin_product_icon_up", methods={"PUT"})
     */
    public function up(Request $request, ProductIcon $icon)
    {
        $this->isTokenValid();

        try {
            $this->productIconRepository->up($icon);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$icon->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_icon');
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon/{id}/down", requirements={"id" = "\d+"}, name="admin_product_icon_down", methods={"PUT"})
     */
    public function down(Request $request, ProductIcon $icon)
    {
        $this->isTokenValid();

        try {
            $this->productIconRepository->down($icon);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$icon->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_icon');
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_icon_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductIcon $icon)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$icon->getId()]);

        try {
            $this->productIconRepository->delete($icon);

            $event = new EventArgs(
                [
                    'icon' => $icon,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$icon->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$icon->getId()]);
            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $icon->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$icon->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_icon');
    }

    /**
     * @Route("/%eccube_admin_route%/product/icon/image/add", name="admin_icon_image_add", methods={"POST"})
     */
    public function addImage(Request $request)
    {
        if (!$request->isXmlHttpRequest() && $this->isTokenValid()) {
            throw new BadRequestHttpException();
        }

        $images = $request->files->get('product_icon'); 

        $allowExtensions = ['gif', 'jpg', 'jpeg', 'png'];
        $files = [];
        if (count($images) > 0) {
            foreach ($images as $img) {
                foreach ($img as $image) {
                    //ファイルフォーマット検証
                    $mimeType = $image->getMimeType();
                    if (0 !== strpos($mimeType, 'image')) {
                        throw new UnsupportedMediaTypeHttpException();
                    }

                    // 拡張子
                    $extension = $image->getClientOriginalExtension();
                    if (!in_array(strtolower($extension), $allowExtensions)) {
                        throw new UnsupportedMediaTypeHttpException();
                    }

                    $filename = date('mdHis') . uniqid('_') . '.' . $extension;
                    $image->move($this->eccubeConfig['eccube_temp_image_dir'], $filename);
                    $files[] = $filename;
                }
            }
        }

        $event = new EventArgs(
            [
                'images' => $images,
                'files' => $files,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_ADD_IMAGE_COMPLETE, $event);
        $files = $event->getArgument('files');

        return $this->json(['files' => $files], 200);
    }
}
