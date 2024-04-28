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

use Customize\Entity\Product\ProductEvent;
use Customize\Form\Type\Admin\Product\ProductEventType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\ProductEventRepository;
use Eccube\Service\CsvExportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductEventController extends AbstractController
{
    /**
     * @var ProductEventRepository
     */
    protected $productEventRepository;
    /**
     * @var CsvExportService
     */
    protected $csvExportService;
    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;

    /**
     * ProductEventController constructor.
     *
     * @param ProductEventRepository $productEventRepository
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     */
    public function __construct(
        ProductEventRepository $productEventRepository,
        CsvExportService $csvExportService,
        CsvTypeRepository $csvTypeRepository
    )
    {
        $this->productEventRepository = $productEventRepository;
        $this->csvExportService = $csvExportService;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/event", name="admin_product_event", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductEvent/event.twig")
     */
    public function index(Request $request)
    {
        $ProductEvents = $this->productEventRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductEvents' => $ProductEvents,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductEvents' => $ProductEvents,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/event/new", name="admin_product_event_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductEvent/event_edit.twig")
     */
    public function create(Request $request)
    {
        $productEvent = new ProductEvent();
        $builder = $this->formFactory
        ->createBuilder(ProductEventType::class, $productEvent);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productEventRepository->save($productEvent);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'event' => $productEvent,
                ],
                $request
            );
            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_event_edit', ['id' => $productEvent->getId()]);
        }
        return [
            'form' => $form->createView(),
            'event' => $productEvent,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/event/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_event_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductEvent/event_edit.twig")
     */
    public function edit(Request $request, ProductEvent $event)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductEventType::class, $event);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productEventRepository->save($event);

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_event_edit', ['id' => $event->getId()]);
        }

        return [
            'form' => $form->createView(),
            'event' => $event,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/event/{id}/up", requirements={"id" = "\d+"}, name="admin_product_event_up", methods={"PUT"})
     */
    public function up(Request $request, ProductEvent $event)
    {
        $this->isTokenValid();

        try {
            $this->productEventRepository->up($event);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$event->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_event');
    }

    /**
     * @Route("/%eccube_admin_route%/product/event/{id}/down", requirements={"id" = "\d+"}, name="admin_product_event_down", methods={"PUT"})
     */
    public function down(Request $request, ProductEvent $event)
    {
        $this->isTokenValid();

        try {
            $this->productEventRepository->down($event);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$event->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_event');
    }

    /**
     * @Route("/%eccube_admin_route%/product/event/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_event_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductEvent $event)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$event->getId()]);

        try {
            $this->productEventRepository->delete($event);

            $eventArg = new EventArgs(
                [
                    'event' => $event,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$event->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$event->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $event->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$event->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_event');
    }

    /**
     * イベントCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/event/export", name="admin_product_event_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_EVENT);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->productEventRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Event \Customize\Entity\ProductEvent */
                $Event = $entity;

                // CSV出力項目と合致するデータを取得.
                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();
                foreach ($Csvs as $Csv) {
                    $ExportCsvRow->setData($csvService->getData($Csv, $Event));
                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'event_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('イベントCSV出力ファイル名', [$filename]);

        return $response;
    }
}
