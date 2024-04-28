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

use Customize\Form\Type\Admin\Shipping\SearchShippingType;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Entity\Master\OrderStatus;
use Eccube\Entity\Shipping;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\Shipping\ShippingGroupRepository;
use Eccube\Service\CsvImportService;
use Eccube\Service\OrderStateMachine;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ShippingController extends AbstractCsvImportController
{
    /**
     * @var ShippingGroupRepository
     */
    protected $shippingGroupRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * ShippingController constructor.
     *
     * @param ShippingGroupRepository $shippingGroupRepository
     * @param PageMaxRepository $pageMaxRepository
     */
    public function __construct(
        ShippingGroupRepository $shippingGroupRepository,
        PageMaxRepository $pageMaxRepository
    )
    {
        $this->shippingGroupRepository = $shippingGroupRepository;
        $this->pageMaxRepository = $pageMaxRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/shipping", name="admin_shipping", methods={"GET", "POST"})
     * @Template("@admin/Shipping/index.twig")
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        $ShippingGroups = $this->shippingGroupRepository->findBy([]);
        $builder = $this->formFactory->createBuilder(SearchShippingType::class);

        $searchForm = $builder->getForm();

        /**
         * ページの表示件数は, 以下の順に優先される.
         * - リクエストパラメータ
         * - セッション
         * - デフォルト値
         * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
         **/
        $page_count = $this->session->get('eccube.admin.order.search.page_count',
            $this->eccubeConfig->get('eccube_default_page_count'));

        $page_count_param = (int) $request->get('page_count');
        $pageMaxis = $this->pageMaxRepository->findAll();

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
            if ($searchForm->isValid()) {
                /**
                 * 検索が実行された場合は, セッションに検索条件を保存する.
                 * ページ番号は最初のページ番号に初期化する.
                 */
                $page_no = 1;
                $searchData = $searchForm->getData();

                // 検索条件, ページ番号をセッションに保持.
                $this->session->set('eccube.admin.shipping.search', FormUtil::getViewData($searchForm));
                $this->session->set('eccube.admin.shipping.search.page_no', $page_no);
            } else {
                // 検索エラーの際は, 詳細検索枠を開いてエラー表示する.
                return [
                    'searchForm' => $searchForm->createView(),
                    'pagination' => [],
                    'pageMaxis' => $pageMaxis,
                    'page_no' => $page_no,
                    'page_count' => $page_count,
                    'has_errors' => false,
                    'ShippingGroups' => $ShippingGroups,
                ];
            }
        } else {
            if (null !== $page_no || $request->get('resume')) {
                /*
                 * ページ送りの場合または、他画面から戻ってきた場合は, セッションから検索条件を復旧する.
                 */
                if ($page_no) {
                    // ページ送りで遷移した場合.
                    $this->session->set('eccube.admin.order.search.page_no', (int) $page_no);
                } else {
                    // 他画面から遷移した場合.
                    $page_no = $this->session->get('eccube.admin.order.search.page_no', 1);
                }
                $viewData = $this->session->get('eccube.admin.order.search', []);
                $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
            } else {
                /**
                 * 初期表示の場合.
                 */
                $page_no = 1;
                $viewData = [];

                if ($statusId = (int) $request->get('order_status_id')) {
                    $viewData = ['status' => [$statusId]];
                }

                $searchData = FormUtil::submitAndGetData($searchForm, $viewData);

                // セッション中の検索条件, ページ番号を初期化.
                $this->session->set('eccube.admin.order.search', $viewData);
                $this->session->set('eccube.admin.order.search.page_no', $page_no);
            }
        }

        if ($page_count_param) {
            foreach ($pageMaxis as $pageMax) {
                if ($page_count_param == $pageMax->getName()) {
                    $page_count = $pageMax->getName();
                    $this->session->set('eccube.admin.order.search.page_count', $page_count);
                    break;
                }
            }
        }
        $qb = $this->shippingGroupRepository->getQueryBuilderBySearchDataForAdmin($searchData);
        $pagination = $paginator->paginate(
            $qb,
            $page_no,
            $page_count,
            ['wrap-queries' => true]
        );

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $page_count,
            'has_errors' => false,
            'ShippingGroups' => $ShippingGroups,
        ];
    }
}
