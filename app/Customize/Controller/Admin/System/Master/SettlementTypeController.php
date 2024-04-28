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

use Customize\Entity\Master\SettlementType;
use Customize\Form\Type\Admin\Master\SettlementTypeType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\SettlementTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SettlementTypeController extends AbstractController
{
    /**
     * @var SettlementTypeRepository
     */
    protected $settlementTypeRepository;

    /**
     * SettlementTypeController constructor.
     *
     * @param SettlementTypeRepository $settlementTypeRepository
     */
    public function __construct(
        SettlementTypeRepository $settlementTypeRepository
    )
    {
        $this->settlementTypeRepository = $settlementTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/settlement_type", name="admin_setting_system_master_settlement_type", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/SettlementType/settlement_type.twig")
     */
    public function index(Request $request)
    {
        $SettlementTypes = $this->settlementTypeRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'SettlementTypes' => $SettlementTypes,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'SettlementTypes' => $SettlementTypes,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/settlement_type/new", name="admin_setting_system_master_settlement_type_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/SettlementType/settlement_type_edit.twig")
     */
    public function create(Request $request)
    {
        $settlement_type = new SettlementType();
        $builder = $this->formFactory
            ->createBuilder(SettlementTypeType::class, $settlement_type);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->settlementTypeRepository->save($settlement_type);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'settlement_type' => $settlement_type,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_settlement_type_edit', ['id' => $settlement_type->getId()]);
        }

        return [
            'form' => $form->createView(),
            'settlement_type' => $settlement_type,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/settlement_type/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_settlement_type_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/SettlementType/settlement_type_edit.twig")
     */
    public function edit(Request $request, SettlementType $settlement_type)
    {
        $builder = $this->formFactory
            ->createBuilder(SettlementTypeType::class, $settlement_type);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->settlementTypeRepository->save($settlement_type);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'settlement_type' => $settlement_type,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_settlement_type_edit', ['id' => $settlement_type->getId()]);
        }

        return [
            'form' => $form->createView(),
            'settlement_type' => $settlement_type,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/settlement_type/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_system_master_settlement_type_up", methods={"PUT"})
     */
    public function up(Request $request, SettlementType $settlement_type)
    {
        $this->isTokenValid();

        try {
            $this->settlementTypeRepository->up($settlement_type);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('決済種別表示順更新エラー', [$settlement_type->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_settlement_type');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/settlement_type/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_system_master_settlement_type_down", methods={"PUT"})
     */
    public function down(Request $request, SettlementType $settlement_type)
    {
        $this->isTokenValid();

        try {
            $this->settlementTypeRepository->down($settlement_type);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('決済種別表示順更新エラー', [$settlement_type->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_settlement_type');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/settlement_type/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_system_master_settlement_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SettlementType $settlement_type)
    {
        $this->isTokenValid();

        log_info('決済種別削除開始', [$settlement_type->getId()]);

        try {
            $this->settlementTypeRepository->delete($settlement_type);

            $event = new EventArgs(
                [
                    'settlement_type' => $settlement_type,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('決済種別削除完了', [$settlement_type->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('決済種別削除エラー', [$settlement_type->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $settlement_type->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('決済種別削除エラー', [$settlement_type->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_settlement_type');
    }
}
