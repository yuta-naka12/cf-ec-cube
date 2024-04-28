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

use Customize\Entity\Contact\Contact;
use Customize\Form\Type\Admin\Contact\ContactType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ContactRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class ContactController extends AbstractController
{
    /**
     * @var ContactRepository
     */
    protected $contactRepository;

    /**
     * ContactController constructor.
     *
     * @param ContactRepository $contactRepository
     */
    public function __construct(
        ContactRepository $contactRepository
    )
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/contact", name="admin_contact", methods={"GET", "PUT"})
     * @Template("@admin/Contact/contact.twig")
     */
    public function index(Request $request)
    {
        $Contacts = $this->contactRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Contacts' => $Contacts,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'Contacts' => $Contacts,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/contact/new", name="admin_contact_new", methods={"GET", "POST"})
     * @Template("@admin/Contact/contact_edit.twig")
     */
    public function create(Request $request)
    {
        $contact = new Contact();
        $builder = $this->formFactory
            ->createBuilder(ContactType::class, $contact);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactRepository->save($contact);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'contact' => $contact,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_contact_edit', ['id' => $contact->getId()]);
        }

        return [
            'form' => $form->createView(),
            'contact' => $contact,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/contact/{id}/edit", requirements={"id" = "\d+"}, name="admin_contact_edit", methods={"GET", "POST"})
     * @Template("@admin/Contact/contact_edit.twig")
     */
    public function edit(Request $request, Contact $contact)
    {
        $builder = $this->formFactory
            ->createBuilder(ContactType::class, $contact);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->contactRepository->save($contact);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'contact' => $contact,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_contact_edit', ['id' => $contact->getId()]);
        }

        return [
            'form' => $form->createView(),
            'contact' => $contact,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/contact/{id}/up", requirements={"id" = "\d+"}, name="admin_contact_up", methods={"PUT"})
     */
    public function up(Request $request, Contact $contact)
    {
        $this->isTokenValid();

        try {
            $this->contactRepository->up($contact);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('件名表示順更新エラー', [$contact->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_contact');
    }

    /**
     * @Route("/%eccube_admin_route%/contact/{id}/down", requirements={"id" = "\d+"}, name="admin_contact_down", methods={"PUT"})
     */
    public function down(Request $request, Contact $contact)
    {
        $this->isTokenValid();

        try {
            $this->contactRepository->down($contact);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('件名表示順更新エラー', [$contact->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_contact');
    }

    /**
     * @Route("/%eccube_admin_route%/contact/{id}/delete", requirements={"id" = "\d+"}, name="admin_contact_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Contact $contact)
    {
        $this->isTokenValid();

        log_info('件名削除開始', [$contact->getId()]);

        try {
            $this->contactRepository->delete($contact);

            $event = new EventArgs(
                [
                    'contact' => $contact,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('件名削除完了', [$contact->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('件名削除エラー', [$contact->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $contact->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('件名削除エラー', [$contact->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_contact');
    }
}
