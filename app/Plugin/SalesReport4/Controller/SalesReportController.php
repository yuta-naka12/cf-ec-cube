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

namespace Plugin\SalesReport4\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Repository\CustomerRepository;
use Knp\Component\Pager\PaginatorInterface;
use Plugin\SalesReport4\Form\Type\SalesReportType;
use Plugin\SalesReport4\Service\SalesReportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class SalesReportController.
 */
class SalesReportController extends AbstractController
{
    /** @var SalesReportService */
    protected $salesReportService;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * SalesReportController constructor.
     *
     * @param SalesReportService $salesReportService
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        SalesReportService $salesReportService,
        CustomerRepository $customerRepository
    ) {
        $this->salesReportService = $salesReportService;
        $this->customerRepository = $customerRepository;
    }

    /**
     * 期間別集計.
     *
     * @param Request $request
     * @Route("%eccube_admin_route%/plugin/sales_report/term", name="sales_report_admin_term")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function term(Request $request)
    {
        return $this->response($request, 'term');
    }

    /**
     * 商品別集計.
     *
     * @param Request $request
     * @Route("%eccube_admin_route%/plugin/sales_report/product", name="sales_report_admin_product")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function product(Request $request)
    {
        return $this->response($request, 'product');
    }

    /**
     * 年代別集計.
     *
     * @param Request $request
     * @Route("%eccube_admin_route%/plugin/sales_report/age", name="sales_report_admin_age")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function age(Request $request)
    {
        return $this->response($request, 'age');
    }

    /**
     * 会員別集計.
     *
     * @param Request $request
     * @Route("%eccube_admin_route%/plugin/sales_report/customer", name="sales_report_admin_customer")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function customer(Request $request)
    {
        return $this->response($request, 'customer');
    }

    /**
     * 顧客情報を検索する.
     *
     * @Route("/%eccube_admin_route%/plugin/sales_report/customer/search/html", name="sales_report_search_customer_html", methods={"GET", "POST"})
     * @Template("@SalesReport4/admin/search_customer.twig")
     * @param Request $request
     * @param integer $page_no
     *
     * @return array
     */
    public function searchCustomerHtml(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        if ($request->isXmlHttpRequest() && $this->isTokenValid()) {
            log_debug('search customer start.');
            $page_count = $this->eccubeConfig['eccube_default_page_count'];
            $session = $this->session;

            if ('POST' === $request->getMethod()) {
                $page_no = 1;

                $searchData = [
                    'multi' => $request->get('search_word'),
                    'customer_status' => [
                        CustomerStatus::REGULAR,
                    ],
                ];
                $session->set('eccube.admin.order.customer.search', $searchData);
                $session->set('eccube.admin.order.customer.search.page_no', $page_no);
            } else {
                $searchData = (array) $session->get('eccube.admin.order.customer.search');
                if (is_null($page_no)) {
                    $page_no = intval($session->get('eccube.admin.order.customer.search.page_no'));
                } else {
                    $session->set('eccube.admin.order.customer.search.page_no', $page_no);
                }
            }

            $qb = $this->customerRepository->getQueryBuilderBySearchData($searchData);
            $event = new EventArgs(
                [
                    'qb' => $qb,
                    'data' => $searchData,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_EDIT_SEARCH_CUSTOMER_SEARCH, $event);

            /** @var \Knp\Component\Pager\Pagination\SlidingPagination $pagination */
            $pagination = $paginator->paginate(
                $qb,
                $page_no,
                $page_count,
                ['wrap-queries' => true]
            );

            /** @var $Customers \Eccube\Entity\Customer[] */
            $Customers = $pagination->getItems();

            if (empty($Customers)) {
                log_debug('search customer not found.');
            }

            $data = [];
            $formatName = '%s%s(%s%s)';
            foreach ($Customers as $Customer) {
                $data[] = [
                    'id' => $Customer->getId(),
                    'name' => sprintf(
                        $formatName,
                        $Customer->getName01(),
                        $Customer->getName02(),
                        $Customer->getKana01(),
                        $Customer->getKana02()
                    ),
                    'phone_number' => $Customer->getPhoneNumber(),
                    'email' => $Customer->getEmail(),
                ];
            }

            $event = new EventArgs(
                [
                    'data' => $data,
                    'Customers' => $pagination,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_EDIT_SEARCH_CUSTOMER_COMPLETE, $event);
            $data = $event->getArgument('data');

            return [
                'data' => $data,
                'pagination' => $pagination,
            ];
        }

        throw new BadRequestHttpException();
    }

    /**
     * 顧客情報を検索する.
     *
     * @Route("/%eccube_admin_route%/plugin/sales_report/customer/search/id", name="sales_report_search_customer_by_id", methods={"POST"})
     *
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function searchCustomerById(Request $request)
    {
        if ($request->isXmlHttpRequest() && $this->isTokenValid()) {
            log_debug('search customer by id start.');

            /** @var $Customer \Eccube\Entity\Customer */
            $Customer = $this->customerRepository
                ->find($request->get('id'));

            $event = new EventArgs(
                [
                    'Customer' => $Customer,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_EDIT_SEARCH_CUSTOMER_BY_ID_INITIALIZE, $event);

            if (is_null($Customer)) {
                log_debug('search customer by id not found.');

                return $this->json([], 404);
            }

            log_debug('search customer by id found.');

            $data = [
                'id' => $Customer->getId(),
                'name01' => $Customer->getName01(),
                'name02' => $Customer->getName02(),
                'kana01' => $Customer->getKana01(),
                'kana02' => $Customer->getKana02(),
                'postal_code' => $Customer->getPostalCode(),
                'pref' => is_null($Customer->getPref()) ? null : $Customer->getPref()->getId(),
                'addr01' => $Customer->getAddr01(),
                'addr02' => $Customer->getAddr02(),
                'email' => $Customer->getEmail(),
                'phone_number' => $Customer->getPhoneNumber(),
                'company_name' => $Customer->getCompanyName(),
                'department' => $Customer->getDepartment(),
            ];

            $event = new EventArgs(
                [
                    'data' => $data,
                    'Customer' => $Customer,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_EDIT_SEARCH_CUSTOMER_BY_ID_COMPLETE, $event);
            $data = $event->getArgument('data');

            return $this->json($data);
        }

        throw new BadRequestHttpException();
    }

    /**
     * 商品CSVの出力.
     *
     * @param Request $request
     * @param string $type
     * @Route("%eccube_admin_route%/plugin/sales_report/export/{type}", name="sales_report_admin_export", methods={"POST"})
     *
     * @return StreamedResponse
     */
    public function export(Request $request, $type)
    {
        set_time_limit(0);
        $response = new StreamedResponse();
        $session = $request->getSession();
        if ($session->has('eccube.admin.sales_report.export')) {
            $searchData = $session->get('eccube.admin.sales_report.export');
        } else {
            $searchData = [];
        }

        $data = [
            'graph' => null,
            'raw' => null,
        ];

        // Query data from database
        if ($searchData) {
            if ($searchData['term_end']) {
                $searchData['term_end'] = $searchData['term_end']->modify('- 1 day');
            }
            $data = $this->salesReportService
                ->setReportType($type)
                ->setTerm($searchData['term_type'], $searchData)
                ->getData();
        }

        $response->setCallback(function () use ($data, $request, $type) {
            $exportSeparator = $this->eccubeConfig['eccube_csv_export_separator'];
            $exportEncoding = $this->eccubeConfig['eccube_csv_export_encoding'];
            // Export data by type
            switch ($type) {
                case 'term':
                    $this->salesReportService->exportTermCsv($data['raw'], $exportSeparator, $exportEncoding);
                    break;
                case 'product':
                    $this->salesReportService->exportProductCsv($data['raw'], $exportSeparator, $exportEncoding);
                    break;
                case 'age':
                    $this->salesReportService->exportAgeCsv($data['raw'], $exportSeparator, $exportEncoding);
                    break;
                default:
                    $this->salesReportService->exportTermCsv($data['raw'], $exportSeparator, $exportEncoding);
            }
        });

        // Set filename by type
        $now = new \DateTime();
        switch ($type) {
            case 'term':
                $filename = 'salesreport_term_' . $now->format('YmdHis') . '.csv';
                break;
            case 'product':
                $filename = 'salesreport_product_' . $now->format('YmdHis') . '.csv';
                break;
            case 'age':
                $filename = 'salesreport_age_' . $now->format('YmdHis') . '.csv';
                break;
            default:
                $filename = 'salesreport_term_' . $now->format('YmdHis') . '.csv';
        }

        $response->headers->set('Content-Type', 'application/octet-stream;');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->send();
        log_info('商品CSV出力ファイル名', [$filename]);

        return $response;
    }

    /**
     * direct by report type(default term).
     *
     * @param Request $request
     * @param null $reportType
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function response(Request $request, $reportType = null)
    {
        $builder = $this->formFactory
            ->createBuilder(SalesReportType::class);
        if (!is_null($reportType) && $reportType !== 'term') {
            $builder->remove('unit');
        }
        /* @var $form \Symfony\Component\Form\Form */
        $form = $builder->getForm();
        $form->handleRequest($request);

        $searchCustomerModalForm = null;

        if ($reportType === 'customer') {
            $builder = $this->formFactory->createBuilder(SearchCustomerType::class);
            $searchCustomerModalForm = $builder->getForm()->createView();
        }

        $data = [
            'graph' => null,
            'raw' => null,
        ];

        $options = [];

        if (!is_null($reportType) && $form->isSubmitted() && $form->isValid()) {
            $session = $request->getSession();
            $searchData = $form->getData();
            $searchData['term_type'] = $form->get('term_type')->getData();
            $session->set('eccube.admin.sales_report.export', $searchData);
            $termType = $form->get('term_type')->getData();

            if ($reportType !== 'customer') {
                $data = $this->salesReportService
                    ->setReportType($reportType)
                    ->setTerm($termType, $searchData)
                    ->getData();
            }else {
                $data = $this->salesReportService
                    ->setReportType($reportType)
                    ->setTerm($termType, $searchData)
                    ->setCustomer($form->get('search_customer')->getData())
                    ->getCustomerData();
            }
            
            $options = $this->getRenderOptions($reportType, $searchData);
        }

        $template = is_null($reportType) ? 'term' : $reportType;
        log_info('SalesReport Plugin : render ', ['template' => $template]);
        return $this->render(
            '@SalesReport4/admin/' . $template . '.twig',
            [
                'form' => $form->createView(),
                'graphData' => json_encode($data['graph']),
                'rawData' => $data['raw'],
                'type' => $reportType,
                'options' => $options,
                'searchCustomerModalForm' => $searchCustomerModalForm,
            ]
        );
    }

    /**
     * get option params for render.
     *
     * @param $termType
     * @param $searchData
     *
     * @return array options
     */
    private function getRenderOptions($termType, $searchData)
    {
        $options = [];

        switch ($termType) {
            case 'term':
                // 期間の集計単位
                if (isset($searchData['unit'])) {
                    $options['unit'] = $searchData['unit'];
                }
                break;
            default:
                // no option
                break;
        }

        return $options;
    }
}
