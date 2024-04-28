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

use Customize\Entity\Product\ProductSupplier;
use Customize\Form\Type\Admin\Product\ProductSupplierType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ProductSupplierRepository;
use Eccube\Service\CsvExportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ProductSupplierController extends AbstractController
{
    /**
     * @var CsvExportService
     */
    protected $csvExportService;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * ProductSupplierController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param ProductSupplierRepository $productSupplierRepository
     */
    public function __construct(
        CsvExportService $csvExportService,
        ProductSupplierRepository $productSupplierRepository
    )
    {
        $this->csvExportService = $csvExportService;
        $this->productSupplierRepository = $productSupplierRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/supplier", name="admin_product_supplier", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductSupplier/supplier.twig")
     */
    public function index(Request $request)
    {
        $ProductSuppliers = $this->productSupplierRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductSuppliers' => $ProductSuppliers,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductSuppliers' => $ProductSuppliers,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/supplier/new", name="admin_product_supplier_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductSupplier/supplier_edit.twig")
     */
    public function create(Request $request)
    {
        $supplier = new ProductSupplier();
        $builder = $this->formFactory
            ->createBuilder(ProductSupplierType::class, $supplier);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productSupplierRepository->save($supplier);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'supplier' => $supplier,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_supplier_edit', ['id' => $supplier->getId()]);
        }

        return [
            'form' => $form->createView(),
            'supplier' => $supplier,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/supplier/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_supplier_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductSupplier/supplier_edit.twig")
     */
    public function edit(Request $request, ProductSupplier $supplier)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductSupplierType::class, $supplier);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productSupplierRepository->save($supplier);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'supplier' => $supplier,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_supplier_edit', ['id' => $supplier->getId()]);
        }

        return [
            'form' => $form->createView(),
            'supplier' => $supplier,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/supplier/{id}/up", requirements={"id" = "\d+"}, name="admin_product_supplier_up", methods={"PUT"})
     */
    public function up(Request $request, ProductSupplier $supplier)
    {
        $this->isTokenValid();

        try {
            $this->productSupplierRepository->up($supplier);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$supplier->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_supplier');
    }

    /**
     * @Route("/%eccube_admin_route%/product/supplier/{id}/down", requirements={"id" = "\d+"}, name="admin_product_supplier_down", methods={"PUT"})
     */
    public function down(Request $request, ProductSupplier $supplier)
    {
        $this->isTokenValid();

        try {
            $this->productSupplierRepository->down($supplier);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$supplier->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_supplier');
    }

    /**
     * @Route("/%eccube_admin_route%/product/supplier/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_supplier_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductSupplier $supplier)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$supplier->getId()]);

        try {
            $this->productSupplierRepository->delete($supplier);

            $event = new EventArgs(
                [
                    'supplier' => $supplier,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$supplier->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$supplier->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $supplier->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$supplier->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_supplier');
    }

    /**
     * 仕入先CSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/supplier/export", name="admin_product_supplier_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_SUPPLIER);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->productSupplierRepository
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
        $filename = 'supplier_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('仕入先CSV出力ファイル名', [$filename]);

        return $response;
    }
}
