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

use Customize\Entity\Master\CourseMaster;
use Customize\Form\Type\Admin\Customer\CourseMasterType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Customize\Repository\Master\CourseMasterRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CourseMasterController extends AbstractController
{
    /**
     * @var CourseMasterRepository
     */
    protected $courseMasterRepository;

    /**
     * CourseMasterController constructor.
     *
     * @param CourseMasterRepository $courseMasterRepository
     */
    public function __construct(
        CourseMasterRepository $courseMasterRepository
    )
    {
        $this->courseMasterRepository = $courseMasterRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/customer/courseMaster", name="admin_customer_courseMaster", methods={"GET", "PUT"})
     * @Template("@admin/Customer/CourseMaster/course_master.twig")
     */
    public function index(Request $request)
    {
        $CourseMaster = $this->courseMasterRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'CourseMaster' => $CourseMaster,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'CourseMaster' => $CourseMaster,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/customer/courseMaster/new", name="admin_customer_courseMaster_edit", methods={"GET", "POST"})
     * @Template("@admin/Customer/CourseMaster/course_master_edit.twig")
     */
    public function create(Request $request)
    {
        $courseMaster = new CourseMaster();
        $builder = $this->formFactory
            ->createBuilder(CourseMasterType::class, $courseMaster);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->courseMasterRepository->save($courseMaster);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'courseMaster' => $courseMaster,
                ],
                $request
            );

            $this->entityManager->persist($courseMaster);
            $this->entityManager->flush();

            // var_dump($request->getContent()); 
            // exit;
            

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_customer_courseMaster', ['id' => $courseMaster->getId()]);
        }

        return [
            'form' => $form->createView(),
            'courseMaster' => $courseMaster,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/customer/courseMaster/{id}/edit", requirements={"id" = "\d+"}, name="admin_courseMaster_edit", methods={"GET", "POST"})
     * @Template("@admin/Customer/CourseMaster/course_master_edit.twig")
     */
    public function edit(Request $request, CourseMaster $courseMaster)
    {
        $builder = $this->formFactory
            ->createBuilder(CourseMasterType::class, $courseMaster);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->courseMasterRepository->save($courseMaster);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'courseMaster' => $courseMaster,
                ],
                $request
            );

            $this->entityManager->persist($courseMaster);
            $this->entityManager->flush();

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_customer_courseMaster', ['id' => $courseMaster->getId()]);
        }

        return [
            'form' => $form->createView(),
            'courseMaster' => $courseMaster,
        ];
    }

        /**
     * @Route("/%eccube_admin_route%/customer/courseMaster/{id}/delete", requirements={"id" = "\d+"}, name="admin_courseMaster_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CourseMaster $courseMaster)
    {
        $this->isTokenValid();

        log_info('会員区分削除開始', [$courseMaster->getId()]);

        try {

            $this->courseMasterRepository->delete($courseMaster);
            

            $event = new EventArgs(
                [
                    'courseMaster' => $courseMaster,
                ],
                $request
            );

            $this->entityManager->remove($courseMaster);
            $this->entityManager->flush();

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('会員区分削除完了', [$courseMaster->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('会員区分削除エラー', [$courseMaster->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $courseMaster->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('会員区分削除エラー', [$courseMaster->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_customer_courseMaster');
    }

}
