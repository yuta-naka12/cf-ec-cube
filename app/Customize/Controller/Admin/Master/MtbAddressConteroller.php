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

namespace Customize\Controller\Admin\Master;

use Customize\Entity\Master\MtbAddress;
use Customize\Form\Type\Admin\Master\MtbAddressType;

use Customize\Repository\Admin\Master\MtbAddressRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Service\CsvExportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class MtbAddressConteroller extends AbstractController
{
    /**
     * @var CsvExportService
     */
    protected $csvExportService;

    /**
     * @var MtbAddressRepository
     */
    protected $mtbAddressRepository;

    /**
     * MtbAddressController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param MtbAddressRepository $mtbAddressRepository
     */
    public function __construct(
        CsvExportService $csvExportService,
        MtbAddressRepository $mtbAddressRepository
    )
    {
        $this->csvExportService = $csvExportService;
        $this->mtbAddressRepository = $mtbAddressRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address", name="admin_master_mtb_address", methods={"GET", "PUT"})
     * @Template("@admin/Master/MtbAddress/address.twig")
     */
    public function index(Request $request)
    {
        $MtbAddresses = $this->mtbAddressRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'MtbAddresses' => $MtbAddresses,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'MtbAddresses' => $MtbAddresses,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address/new", name="admin_master_mtb_address_new", methods={"GET", "POST"})
     * @Template("@admin/Master/MtbAddress/address_edit.twig")
     */
    public function create(Request $request)
    {
        $MtbAddress = new MtbAddress();
        $builder = $this->formFactory
            ->createBuilder(MtbAddressType::class, $MtbAddress);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mtbAddressRepository->save($MtbAddress);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'MtbAddress' => $MtbAddress,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_master_mtb_address_edit', ['id' => $MtbAddress->getId()]);
        }

        return [
            'form' => $form->createView(),
            'MtbAddress' => $MtbAddress,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address/{id}/edit", requirements={"id" = "\d+"}, name="admin_master_mtb_address_edit", methods={"GET", "POST"})
     * @Template("@admin/Master/MtbAddress/address_edit.twig")
     */
    public function edit(Request $request, MtbAddress $mtbAddress)
    {
        $builder = $this->formFactory
            ->createBuilder(MtbAddressType::class, $mtbAddress);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->mtbAddressRepository->save($mtbAddress);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'supplier' => $mtbAddress,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_master_mtb_address_edit', ['id' => $mtbAddress->getId()]);
        }

        return [
            'form' => $form->createView(),
            'MtbAddress' => $mtbAddress,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address/{id}/up", requirements={"id" = "\d+"}, name="admin_master_mtb_address_up", methods={"PUT"})
     */
    public function up(Request $request, MtbAddress $MtbAddress)
    {
        $this->isTokenValid();

        try {
            $this->mtbAddressRepository->up($MtbAddress);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$MtbAddress->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_supplier');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address/{id}/down", requirements={"id" = "\d+"}, name="admin_master_mtb_address_down", methods={"PUT"})
     */
    public function down(Request $request, MtbAddress $MtbAddress)
    {
        $this->isTokenValid();

        try {
            $this->mtbAddressRepository->down($MtbAddress);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$MtbAddress->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_supplier');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address/{id}/delete", requirements={"id" = "\d+"}, name="admin_master_mtb_address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, MtbAddress $MtbAddress)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$MtbAddress->getId()]);

        try {
            $this->mtbAddressRepository->delete($MtbAddress);

            $event = new EventArgs(
                [
                    'supplier' => $MtbAddress,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$MtbAddress->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$MtbAddress->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $MtbAddress->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$MtbAddress->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_supplier');
    }

    /**
     * 仕入先CSVの出力.
     *
     * @Route("/%eccube_admin_route%/setting/system/master/mtb_address/export", name="admin_master_mtb_address_export", methods={"GET"})
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

            $qb = $this->mtbAddressRepository
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
