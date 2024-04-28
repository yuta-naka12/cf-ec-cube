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

use Customize\Entity\Master\BulkBuying;
use Customize\Form\Type\Admin\Master\BulkBuyingType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\BulkBuyingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class BulkBuyingController extends AbstractController
{
    /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * BulkBuyingController constructor.
     *
     * @param BulkBuyingRepository $bulkBuyingRepository
     */
    public function __construct(
        BulkBuyingRepository $bulkBuyingRepository
    )
    {
        $this->bulkBuyingRepository = $bulkBuyingRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/bulk_buying", name="admin_setting_system_master_bulk_buying", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/BulkBuying/bulk_buying.twig")
     */
    public function index(Request $request)
    {
        $BulkBuyings = $this->bulkBuyingRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'BulkBuyings' => $BulkBuyings,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'BulkBuyings' => $BulkBuyings,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/bulk_buying/new", name="admin_setting_system_master_bulk_buying_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/BulkBuying/bulk_buying_edit.twig")
     */
    public function create(Request $request)
    {
        $bulk_buying = new BulkBuying();
        $builder = $this->formFactory
            ->createBuilder(BulkBuyingType::class, $bulk_buying);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bulkBuyingRepository->save($bulk_buying);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'bulk_buying' => $bulk_buying,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_bulk_buying_edit', ['id' => $bulk_buying->getId()]);
        }

        return [
            'form' => $form->createView(),
            'bulk_buying' => $bulk_buying,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/bulk_buying/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_bulk_buying_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/BulkBuying/bulk_buying_edit.twig")
     */
    public function edit(Request $request, BulkBuying $bulk_buying)
    {
        $builder = $this->formFactory
            ->createBuilder(BulkBuyingType::class, $bulk_buying);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->bulkBuyingRepository->save($bulk_buying);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'bulk_buying' => $bulk_buying,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_bulk_buying_edit', ['id' => $bulk_buying->getId()]);
        }

        return [
            'form' => $form->createView(),
            'bulk_buying' => $bulk_buying,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/bulk_buying/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_system_master_bulk_buying_up", methods={"PUT"})
     */
    public function up(Request $request, BulkBuying $bulk_buying)
    {
        $this->isTokenValid();

        try {
            $this->bulkBuyingRepository->up($bulk_buying);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('まとめ買いグループ表示順更新エラー', [$bulk_buying->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_bulk_buying');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/bulk_buying/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_system_master_bulk_buying_down", methods={"PUT"})
     */
    public function down(Request $request, BulkBuying $bulk_buying)
    {
        $this->isTokenValid();

        try {
            $this->bulkBuyingRepository->down($bulk_buying);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('まとめ買いグループ表示順更新エラー', [$bulk_buying->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_bulk_buying');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/bulk_buying/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_system_master_bulk_buying_delete", methods={"DELETE"})
     */
    public function delete(Request $request, BulkBuying $bulk_buying)
    {
        $this->isTokenValid();

        log_info('まとめ買いグループ削除開始', [$bulk_buying->getId()]);

        try {
            $this->bulkBuyingRepository->delete($bulk_buying);

            $event = new EventArgs(
                [
                    'bulk_buying' => $bulk_buying,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('まとめ買いグループ削除完了', [$bulk_buying->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('まとめ買いグループ削除エラー', [$bulk_buying->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $bulk_buying->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('まとめ買いグループ削除エラー', [$bulk_buying->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_bulk_buying');
    }
}
