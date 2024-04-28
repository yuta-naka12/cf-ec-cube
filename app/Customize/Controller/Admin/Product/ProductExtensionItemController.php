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

use Customize\Entity\Product\ProductExtensionItem;
use Customize\Form\Type\Admin\Product\ProductExtensionItemType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ProductExtensionItemRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ProductExtensionItemController extends AbstractController
{
    /**
     * @var ProductExtensionItemRepository
     */
    protected $productExtensionItemRepository;

    /**
     * ProductExtensionItemController constructor.
     *
     * @param ProductExtensionItemRepository $productExtensionItemRepository
     */
    public function __construct(
        ProductExtensionItemRepository $productExtensionItemRepository
    )
    {
        $this->productExtensionItemRepository = $productExtensionItemRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/extension_item", name="admin_product_extension_item", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductExtensionItem/extension_item.twig")
     */
    public function index(Request $request)
    {
        $ProductExtensionItems = $this->productExtensionItemRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductExtensionItems' => $ProductExtensionItems,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductExtensionItems' => $ProductExtensionItems,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/extension_item/new", name="admin_product_extension_item_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductExtensionItem/extension_item_edit.twig")
     */
    public function create(Request $request)
    {
        $extension_item = new ProductExtensionItem();
        $builder = $this->formFactory
            ->createBuilder(ProductExtensionItemType::class, $extension_item);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productExtensionItemRepository->save($extension_item);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'extension_item' => $extension_item,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_extension_item_edit', ['id' => $extension_item->getId()]);
        }

        return [
            'form' => $form->createView(),
            'extension_item' => $extension_item,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/extension_item/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_extension_item_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductExtensionItem/extension_item_edit.twig")
     */
    public function edit(Request $request, ProductExtensionItem $extension_item)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductExtensionItemType::class, $extension_item);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productExtensionItemRepository->save($extension_item);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'extension_item' => $extension_item,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_extension_item_edit', ['id' => $extension_item->getId()]);
        }

        return [
            'form' => $form->createView(),
            'extension_item' => $extension_item,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/extension_item/{id}/up", requirements={"id" = "\d+"}, name="admin_product_extension_item_up", methods={"PUT"})
     */
    public function up(Request $request, ProductExtensionItem $extension_item)
    {
        $this->isTokenValid();

        try {
            $this->productExtensionItemRepository->up($extension_item);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$extension_item->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_extension_item');
    }

    /**
     * @Route("/%eccube_admin_route%/product/extension_item/{id}/down", requirements={"id" = "\d+"}, name="admin_product_extension_item_down", methods={"PUT"})
     */
    public function down(Request $request, ProductExtensionItem $extension_item)
    {
        $this->isTokenValid();

        try {
            $this->productExtensionItemRepository->down($extension_item);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$extension_item->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_extension_item');
    }

    /**
     * @Route("/%eccube_admin_route%/product/extension_item/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_extension_item_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductExtensionItem $extension_item)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$extension_item->getId()]);

        try {
            $this->productExtensionItemRepository->delete($extension_item);

            $event = new EventArgs(
                [
                    'extension_item' => $extension_item,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$extension_item->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$extension_item->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $extension_item->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$extension_item->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_extension_item');
    }
}
