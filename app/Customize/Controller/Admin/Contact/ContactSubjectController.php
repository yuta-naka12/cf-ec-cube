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

namespace Customize\Controller\Admin\Contact;

use Customize\Entity\Contact\ContactSubject;
use Customize\Form\Type\Admin\Contact\ContactSubjectType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ContactSubjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContactSubjectController extends AbstractController
{
    /**
     * @var ContactSubjectRepository
     */
    protected $contactSubjectRepository;

    /**
     * ContactSubjectController constructor.
     *
     * @param ContactSubjectRepository $contactSubjectRepository
     */
    public function __construct(
        ContactSubjectRepository $contactSubjectRepository
    )
    {
        $this->contactSubjectRepository = $contactSubjectRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/contact/subject", name="admin_contact_subject", methods={"GET", "PUT"})
     * @Template("@admin/Contact/ContactSubject/subject.twig")
     */
    public function index(Request $request)
    {
        $ContactSubjects = $this->contactSubjectRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ContactSubjects' => $ContactSubjects,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ContactSubjects' => $ContactSubjects,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/contact/subject/new", name="admin_contact_subject_new", methods={"GET", "POST"})
     * @Template("@admin/Contact/ContactSubject/subject_edit.twig")
     */
    public function create(Request $request)
    {
        $subject = new ContactSubject();
        $builder = $this->formFactory
            ->createBuilder(ContactSubjectType::class, $subject);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactSubjectRepository->save($subject);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'subject' => $subject,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_contact_subject_edit', ['id' => $subject->getId()]);
        }

        return [
            'form' => $form->createView(),
            'subject' => $subject,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/contact/subject/{id}/edit", requirements={"id" = "\d+"}, name="admin_contact_subject_edit", methods={"GET", "POST"})
     * @Template("@admin/Contact/ContactSubject/subject_edit.twig")
     */
    public function edit(Request $request, ContactSubject $subject)
    {
        $builder = $this->formFactory
            ->createBuilder(ContactSubjectType::class, $subject);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactSubjectRepository->save($subject);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'subject' => $subject,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_contact_subject_edit', ['id' => $subject->getId()]);
        }

        return [
            'form' => $form->createView(),
            'subject' => $subject,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/contact/subject/{id}/up", requirements={"id" = "\d+"}, name="admin_contact_subject_up", methods={"PUT"})
     */
    public function up(Request $request, ContactSubject $subject)
    {
        $this->isTokenValid();

        try {
            $this->contactSubjectRepository->up($subject);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('件名表示順更新エラー', [$subject->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_contact_subject');
    }

    /**
     * @Route("/%eccube_admin_route%/contact/subject/{id}/down", requirements={"id" = "\d+"}, name="admin_contact_subject_down", methods={"PUT"})
     */
    public function down(Request $request, ContactSubject $subject)
    {
        $this->isTokenValid();

        try {
            $this->contactSubjectRepository->down($subject);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('件名表示順更新エラー', [$subject->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_contact_subject');
    }

    /**
     * @Route("/%eccube_admin_route%/contact/subject/{id}/delete", requirements={"id" = "\d+"}, name="admin_contact_subject_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ContactSubject $subject)
    {
        $this->isTokenValid();

        log_info('件名削除開始', [$subject->getId()]);

        try {
            $this->contactSubjectRepository->delete($subject);

            $event = new EventArgs(
                [
                    'subject' => $subject,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('件名削除完了', [$subject->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('件名削除エラー', [$subject->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $subject->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('件名削除エラー', [$subject->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_contact_subject');
    }
}
