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

namespace Customize\Controller\Admin\Customer;

use Customize\Entity\Point\PointHistory;
use Symfony\Component\Form\FormFactoryInterface;
use Customize\Form\Type\Admin\Customer\SearchSaCustomerType;
use Customize\Form\Type\Admin\Customer\SearchPointHistoryType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\QueryBuilder;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\Master\PrefRepository;
use Eccube\Repository\Master\SexRepository;
use Eccube\Repository\PointHistoryRepository;
use Eccube\Repository\QueryKey;
use Eccube\Util\FormUtil;
use Eccube\Util\StringUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class PointHistoryController extends AbstractController
{
    /**
     * @var PrefRepository
     */
    protected $prefRepository;

    /**
     * @var SexRepository
     */
    protected $sexRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var CustomerRepository
     */
    protected $pointHistoryRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    public function __construct(
        PageMaxRepository $pageMaxRepository,
        PointHistoryRepository $pointHistoryRepository,
        CustomerRepository $customerRepository
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->pointHistoryRepository = $pointHistoryRepository;
        $this->customerRepository = $customerRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/point/history", name="admin_point_history", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/point/history/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_point_history_page", methods={"GET", "POST"})
     * @Template("@admin/Customer/Point/History/index.twig")
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        $session = $this->session;
        $builder = $this->formFactory->createBuilder(SearchCustomerType::class);

        $event = new EventArgs(
            [
                'builder' => $builder,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CUSTOMER_INDEX_INITIALIZE, $event);

        $searchForm = $builder->getForm();

        $pageMaxis = $this->pageMaxRepository->findAll();
        $pageCount = $session->get('eccube.admin.customer.search.page_count', $this->eccubeConfig['eccube_default_page_count']);
        $pageCountParam = $request->get('page_count');
        if ($pageCountParam && is_numeric($pageCountParam)) {
            foreach ($pageMaxis as $pageMax) {
                if ($pageCountParam == $pageMax->getName()) {
                    $pageCount = $pageMax->getName();
                    $session->set('eccube.admin.customer.search.page_count', $pageCount);
                    break;
                }
            }
        }

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);

            if ($searchForm->isValid()) {
                $searchData = $searchForm->getData();
                // dd($searchData);

                $page_no = 1;

                $session->set('eccube.admin.customer.search', FormUtil::getViewData($searchForm));

                $session->set('eccube.admin.customer.search.page_no', $page_no);
            } else {
                return [
                    'searchForm' => $searchForm->createView(),
                    'pagination' => [],
                    'pageMaxis' => $pageMaxis,
                    'page_no' => $page_no,
                    'page_count' => $pageCount,
                    'has_errors' => true,
                ];
            }
        } else {
            if (null !== $page_no || $request->get('resume')) {
                if ($page_no) {
                    $session->set('eccube.admin.customer.search.page_no', (int) $page_no);
                } else {
                    $page_no = $session->get('eccube.admin.customer.search.page_no', 1);
                }
                $viewData = $session->get('eccube.admin.customer.search', []);
            } else {
                $page_no = 1;
                $viewData = FormUtil::getViewData($searchForm);
                $session->set('eccube.admin.customer.search', $viewData);
                $session->set('eccube.admin.customer.search.page_no', $page_no);
            }
            $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
        }
        /** @var QueryBuilder $qb */
        $qb = $this->pointHistoryRepository->getQueryBuilderBySearchData($searchData);

        $event = new EventArgs(
            [
                'form' => $searchForm,
                'qb' => $qb,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CUSTOMER_INDEX_SEARCH, $event);

        $pagination = $paginator->paginate(
            $qb,
            $page_no,
            $pageCount
        );

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $pageCount,
            'has_errors' => false,
        ];
    }

     /**
     * @Route("/%eccube_admin_route%/point/history/new", name="admin_point_history_new", methods={"GET", "POST"})
     * @Template("@admin/Customer/Point/History/point_history_edit.twig")
     */
    public function create(Request $request)
    {
        $pointHistory = new PointHistory();
        $builder = $this->formFactory
            ->createBuilder(SearchPointHistoryType::class, $pointHistory );

        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            // dd($data["point"]);
            $customerId = $data["Customer"]["id"];
        
            // Update the customer's points based on the points data from the form
            $points = $data["point"]; // Assuming the points data is passed as a parameter in the request
            $record_type = $data["record_type"]; // Assuming the points data is passed as a parameter in the request
            $customer = $this->customerRepository->find($customerId);
            $current_point = $customer->getPoint();
            // dd($record_type);
            if ($record_type == 2 && intval($points) > intval($current_point)){
                $this->addError('No enough points', 'admin');
                return $this->redirectToRoute('admin_point_history', ['id' => $pointHistory->getId()]);
            } elseif ($record_type == 2 && intval($current_point) > intval($points)){
                $points = $current_point - $points;
            }
            else {
                $points = $current_point + $points;
            }
                $customer->setPoint($points);

                $this->entityManager->persist($customer);
                $this->entityManager->flush();
    
                $this->pointHistoryRepository->save($pointHistory);
                $this->entityManager->flush();
    
                $event = new EventArgs(
                    [
                        'form' => $form,
                        'pointHistory' => $pointHistory,
                    ],
                    $request
                );
    
                $this->addSuccess('admin.common.save_complete', 'admin');
                return $this->redirectToRoute('admin_point_history', ['id' => $pointHistory->getId()]);
            
        }

        return [
            'form' => $form->createView(),
            'pointHistory' => $pointHistory,
        ];
    }
}
