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

namespace Customize\Controller\Admin\CallList;

use Customize\Entity\CallList\CallList;
use Eccube\Repository\Master\BulkBuyingRepository;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Customize\Entity\Contact\Contact;
use Customize\Entity\Master\CallListStatus;
use Customize\Form\Type\Admin\Contact\ContactType;
use Customize\Form\Type\Admin\Master\BulkBuyingType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Eccube\Repository\CallList\CallListGroupRepository;
use Eccube\Repository\CallList\CallListRepository;
use Eccube\Util\FormUtil;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class CallListController extends AbstractController
{
    /**
     * @var CallListGroupRepository
     */
    protected $callListGroupRepository;
    
    /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * @var CallListRepository
     */
    protected $callListRepository;

    /**
     * ContactController constructor.
     *
     * @param CallListGroupRepository $callListGroupRepository
     * @param CallListRepository $callListRepository
     * @param BulkBuyingRepository $BulkBuyingRepository
     */
    public function __construct(
        CallListGroupRepository $callListGroupRepository,
        CallListRepository $callListRepository,
        BulkBuyingRepository $bulkBuyingRepository
    )
    {
        $this->callListGroupRepository = $callListGroupRepository;
        $this->callListRepository = $callListRepository;
        $this->bulkBuyingRepository = $bulkBuyingRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/call-list", name="admin_call_list", methods={"GET", "PUT", "POST"})
     * @Template("@admin/CallList/call_list.twig")
     */
    public function index(Request $request)
    {
        $adminUser = $this->getUser();
        $CallListGroups = $this->callListGroupRepository->getMyCallListGroups($adminUser);
        $CallLists = [];
        if (!empty($CallListGroups)) {
            $CallLists = $CallListGroups[0]['CallLists']->getValues();
        }

        $builder = $this->formFactory->createBuilder();
        $builder->add('name_kana', TextType::class, [
            'required' => false,
            'label' => 'name_kana',
            'attr' => [
                'placeholder' => 'Enter user name'
            ]
        ])

        ->add('tel_number', TextType::class, [
            'required' => false,
            'label' => 'tel',
            'attr' => [
                'placeholder' => 'Enter phone number'
            ]
            ])

        ->add('situation', ChoiceType::class, [
            'required' => false,
            'choices'=>[
                '未処理' => CallListStatus::UNPROCESSED,
                '処理済み' => CallListStatus::PROCESSED,
                '処理済み(休み処理)' => CallListStatus::NON_ATTENDANCE_PROCESSED,
                'キャンセル' => CallListStatus::CANCEL,
                '事務所受け' => CallListStatus::OFFICE_OFFERED,
                
            ],
            'placeholder' => 'シチュエーションを選択してください'
        ])
        
        ->add('delivery_date', ChoiceType::class, [
            'required' => false,
            'choices'=>[
                'Week 1' => 1,
                'Week 2' => 2,
                'Week 3' => 3,
                'Week 4' => 4,
                'Week 5' => 5,
            ],
            'placeholder' => '選択してください'
        ]);

        $searchForm = $builder->getForm();    

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
            $searchData = $searchForm->getData();
            // dd($searchData);
            // 検索条件, ページ番号をセッションに保持.
            $this->session->set('eccube.admin.product.search', FormUtil::getViewData($searchForm));
        } else {
            $viewData = FormUtil::getViewData($searchForm);
            $this->session->set('eccube.admin.customer.search', $viewData);
            $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
        }

         // Retrieve call lists based on search criteria
        $queryBuilder = $this->callListRepository->createQueryBuilder('c');
        $queryBuilder->join('c.Customer', 'cu');

        if (!empty($searchData['delivery_date'])) {
            $queryBuilder->andWhere('cu.delivery_week_1 = :delivery_week_1 OR cu.delivery_week_2 = :delivery_week_2');
            $queryBuilder->setParameter('delivery_week_1', (int) $searchData['delivery_date']);
            $queryBuilder->setParameter('delivery_week_2', (int) $searchData['delivery_date']);
        }

         
        if (!empty($searchData['name_kana'])) {
            $queryBuilder->andWhere('CONCAT(cu.name01, cu.name02) LIKE :userName');
            $queryBuilder->setParameter('userName', '%' . $searchData['name_kana'] . '%');
        }
        if (!empty($searchData['tel_number'])) {
            $queryBuilder->andWhere('cu.phone_number LIKE :phone_number');
            $queryBuilder->setParameter('phone_number', '%' . $searchData['tel_number'] . '%');
        }


        if (!empty($searchData['situation'])) {
            $queryBuilder->andWhere('c.status_id = :status_id');
            $queryBuilder->setParameter('status_id', (int) $searchData['situation']);
        }
        $CallLists = $queryBuilder->getQuery()->getResult();
        //  dd($searchData['name_kana']);

        return [
            'form' => $searchForm->createView(),
            'CallLists' => $CallLists,
            'searchData' => $searchData,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/call-list/{id}/edit", requirements={"id" = "\d+"}, name="admin_call_list_edit", methods={"GET", "POST"})
     * @Template("@admin/Contact/call-list_edit.twig")
     */
    public function edit(Request $request, Contact $contact)
    {

        $builder = $this->formFactory
            ->createBuilder(ContactType::class, $contact);
        $builder = $this->formFactory
            ->createBuilder(BulkBuyingType::class, $contact);
        

        $form = $builder->getForm();
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $this->callListGroupRepository->save($contact);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'contact' => $contact,
                    
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_call_list_edit', ['id' => $contact->getId()]);
        }

        return [
            'form' => $form->createView(),
            'contact' => $contact,
        ];
    }

    /**
     * 欠席
     * @Route("/%eccube_admin_route%/call-list/{id}/non-attendance", name="admin_call_list_non_attendance", methods={"POST"})
     */
    public function nonAttendance(Request $request, CallList $callList)
    {
        try {
            $this->callListRepository->addNonAttendance($callList);
        } catch (\Exception $e) {
            log_error('', [$e]);

            $this->addError('admin.common.update_error', 'admin');
        }

        return $this->redirectToRoute('admin_call_list');
    }

    /**
     * メモ更新
     * @Route("/%eccube_admin_route%/call-list/{id}/time_designation", name="admin_call_list_time_designation", methods={"POST"})
     */
    public function timeDesignationUpdate(Request $request, CallList $callList)
    {
        try {
            $this->callListRepository->updateTimeDesignation($callList, $request);
        } catch (\Exception $e) {
            log_error('', [$e]);

            $this->addError('admin.common.update_error', 'admin');
        }

        return $this->redirectToRoute('admin_call_list');
    }


    /**
     * メモ更新
     * @Route("/%eccube_admin_route%/call-list/{id}/note", name="admin_call_list_note", methods={"POST"})
     */
    public function noteUpdate(Request $request, CallList $callList)
    {
        try {
            $this->callListRepository->updateNote($callList, $request);
        } catch (\Exception $e) {
            log_error('', [$e]);

            $this->addError('admin.common.update_error', 'admin');
        }

        return $this->redirectToRoute('admin_call_list');
    }

    /**
     * キャンセル　
     * @Route("/%eccube_admin_route%/call-list/{id}/cancel", name="admin_call_list_cancel", methods={"POST"})
     */
    public function cancel(Request $request, CallList $callList)
    {
        try {
            $this->callListRepository->cancel($callList);
        } catch (\Exception $e) {
            log_error('', [$e]);
            $this->addError('admin.common.update_error', 'admin');
        }

        return $this->redirectToRoute('admin_call_list');
    }
}
