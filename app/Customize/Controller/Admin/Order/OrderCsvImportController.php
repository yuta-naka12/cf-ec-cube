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

namespace Customize\Controller\Admin\Order;

use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Shipping;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\OrderRepository;
use Eccube\Service\CsvImportService;
use Eccube\Service\OrderStateMachine;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderCsvImportController extends AbstractCsvImportController
{
    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderStateMachine
     */
    protected $orderStateMachine;

    public function __construct(
        OrderRepository $orderRepository,
        OrderStateMachine $orderStateMachine
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStateMachine = $orderStateMachine;
    }

    /**
     * 出荷CSVアップロード
     *
     * @Route("/%eccube_admin_route%/order/order_csv_upload", name="admin_order_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Order/csv_order.twig")
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function csvShipping(Request $request)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
        $columnConfig = $this->getColumnConfig();
        $errors = [];

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();

                if (!empty($formFile)) {
                    $csv = $this->getImportData($formFile);

                    try {
                        $this->entityManager->getConfiguration()->setSQLLogger(null);
                        $this->entityManager->getConnection()->beginTransaction();

                        $this->loadCsv($csv, $errors);

                        if ($errors) {
                            $this->entityManager->getConnection()->rollBack();
                        } else {
                            $this->entityManager->flush();
                            $this->entityManager->getConnection()->commit();

                            $this->addInfo('admin.common.csv_upload_complete', 'admin');
                        }
                    } finally {
                        $this->removeUploadedFile();
                    }
                }
            }
        }

        return [
            'form' => $form->createView(),
            'headers' => $columnConfig,
            'errors' => $errors,
        ];
    }

    protected function loadCsv(CsvImportService $csv, &$errors)
    {
        $columnConfig = $this->getColumnConfig();

        if ($csv === false) {
            $errors[] = trans('admin.common.csv_invalid_format');
        }

        // 必須カラムの確認
        $requiredColumns = array_map(function ($value) {
            return $value['name'];
        }, array_filter($columnConfig, function ($value) {
            return $value['required'];
        }));
        $csvColumns = $csv->getColumnHeaders();
        if (count(array_diff($requiredColumns, $csvColumns)) > 0) {
            $errors[] = trans('admin.common.csv_invalid_format');

            return;
        }

        // 行数の確認
        $size = count($csv);
        if ($size < 1) {
            $errors[] = trans('admin.common.csv_invalid_format');

            return;
        }

        $columnNames = array_combine(array_keys($columnConfig), array_column($columnConfig, 'name'));

        foreach ($csv as $line => $row) {
            // 出荷IDがなければエラー
            if (!isset($row[$columnNames['id']])) {
                $errors[] = trans('admin.common.csv_invalid_required', ['%line%' => $line + 1, '%name%' => $columnNames['id']]);
                continue;
            }

            /* @var Shipping $Order */
            $Order = is_numeric($row[$columnNames['id']]) ? $this->orderRepository->find($row[$columnNames['id']]) : null;

            // 存在しない出荷IDはエラー
            if (is_null($Order)) {
                $errors[] = trans('admin.common.csv_invalid_not_found', ['%line%' => $line + 1, '%name%' => $columnNames['id']]);
                continue;
            }

            if (isset($row[$columnNames['tracking_number']])) {
                $Order->setTrackingNumber($row[$columnNames['tracking_number']]);
            }

            if (isset($row[$columnNames['order_date']])) {
                // 日付フォーマットが異なる場合はエラー
                $orderDate = \DateTime::createFromFormat('Y-m-d', $row[$columnNames['order_date']]);
                if ($orderDate === false) {
                    $errors[] = trans('admin.common.csv_invalid_date_format', ['%line%' => $line + 1, '%name%' => $columnNames['order_date']]);
                    continue;
                }

                $orderDate->setTime(0, 0, 0);
                $Order->setShippingDate($orderDate);
            }
        }
    }

    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/order/csv_template", name="admin_order_csv_template", methods={"GET"})
     */
    public function csvTemplate(Request $request)
    {
        $columns = array_column($this->getColumnConfig(), 'name');

        return $this->sendTemplateResponse($request, $columns, 'order.csv');
    }

    protected function getColumnConfig()
    {
        return [
            'id' => [
                'name' => trans('admin.order.order_csv.order_id_col'),
                'description' => trans('admin.order.order_csv.order_id_description'),
                'required' => true,
            ],
            'tracking_number' => [
                'name' => trans('admin.order.order_csv.tracking_number_col'),
                'description' => trans('admin.order.order_csv.tracking_number_description'),
                'required' => false,
            ],
            'order_date' => [
                'name' => trans('admin.order.order_csv.order_date_col'),
                'description' => trans('admin.order.order_csv.order_date_description'),
                'required' => true,
            ],
        ];
    }
}
