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

namespace Customize\Controller\Admin\WithdrawReason;

use Customize\Entity\Master\WithdrawalReason;
use Customize\Form\Type\Admin\WithdrawalReason\WithdrawReasonType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\WithdrawalReasonRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class WithdrawReasonController extends AbstractController
{
    /**
     * @var WithdrawalReasonRepository
     */
    protected $withdrawalReasonRepository;

    /**
     * WithdrawReasonController constructor.
     *
     * @param WithdrawalReasonRepository $withdrawalReasonRepository
     */
    public function __construct(
        WithdrawalReasonRepository $withdrawalReasonRepository
    )
    {
        $this->withdrawalReasonRepository = $withdrawalReasonRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/withdraw_reason", name="admin_withdraw_reason_item", methods={"GET", "PUT"})
     * @Template("@admin/WithdrawReason/index.twig")
     */
    public function index(Request $request)
    {
        $WithdrawalReasons = $this->withdrawalReasonRepository->findBy([], ['sort_no' => 'DESC']);

        // dd($WithdrawalReasons);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'WithdrawalReasons' => $WithdrawalReasons,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'WithdrawalReasons' => $WithdrawalReasons,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/withdraw_reason/new", name="admin_withdraw_reason_item_new", methods={"GET", "POST"})
     * @Template("@admin/WithdrawReason/extension_item_edit.twig")
     */
    public function create(Request $request)
    {
        $extension_item = new WithdrawalReason();
        $builder = $this->formFactory
            ->createBuilder(WithdrawReasonType::class, $extension_item);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->withdrawalReasonRepository->save($extension_item);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'extension_item' => $extension_item,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_withdraw_reason_item', ['id' => $extension_item->getId()]);
        }

        return [
            'form' => $form->createView(),
            'extension_item' => $extension_item,
        ];
    }


}
