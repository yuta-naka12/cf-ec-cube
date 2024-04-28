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

use Customize\Entity\Product\ProductGift;
use Customize\Form\Type\Admin\Product\ProductGiftType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ProductGiftRepository;
use Eccube\Service\CsvExportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductGiftController extends AbstractController
{
    /**
     * @var CsvExportService
     */
    protected $csvExportService;

    /**
     * @var ProductGiftRepository
     */
    protected $productGiftRepository;

    /**
     * ProductGiftController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param ProductGiftRepository $productGiftRepository
     */
    public function __construct(
        CsvExportService $csvExportService,
        ProductGiftRepository $productGiftRepository
    )
    {
        $this->csvExportService = $csvExportService;
        $this->productGiftRepository = $productGiftRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/gift", name="admin_product_gift", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductGift/gift.twig")
     */
    public function index(Request $request)
    {
        $ProductGifts = $this->productGiftRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductGifts' => $ProductGifts,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductGifts' => $ProductGifts,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/gift/new", name="admin_product_gift_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductGift/gift_edit.twig")
     */
    public function create(Request $request)
    {
        $gift = new ProductGift();
        $builder = $this->formFactory
            ->createBuilder(ProductGiftType::class, $gift);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productGiftRepository->save($gift);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'gift' => $gift,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_gift_edit', ['id' => $gift->getId()]);
        }

        return [
            'form' => $form->createView(),
            'gift' => $gift,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/gift/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_gift_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductGift/gift_edit.twig")
     */
    public function edit(Request $request, ProductGift $gift)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductGiftType::class, $gift);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productGiftRepository->save($gift);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'gift' => $gift,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_gift_edit', ['id' => $gift->getId()]);
        }

        return [
            'form' => $form->createView(),
            'gift' => $gift,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/gift/{id}/up", requirements={"id" = "\d+"}, name="admin_product_gift_up", methods={"PUT"})
     */
    public function up(Request $request, ProductGift $gift)
    {
        $this->isTokenValid();

        try {
            $this->productGiftRepository->up($gift);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$gift->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_gift');
    }

    /**
     * @Route("/%eccube_admin_route%/product/gift/{id}/down", requirements={"id" = "\d+"}, name="admin_product_gift_down", methods={"PUT"})
     */
    public function down(Request $request, ProductGift $gift)
    {
        $this->isTokenValid();

        try {
            $this->productGiftRepository->down($gift);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$gift->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_gift');
    }

    /**
     * @Route("/%eccube_admin_route%/product/gift/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_gift_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductGift $gift)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$gift->getId()]);

        try {
            $this->productGiftRepository->delete($gift);

            $event = new EventArgs(
                [
                    'gift' => $gift,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$gift->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$gift->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $gift->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$gift->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_gift');
    }

    /**
     * カテゴリCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/gift/export", name="admin_product_gift_export", methods={"GET"})
     *
     * @param Request $request
     *
     * @return StreamedResponse
     */
    public function export(Request $request)
    {
        // タイムアウトを無効にする.
        set_time_limit(0);

        // sql loggerを無効にする.
        $em = $this->entityManager;
        $em->getConfiguration()->setSQLLogger(null);

        $response = new StreamedResponse();
        $response->setCallback(function () use ($request) {
            // CSV種別を元に初期化.
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_GIFT);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->productGiftRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Gift \Customize\Entity\Product\ProductGift */
                $Gift = $entity;

                // CSV出力項目と合致するデータを取得.
                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();
                foreach ($Csvs as $Csv) {
                    $ExportCsvRow->setData($csvService->getData($Csv, $Gift));
                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'gift_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('ギフトCSV出力ファイル名', [$filename]);

        return $response;
    }
}
