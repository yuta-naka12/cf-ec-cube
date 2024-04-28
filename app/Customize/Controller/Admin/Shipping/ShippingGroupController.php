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

namespace Customize\Controller\Admin\Shipping;

use Customize\Entity\Master\OrderDeliveryType;
use Customize\Entity\Master\OrderDesireDeliveryTimeType;
use Customize\Entity\Product\ProductGift;
use Customize\Entity\Shipping\ShippingGroup;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Product;
use Eccube\Entity\Shipping;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Service\CsvImportService;
use Eccube\Service\OrderStateMachine;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShippingGroupController extends AbstractCsvImportController
{
    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var OrderStateMachine
     */
    protected $orderStateMachine;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function __construct(
        OrderRepository $orderRepository,
        OrderStateMachine $orderStateMachine,
        ProductRepository $productRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStateMachine = $orderStateMachine;
        $this->productRepository = $productRepository;
    }

    /**
     * 締め作業
     *
     * @Route("/%eccube_admin_route%/shipping/close-order", name="admin_shipping_close_order", methods={"GET", "POST"})
     * @Template("@admin/Shipping/close_order.twig")
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function csvShippingGroup(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
        $columnConfig = $this->getColumnConfig();
        $errors = [];
        $headers = $this->getShippingCsvHeader();

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('受注CSV締め作業開始');
                    $data = $this->getImportData($formFile);
                    if ($data === false) {
                        $this->addErrors(trans('admin.common.csv_invalid_format'));

                        return $this->renderWithError($form, $headers, false);
                    }

                    $getId = function ($item) {
                        return $item['id'];
                    };
                    $requireHeader = array_keys(array_map($getId, array_filter($headers, function ($value) {
                        return $value['required'];
                    })));

                    $headerByKey = array_flip(array_map($getId, $headers));

                    $columnHeaders = $data->getColumnHeaders();
                    if (count(array_diff($requireHeader, $columnHeaders)) > 0) {
                        $this->addErrors(trans('admin.common.csv_invalid_format'));

                        return $this->renderWithError($form, $headers, false);
                    }

                    $size = count($data);
                    if ($size < 1) {
                        $this->addErrors(trans('admin.common.csv_invalid_no_data'));

                        return $this->renderWithError($form, $headers, false);
                    }
                    $this->entityManager->getConfiguration()->setSQLLogger(null);
                    $this->entityManager->getConnection()->beginTransaction();

                    $ShippingGroup = new ShippingGroup();
                    $ShippingGroup->setStatus(1);
                    $ShippingGroup->setCreatedAt(new \DateTime());
                    $this->entityManager->persist($ShippingGroup);

                    $insertedIds = [];
                    // CSVファイルの登録処理
                    foreach ($data as $row) {
                        /* @var Shipping $Order */
                        if ($row[0] && !in_array($row[0],$insertedIds)) {
                            $Order = $this->orderRepository->find($row[0]);

                            // 存在しない出荷IDはエラー
                            if (is_null($Order)) {
                                $errors[] = '未入力項目があります';
                                continue;
                            }

                            if (isset($row[$headerByKey['order_id']]) && strlen($row[$headerByKey['order_id']]) > 0) {
                                if (!preg_match('/^\d+$/', $row[$headerByKey['order_id']])) {
                                    $this->addErrors(($data->key() + 1) . '行目のIDが存在しません。');

                                    return $this->renderWithError($form, $headers);
                                }
                            }

                            if ($this->hasErrors()) {
                                return $this->renderWithError($form, $headers);
                            }

                            $Order->setShippingGroup($ShippingGroup);
                            $this->entityManager->persist($Order);

                            // インサート済みのIDを登録
                            array_push($insertedIds, $row[0]);
                        }
                    }

                    $this->entityManager->getConnection()->commit();
                    $this->entityManager->flush();
                    log_info('締め作業完了');
                    $message = '締め作業が完了しました';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }

        return [
            'form' => $form->createView(),
            'headers' => $columnConfig,
            'errors' => $errors,
        ];
    }

    protected function getColumnConfig()
    {
        return [
            trans('admin.shipping.shipping_csv.shipping_id_col')  => [
                'name' => trans('admin.shipping.shipping_csv.shipping_id_col'),
                'description' => trans('admin.order.shipping_csv.shipping_id_description'),
                'required' => true,
            ],
        ];
    }

    /**
     * ギフトCSVヘッダー定義
     */
    protected function getShippingCsvHeader()
    {
        return [
            trans('admin.shipping.shipping_csv.shipping_id_col') => [
                'id' => 'order_id',
                'description' => 'admin.product.gift_csv_import.id.description',
                'required' => true,
            ],
        ];
    }

    /**
     * 登録、更新時のエラー画面表示
     */
    protected function addErrors($message)
    {
        $this->errors[] = $message;
    }

    /**
     * @return array
     */
    protected function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return boolean
     */
    protected function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * 登録、更新時のエラー画面表示
     *
     * @param FormInterface $form
     * @param array $headers
     * @param bool $rollback
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function renderWithError($form, $headers, $rollback = true)
    {
        if ($this->hasErrors()) {
            if ($rollback) {
                $this->entityManager->getConnection()->rollback();
            }
        }

        $this->removeUploadedFile();

        if ($this->isSplitCsv) {
            return $this->json([
                'success' => !$this->hasErrors(),
                'success_message' => trans('admin.common.csv_upload_line_success', [
                    '%from%' => $this->convertLineNo(2),
                    '%to%' => $this->currentLineNo, ]),
                'errors' => $this->errors,
                'error_message' => trans('admin.common.csv_upload_line_error', [
                    '%from%' => $this->convertLineNo(2), ]),
            ]);
        }

        return [
            'form' => $form->createView(),
            'headers' => $headers,
            'errors' => $this->errors,
        ];
    }

    protected function convertLineNo($currentLineNo)
    {
        if ($this->isSplitCsv) {
            return ($this->eccubeConfig['eccube_csv_split_lines']) * ($this->csvFileNo - 1) + $currentLineNo;
        }

        return $currentLineNo;
    }

    /**
     * ピッキングリスト抽出
     * @Route("/%eccube_admin_route%/shipping/{id}/picking-list", name="admin_shipping_picking_list", requirements={"id" = "\d+"}, methods={"GET"})
     * @Template("@admin/Shipping/picking_list.twig")
     */
    public function pickingList(Request $request, ShippingGroup $shippingGroup)
    {
        $orderItemRepo = $this->entityManager->getRepository(OrderItem::class);
        // ページリミット個数
        $pageLimitCount = 270;
        // 総個数
        $totalQuantities = 0;
        // 総顧客数
        $totalCustomers = 0;
        // Get Order Count
        $totalOrders = $this->orderRepository->findBy([
            'ShippingGroup' => $shippingGroup->getId()
        ]);
        $orderCount = count($totalOrders);

        // Get all products
        $allProducts = $this->productRepository->findAll();
        $orders = [];
        // Get Various type orders
        /**
         * 1. Morning & Self
         * 2. Morning & Other
         * 3. Afternoon & Self
         * 4. Afternoon & Other
         */
        $morningSelfOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::SELF,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
        $orders['morning_self']['orders'] = $morningSelfOrders;
        $orders['morning_self']['delivery_type'] = OrderDeliveryType::SELF;
        $orders['morning_self']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
        $orders['morning_self']['title'] = '自社便+朝';

        $morningOtherOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::OTHER,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
        $orders['morning_other']['orders'] = $morningOtherOrders;
        $orders['morning_other']['delivery_type'] = OrderDeliveryType::OTHER;
        $orders['morning_other']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
        $orders['morning_other']['title'] = '他社便+朝';

//        $afternoonSelfOrders = $this->orderRepository->findBy(
//            [
//                'ShippingGroup' => $shippingGroup->getId(),
//                'delivery_type' => OrderDeliveryType::SELF,
//                'desired_delivery_time' => OrderDesireDeliveryTimeType::AFTERNOON
//            ]);
//        $orders['afternoon_self']['orders'] = $afternoonSelfOrders;
//        $orders['afternoon_self']['delivery_type'] = OrderDeliveryType::SELF;
//        $orders['afternoon_self']['desired_delivery_time'] = OrderDesireDeliveryTimeType::AFTERNOON;
//        $orders['afternoon_self']['title'] = '自社便+昼';
//
//        $afternoonOtherOrders = $this->orderRepository->findBy(
//            [
//                'ShippingGroup' => $shippingGroup->getId(),
//                'delivery_type' => OrderDeliveryType::OTHER,
//                'desired_delivery_time' => OrderDesireDeliveryTimeType::AFTERNOON
//            ]);
//        $orders['afternoon_other']['orders'] = $afternoonOtherOrders;
//        $orders['afternoon_other']['delivery_type'] = OrderDeliveryType::OTHER;
//        $orders['afternoon_other']['desired_delivery_time'] = OrderDesireDeliveryTimeType::AFTERNOON;
//        $orders['afternoon_other']['title'] = '他社便+昼';

        $page = 0;
        foreach($orders as $orderKey => $orderContent) {
            foreach($allProducts as $index => $product) {
                $quantity = 0;
                $orderItems = $orderItemRepo->findBy([
                    'Product' => $product['id']
                ]);
                if (!empty($orderItems)) {
                    foreach ($orderItems as $item) {
                        $totalQuantities += $item['quantity'];
                        $quantity += (int) $item['quantity'];
                    }
                }
                if ($index == 0 || $index % $pageLimitCount === 0 ) {
                    $page++;
                }
                $orders[$orderKey]['data'][$page][] = [
                    'name' => $product['name'],
                    'quantity' => $quantity,
                ];
            }
        }

        // 作成日時
        $createDate = new \DateTime();

        return [
            'orders' => $orders,
            'totalQuantities' => $totalQuantities,
            'totalCustomers' => $orderCount,
            'createDate' => $createDate->format('Y-m-d H:i'),
            'deliveryDate' => $shippingGroup->getCreatedAt()->format('Y年m月d日'),
        ];
    }

    /**
     * 詰込管理票出力
     * @Route("/%eccube_admin_route%/shipping/{id}/packed-list", name="admin_shipping_packed_list", requirements={"id" = "\d+"}, methods={"GET"})
     * @Template("@admin/Shipping/packed_list.twig")
     */
    public function packedList(Request $request, ShippingGroup $shippingGroup)
    {
        $orders = [];
        // Get Various type orders
        /**
         * 1. Morning & Self
         * 2. Morning & Other
         * 3. Afternoon & Self
         * 4. Afternoon & Other
         */
        $morningSelfOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::SELF,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
        $orders['morning_self']['orders'] = $morningSelfOrders;
        $orders['morning_self']['delivery_type'] = OrderDeliveryType::SELF;
        $orders['morning_self']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
        $orders['morning_self']['title'] = '自社便+朝';
        $morningOtherOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::OTHER,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
//        $orders['morning_other']['orders'] = $morningOtherOrders;
//        $orders['morning_other']['delivery_type'] = OrderDeliveryType::OTHER;
//        $orders['morning_other']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
//        $orders['morning_other']['title'] = '他社便+朝';
//
//        $afternoonSelfOrders = $this->orderRepository->findBy(
//            [
//                'ShippingGroup' => $shippingGroup->getId(),
//                'delivery_type' => OrderDeliveryType::SELF,
//                'desired_delivery_time' => OrderDesireDeliveryTimeType::AFTERNOON
//            ]);
//        $orders['afternoon_self']['orders'] = $afternoonSelfOrders;
//        $orders['afternoon_self']['delivery_type'] = OrderDeliveryType::SELF;
//        $orders['afternoon_self']['desired_delivery_time'] = OrderDesireDeliveryTimeType::AFTERNOON;
//        $orders['afternoon_self']['title'] = '自社便+昼';
//
//        $afternoonOtherOrders = $this->orderRepository->findBy(
//            [
//                'ShippingGroup' => $shippingGroup->getId(),
//                'delivery_type' => OrderDeliveryType::OTHER,
//                'desired_delivery_time' => OrderDesireDeliveryTimeType::AFTERNOON
//            ]);
//        $orders['afternoon_other']['orders'] = $afternoonOtherOrders;
//        $orders['afternoon_other']['delivery_type'] = OrderDeliveryType::OTHER;
//        $orders['afternoon_other']['desired_delivery_time'] = OrderDesireDeliveryTimeType::AFTERNOON;
//        $orders['afternoon_other']['title'] = '他社便+昼';

        // 作成日時
        $createDate = new \DateTime();
        return [
            'contents' => $orders,
            'createDate' => $createDate->format('Y-m-d H:i'),
            'deliveryDate' => $shippingGroup->getCreatedAt()->format('Y年m月d日'),
        ];
    }

    /**
     * コンテナ出力
     * @Route("/%eccube_admin_route%/shipping/{id}/container-list", name="admin_shipping_container_list", requirements={"id" = "\d+"}, methods={"GET"})
     * @Template("@admin/Shipping/container_list.twig")
     */
    public function containerList(Request $request, ShippingGroup $shippingGroup)
    {
        $orderItemRepo = $this->entityManager->getRepository(OrderItem::class);
        // Get Order Count
        $totalOrders = $this->orderRepository->findBy([
            'ShippingGroup' => $shippingGroup->getId()
        ]);
        $orders = [];
        // Get Various type orders
        /**
         * 1. Morning & Self
         * 2. Morning & Other
         * 3. Afternoon & Self
         * 4. Afternoon & Other
         */
        $morningSelfOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::SELF,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
        $orders['morning_self']['orders'] = $morningSelfOrders;
        $orders['morning_self']['delivery_type'] = OrderDeliveryType::SELF;
        $orders['morning_self']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
        $orders['morning_self']['title'] = '自社便+朝';
        $morningOtherOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::OTHER,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
        $orders['morning_other']['orders'] = $morningOtherOrders;
        $orders['morning_other']['delivery_type'] = OrderDeliveryType::OTHER;
        $orders['morning_other']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
        $orders['morning_other']['title'] = '他社便+朝';


        $page = 0;
        $data = [];
        $customerUserKey = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J'];
        foreach($orders as $orderKey => $orderContents) {
            $page = 0;
            $contentsLimit = 10;
            $items = [];
            foreach($orderContents['orders'] as $index => $orderContent) {
                if ($index % $contentsLimit == 0 || $index == 0) {
                    if ($index != 0) {
                        $data[$orderKey][$page]['data'] = $items;
                    }
                    $items = [];
                    $page++;
                }

                $customerKey = $customerUserKey[$index == 0
                    ? $index
                    : $index % $contentsLimit
                ];

                $data[$orderKey][$page]['order'][] = [
                    'customer_name' => $orderContent['name01'] . ' ' .$orderContent['name02'],
                    'customer_key' => $customerKey,
                    'customer_id' => $orderContent['id'],
                ];

                $orderItems = $orderItemRepo->findBy([
                    'Order' => $orderContent['id']
                ]);

                foreach($orderItems as $item) {
                    if (!array_key_exists($item['id'], $items)) {
                        $items[$item['id']] = [
                            'name' => $item['product_name'],
                            'customer_keys' => [$customerKey],
                            'quantity' => (int) $item['quantity']
                        ];
                    } else {
                        array_push($items[$item['id']]['customer_keys'], $customerKey);
                        $items[$item['id']]['quantity'] += (int) $item['quantity'];
                    }
                }

            }
            $data[$orderKey][$page]['data'] = $items;
        }
        // 作成日時
        $createDate = new \DateTime();
        return [
            'contents' => $data,
            'createDate' => $createDate->format('Y-m-d H:i'),
            'deliveryDate' => $shippingGroup->getCreatedAt()->format('Y年m月d日'),
        ];
    }

    /**
     * 請求書出力
     * @Route("/%eccube_admin_route%/shipping/{id}/delivery-invoice", name="admin_shipping_delivery_invoice", requirements={"id" = "\d+"}, methods={"GET"})
     * @Template("@admin/Shipping/delivery_invoice.twig")
     */
    public function deliveryInvoice(Request $request, ShippingGroup $shippingGroup)
    {
        /**
         * 1. Morning & Self
         * 2. Morning & Other
         * 3. Afternoon & Self
         * 4. Afternoon & Other
         */
        $morningSelfOrders = $this->orderRepository->findBy(
            [
                'ShippingGroup' => $shippingGroup->getId(),
                'delivery_type' => OrderDeliveryType::SELF,
                'desired_delivery_time' => OrderDesireDeliveryTimeType::MORNING
            ]);
        $orders['morning_self']['orders'] = $morningSelfOrders;
        $orders['morning_self']['delivery_type'] = OrderDeliveryType::SELF;
        $orders['morning_self']['desired_delivery_time'] = OrderDesireDeliveryTimeType::MORNING;
        $orders['morning_self']['title'] = '自社便+朝';
        // 作成日時
        $createDate = new \DateTime();

        return [
            'orders' => $orders,
            'createDate' => $createDate->format('Y-m-d H:i'),
            'deliveryDate' => $shippingGroup->getCreatedAt()->format('Y年m月d日'),
        ];
    }
}
