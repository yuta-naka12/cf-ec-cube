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

namespace Customize\Controller\Admin\Setting;

use Customize\Entity\Setting\PurchaseGroup;
use Customize\Form\Type\Admin\Setting\PurchaseGroupType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\PurchaseGroupRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PurchaseGroupController extends AbstractController
{
    /**
     * @var PurchaseGroupRepository
     */
    protected $purchaseGroupRepository;

    /**
     * PurchaseGroupController constructor.
     *
     * @param PurchaseGroupRepository $purchaseGroupRepository
     */
    public function __construct(
        PurchaseGroupRepository $purchaseGroupRepository
    )
    {
        $this->purchaseGroupRepository = $purchaseGroupRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/pruchase-group", name="admin_setting_purchase_group", methods={"GET", "PUT"})
     * @Template("@admin/Setting/PurchaseGroup/purchase_group.twig")
     */
    public function index(Request $request)
    {
        $PurchaseGroups = $this->purchaseGroupRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'PurchaseGroups' => $PurchaseGroups,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'PurchaseGroups' => $PurchaseGroups,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/pruchase-group/new", name="admin_setting_purchase_group_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/PurchaseGroup/purchase_group_edit.twig")
     */
    public function create(Request $request)
    {
        $purchaseGroup = new PurchaseGroup();
        $builder = $this->formFactory
            ->createBuilder(PurchaseGroupType::class, $purchaseGroup);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->purchaseGroupRepository->save($purchaseGroup);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'purchaseGroup' => $purchaseGroup,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_purchase_group_edit', ['id' => $purchaseGroup->getId()]);
        }

        return [
            'form' => $form->createView(),
            'purchaseGroup' => $purchaseGroup,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/pruchase-group/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_purchase_group_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/PurchaseGroup/purchase_group_edit.twig")
     */
    public function edit(Request $request, PurchaseGroup $purchaseGroup)
    {
        $builder = $this->formFactory
            ->createBuilder(PurchaseGroupType::class, $purchaseGroup);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->purchaseGroupRepository->save($purchaseGroup);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'purchaseGroup' => $purchaseGroup,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_purchase_group_edit', ['id' => $purchaseGroup->getId()]);
        }

        return [
            'form' => $form->createView(),
            'purchaseGroup' => $purchaseGroup,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/pruchase-group/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_purchase_group_up", methods={"PUT"})
     */
    public function up(Request $request, PurchaseGroup $purchaseGroup)
    {
        $this->isTokenValid();

        try {
            $this->purchaseGroupRepository->up($purchaseGroup);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$purchaseGroup->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_purchase_group');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/pruchase-group/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_purchase_group_down", methods={"PUT"})
     */
    public function down(Request $request, PurchaseGroup $purchaseGroup)
    {
        $this->isTokenValid();

        try {
            $this->purchaseGroupRepository->down($purchaseGroup);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$purchaseGroup->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_purchase_group');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/pruchase-group/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_purchase_group_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PurchaseGroup $purchaseGroup)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$purchaseGroup->getId()]);

        try {
            $this->purchaseGroupRepository->delete($purchaseGroup);

            $event = new EventArgs(
                [
                    'purchaseGroup' => $purchaseGroup,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$purchaseGroup->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$purchaseGroup->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $purchaseGroup->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$purchaseGroup->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_purchase_group');
    }
}
