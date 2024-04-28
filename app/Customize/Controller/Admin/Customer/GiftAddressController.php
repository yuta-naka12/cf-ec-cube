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

use Customize\Form\Type\Admin\GiftAddress\GiftAddressType;
use Customize\Repository\Admin\Customer\CustomerSearchTemplateRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\GiftAddress;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\GiftAddressRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;


class GiftAddressController extends AbstractController
{
    /**
     * @var GiftAddressRepository
     */
    protected $giftAddressRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CustomerSearchTemplateRepository
     */
    protected $customerSearchTemplateRepository;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * GiftAddressController constructor.
     *
     * @param GiftAddressRepository $giftAddressRepository
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        GiftAddressRepository $giftAddressRepository,
        CustomerRepository $customerRepository,
        CustomerSearchTemplateRepository $customerSearchTemplateRepository,
        PageMaxRepository $pageMaxRepository
    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->giftAddressRepository = $giftAddressRepository;
        $this->customerRepository = $customerRepository;
        $this->customerSearchTemplateRepository = $customerSearchTemplateRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/gift_address", name="admin_gift_address_list", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/gift_address/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_gift_address_list_page", methods={"GET", "POST"})
     * @Template("@admin/GiftAddress/index.twig")
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        $GiftAddresses = $this->giftAddressRepository->findAll();

        $builder = $this->formFactory->createBuilder(GiftAddressType::class);

        $form = $builder->getForm();

        $page_count = $this->session->get(
            'eccube.admin.product.search.page_count',
            $this->eccubeConfig->get('eccube_default_page_count')
        );

        $page_count_param = (int) $request->get('page_count');
        $pageMaxis = $this->pageMaxRepository->findAll();

        if ($page_count_param) {
            foreach ($pageMaxis as $pageMax) {
                if ($page_count_param == $pageMax->getName()) {
                    $page_count = $pageMax->getName();
                    $this->session->set('eccube.admin.product.search.page_count', $page_count);
                    break;
                }
            }
        }

        $this->session->set('eccube.admin.product.brand.search', FormUtil::getViewData($form));

        return [
            'form' => $form->createView(),
            'GiftAddresses' => $GiftAddresses,
            'pageMaxis' => $pageMaxis,
            'page_count' => $page_count,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/gift_address/new", name="admin_gift_address_registration", methods={"GET", "POST"})
     * @Template("@admin/GiftAddress/edit.twig")
     */
    public function create(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        $gift_address = new GiftAddress();
        $user = $this->getUser();

        $orderSearchTemplates = $this->customerSearchTemplateRepository->getAll($user);

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

        if ('POST' === $request->getMethod() && $request->request->has('search')) {
            $requestData = $request->request->all();
            if (!empty($requestData['search-template'])) {
                $searchTemplates = $this->customerSearchTemplateRepository->find($requestData['search-template']);
                $templateSearchData = json_decode($searchTemplates['search_contents'], true);
                $unsetKeys = [
                    'admin_search_customer',
                    'search-pattern-name',
                    'search-pattern-type',
                    'search-template',
                    'search-pattern-type',
                ];
                foreach ($unsetKeys as $key) {
                    unset($templateSearchData[$key]);
                }
                $requestData['admin_search_customer'] = $templateSearchData;
                $request->request->replace($requestData);
            }
            $searchForm->handleRequest($request);

            $searchData = $searchForm->getData();

            $page_no = 1;

            $session->set('eccube.admin.customer.search', FormUtil::getViewData($searchForm));

            $session->set('eccube.admin.customer.search.page_no', $page_no);
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
        $qb = $this->customerRepository->getQueryBuilderBySearchData($searchData);

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
        $form = $this->formFactory->createBuilder(GiftAddressType::class, $gift_address, ['customers' => $pagination])->getForm();


        if ('POST' === $request->getMethod() && !($request->request->has('search'))) {
            $form->handleRequest($request);

            $this->giftAddressRepository->save($gift_address);

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_gift_address_list');
        }

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $pageCount,
            'has_errors' => false,
            'orderSearchTemplates' => $orderSearchTemplates,
            'form' => $form->createView(),
        ];
    }


    /**
     * @Route("/%eccube_admin_route%/GiftAddress/{id}/edit", requirements={"id" = "\d+"}, name="admin_gift_address_edit", methods={"GET", "POST"})
     * @Template("@admin/GiftAddress/edit.twig")
     */
    public function edit(Request $request, GiftAddress $gift_address, $page_no = null, PaginatorInterface $paginator)
    {
        $user = $this->getUser();

        $orderSearchTemplates = $this->customerSearchTemplateRepository->getAll($user);

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

        if ('POST' === $request->getMethod() && $request->request->has('search')) {
            $requestData = $request->request->all();
            if (!empty($requestData['search-template'])) {
                $searchTemplates = $this->customerSearchTemplateRepository->find($requestData['search-template']);
                $templateSearchData = json_decode($searchTemplates['search_contents'], true);
                $unsetKeys = [
                    'admin_search_customer',
                    'search-pattern-name',
                    'search-pattern-type',
                    'search-template',
                    'search-pattern-type',
                ];
                foreach ($unsetKeys as $key) {
                    unset($templateSearchData[$key]);
                }
                $requestData['admin_search_customer'] = $templateSearchData;
                $request->request->replace($requestData);
            }
            $searchForm->handleRequest($request);

            $searchData = $searchForm->getData();

            $page_no = 1;

            $session->set('eccube.admin.customer.search', FormUtil::getViewData($searchForm));

            $session->set('eccube.admin.customer.search.page_no', $page_no);
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
        $qb = $this->customerRepository->getQueryBuilderBySearchData($searchData);

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
        $form = $this->formFactory->createBuilder(GiftAddressType::class, $gift_address, ['customers' => $pagination])->getForm();

        if ('POST' === $request->getMethod() && !($request->request->has('search'))) {
            $form->handleRequest($request);

            $this->giftAddressRepository->save($gift_address);

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_gift_address_list');
        }

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $pageCount,
            'has_errors' => false,
            'orderSearchTemplates' => $orderSearchTemplates,
            'form' => $form->createView(),
        ];
    }


    /**
     * @Route("/%eccube_admin_route%/gift_address/{id}/delete", requirements={"id" = "\d+"}, name="admin_gift_address_delete", methods={"DELETE"})
     */
    public function delete(Request $request, GiftAddress $gift_address)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$gift_address->getId()]);

        try {
            $this->giftAddressRepository->delete($gift_address);

            $event = new EventArgs(
                [
                    'gift_address' => $gift_address,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$gift_address->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$gift_address->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => '贈答先']);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$gift_address->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_gift_address_list');
    }
};
