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

use Customize\Entity\Master\Course;
use Customize\Form\Type\Admin\Master\CourseType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\CourseRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CourseController extends AbstractController
{
    /**
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * CourseController constructor.
     *
     * @param CourseRepository $courseRepository
     */
    public function __construct(
        CourseRepository $courseRepository
    )
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/course", name="admin_setting_system_master_course", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/Course/course.twig")
     */
    public function index(Request $request)
    {
        $Courses = $this->courseRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Courses' => $Courses,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'Courses' => $Courses,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/course/new", name="admin_setting_system_master_course_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/Course/course_edit.twig")
     */
    public function create(Request $request)
    {
        $course = new course();
        $builder = $this->formFactory
            ->createBuilder(courseType::class, $course);

        $event = new EventArgs([
            'builder' => $builder,
            'course' => $course,
        ], $request);
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SYSTEM_MEMBER_EDIT_INITIALIZE, $event);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->courseRepository->save($course);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'course' => $course,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SYSTEM_MEMBER_EDIT_COMPLETE, $event);

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_course_edit', ['id' => $course->getId()]);
        }

        return [
            'form' => $form->createView(),
            'course' => $course,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/course/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_course_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/Course/course_edit.twig")
     */
    public function edit(Request $request, course $course)
    {
        $builder = $this->formFactory
            ->createBuilder(CourseType::class, $course);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'course' => $course,
            ],
            $request
        );

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->courseRepository->save($course);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'course' => $course,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_course_edit', ['id' => $course->getId()]);
        }

        return [
            'form' => $form->createView(),
            'course' => $course,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/course/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_system_master_course_up", methods={"PUT"})
     */
    public function up(Request $request, Course $course)
    {
        $this->isTokenValid();

        try {
            $this->courseRepository->up($course);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$course->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_course');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/course/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_system_master_course_down", methods={"PUT"})
     */
    public function down(Request $request, Course $course)
    {
        $this->isTokenValid();

        try {
            $this->courseRepository->down($course);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$course->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_course');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/course/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_system_master_course_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Course $course)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$course->getId()]);

        try {
            $this->courseRepository->delete($course);

            $event = new EventArgs(
                [
                    'course' => $course,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SYSTEM_MEMBER_DELETE_COMPLETE, $event);

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$course->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$course->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $course->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$course->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_course');
    }
}
