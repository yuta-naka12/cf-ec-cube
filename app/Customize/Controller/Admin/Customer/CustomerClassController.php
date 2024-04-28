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

use Customize\Entity\Customer\CustomerClass;
use Customize\Form\Type\Admin\Customer\CustomerClassType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\CustomerClassRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CustomerClassController extends AbstractController
{
    /**
     * @var CustomerClassRepository
     */
    protected $classRepository;

    /**
     * CustomerClassController constructor.
     *
     * @param CustomerClassRepository $classRepository
     */
    public function __construct(
        CustomerClassRepository $classRepository
    )
    {
        $this->classRepository = $classRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/customer/class", name="admin_customer_class", methods={"GET", "PUT"})
     * @Template("@admin/Customer/CustomerClass/class.twig")
     */
    public function index(Request $request)
    {
        $CustomerClasses = $this->classRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'CustomerClasses' => $CustomerClasses,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'CustomerClasses' => $CustomerClasses,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/customer/class/new", name="admin_customer_class_new", methods={"GET", "POST"})
     * @Template("@admin/Customer/CustomerClass/class_edit.twig")
     */
    public function create(Request $request)
    {
        $class = new CustomerClass();
        $builder = $this->formFactory
            ->createBuilder(CustomerClassType::class, $class);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->classRepository->save($class);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'class' => $class,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_customer_class_edit', ['id' => $class->getId()]);
        }

        return [
            'form' => $form->createView(),
            'class' => $class,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/customer/class/{id}/edit", requirements={"id" = "\d+"}, name="admin_customer_class_edit", methods={"GET", "POST"})
     * @Template("@admin/Customer/CustomerClass/class_edit.twig")
     */
    public function edit(Request $request, CustomerClass $class)
    {
        $builder = $this->formFactory
            ->createBuilder(CustomerClassType::class, $class);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->classRepository->save($class);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'class' => $class,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_customer_class_edit', ['id' => $class->getId()]);
        }

        return [
            'form' => $form->createView(),
            'class' => $class,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/customer/class/{id}/up", requirements={"id" = "\d+"}, name="admin_customer_class_up", methods={"PUT"})
     */
    public function up(Request $request, CustomerClass $class)
    {
        $this->isTokenValid();

        try {
            $this->classRepository->up($class);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('会員区分表示順更新エラー', [$class->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_customer_class');
    }

    /**
     * @Route("/%eccube_admin_route%/customer/class/{id}/down", requirements={"id" = "\d+"}, name="admin_customer_class_down", methods={"PUT"})
     */
    public function down(Request $request, CustomerClass $class)
    {
        $this->isTokenValid();

        try {
            $this->classRepository->down($class);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('会員区分表示順更新エラー', [$class->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_customer_class');
    }

    /**
     * @Route("/%eccube_admin_route%/customer/class/{id}/delete", requirements={"id" = "\d+"}, name="admin_customer_class_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CustomerClass $class)
    {
        $this->isTokenValid();

        log_info('会員区分削除開始', [$class->getId()]);

        try {
            $this->classRepository->delete($class);

            $event = new EventArgs(
                [
                    'class' => $class,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('会員区分削除完了', [$class->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('会員区分削除エラー', [$class->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $class->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('会員区分削除エラー', [$class->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_customer_class');
    }
}
