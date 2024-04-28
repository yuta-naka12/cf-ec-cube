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

use Customize\Entity\Master\DeliveryCalendar;
use Customize\Form\Type\Admin\Master\DeliveryCalendarType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\DeliveryCalendarRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DeliveryCalendarController extends AbstractController
{
    /**
     * @var DeliveryCalendarRepository
     */
    protected $deliveryCalendarRepository;

    /**
     * DeliveryCalendarController constructor.
     *
     * @param DeliveryCalendarRepository $deliveryCalendarRepository
     */
    public function __construct(
        DeliveryCalendarRepository $deliveryCalendarRepository
    )
    {
        $this->deliveryCalendarRepository = $deliveryCalendarRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/delivery_calendar", name="admin_setting_system_master_delivery_calendar", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/DeliveryCalendar/delivery_calendar.twig")
     */
    public function index(Request $request)
    {
        $DeliveryCalendars = $this->deliveryCalendarRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'DeliveryCalendars' => $DeliveryCalendars,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'DeliveryCalendars' => $DeliveryCalendars,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/delivery_calendar/new", name="admin_setting_system_master_delivery_calendar_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/DeliveryCalendar/delivery_calendar_edit.twig")
     */
    public function create(Request $request)
    {
        $delivery_calendar = new DeliveryCalendar();
        $builder = $this->formFactory
            ->createBuilder(DeliveryCalendarType::class, $delivery_calendar);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->deliveryCalendarRepository->save($delivery_calendar);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'delivery_calendar' => $delivery_calendar,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_delivery_calendar_edit', ['id' => $delivery_calendar->getId()]);
        }

        return [
            'form' => $form->createView(),
            'delivery_calendar' => $delivery_calendar,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/delivery_calendar/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_delivery_calendar_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/DeliveryCalendar/delivery_calendar_edit.twig")
     */
    public function edit(Request $request, DeliveryCalendar $delivery_calendar)
    {
        $builder = $this->formFactory
            ->createBuilder(DeliveryCalendarType::class, $delivery_calendar);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->deliveryCalendarRepository->save($delivery_calendar);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'delivery_calendar' => $delivery_calendar,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_delivery_calendar_edit', ['id' => $delivery_calendar->getId()]);
        }

        return [
            'form' => $form->createView(),
            'delivery_calendar' => $delivery_calendar,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/delivery_calendar/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_system_master_delivery_calendar_up", methods={"PUT"})
     */
    public function up(Request $request, DeliveryCalendar $delivery_calendar)
    {
        $this->isTokenValid();

        try {
            $this->deliveryCalendarRepository->up($delivery_calendar);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('まとめ買いグループ表示順更新エラー', [$delivery_calendar->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_delivery_calendar');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/delivery_calendar/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_system_master_delivery_calendar_down", methods={"PUT"})
     */
    public function down(Request $request, DeliveryCalendar $delivery_calendar)
    {
        $this->isTokenValid();

        try {
            $this->deliveryCalendarRepository->down($delivery_calendar);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('まとめ買いグループ表示順更新エラー', [$delivery_calendar->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_delivery_calendar');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/delivery_calendar/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_system_master_delivery_calendar_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DeliveryCalendar $delivery_calendar)
    {
        $this->isTokenValid();

        log_info('まとめ買いグループ削除開始', [$delivery_calendar->getId()]);

        try {
            $this->deliveryCalendarRepository->delete($delivery_calendar);

            $event = new EventArgs(
                [
                    'delivery_calendar' => $delivery_calendar,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('まとめ買いグループ削除完了', [$delivery_calendar->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('まとめ買いグループ削除エラー', [$delivery_calendar->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $delivery_calendar->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('まとめ買いグループ削除エラー', [$delivery_calendar->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_delivery_calendar');
    }
}
