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

namespace Customize\Controller\Admin\System\Master;

use Customize\Entity\Master\CustomerRank;
use Customize\Form\Type\Admin\Master\CustomerRankType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\CustomerRankRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CustomerRankController extends AbstractController
{
    /**
     * @var CustomerRankRepository
     */
    protected $rankRepository;

    /**
     * CustomerRankController constructor.
     *
     * @param CustomerRankRepository $rankRepository
     */
    public function __construct(
        CustomerRankRepository $rankRepository
    )
    {
        $this->rankRepository = $rankRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/rank", name="admin_setting_system_master_rank", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/CustomerRank/rank.twig")
     */
    public function index(Request $request)
    {
        $CustomerRanks = $this->rankRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'CustomerRanks' => $CustomerRanks,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'CustomerRanks' => $CustomerRanks,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/rank/new", name="admin_setting_system_master_rank_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/CustomerRank/rank_edit.twig")
     */
    public function create(Request $request)
    {
        $rank = new CustomerRank();
        $builder = $this->formFactory
            ->createBuilder(CustomerRankType::class, $rank);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->rankRepository->save($rank);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'rank' => $rank,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_rank_edit', ['id' => $rank->getId()]);
        }

        return [
            'form' => $form->createView(),
            'rank' => $rank,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/rank/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_rank_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/CustomerRank/rank_edit.twig")
     */
    public function edit(Request $request, CustomerRank $rank)
    {
        $builder = $this->formFactory
            ->createBuilder(CustomerRankType::class, $rank);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->rankRepository->save($rank);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'rank' => $rank,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_rank_edit', ['id' => $rank->getId()]);
        }

        return [
            'form' => $form->createView(),
            'rank' => $rank,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/rank/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_system_master_rank_up", methods={"PUT"})
     */
    public function up(Request $request, CustomerRank $rank)
    {
        $this->isTokenValid();

        try {
            $this->rankRepository->up($rank);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$rank->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_rank');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/rank/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_system_master_rank_down", methods={"PUT"})
     */
    public function down(Request $request, CustomerRank $rank)
    {
        $this->isTokenValid();

        try {
            $this->rankRepository->down($rank);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$rank->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_rank');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/rank/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_system_master_rank_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CustomerRank $rank)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$rank->getId()]);

        try {
            $this->rankRepository->delete($rank);

            $event = new EventArgs(
                [
                    'rank' => $rank,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$rank->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$rank->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $rank->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$rank->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_rank');
    }
}
