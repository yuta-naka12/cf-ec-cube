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

use Customize\Entity\Master\FinancialInstitution;
use Customize\Form\Type\Admin\Master\FinancialInstitutionType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\FinancialInstitutionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class FinancialInstitutionController extends AbstractController
{
    /**
     * @var FinancialInstitutionRepository
     */
    protected $financialInstitutionRepository;

    /**
     * FinancialInstitutionController constructor.
     *
     * @param FinancialInstitutionRepository $financialInstitutionRepository
     */
    public function __construct(
        FinancialInstitutionRepository $financialInstitutionRepository
    )
    {
        $this->financialInstitutionRepository = $financialInstitutionRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/financial", name="admin_setting_system_master_financial", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/FinancialInstitution/financial.twig")
     */
    public function index(Request $request)
    {
        $FinancialInstitutions = $this->financialInstitutionRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'FinancialInstitutions' => $FinancialInstitutions,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'FinancialInstitutions' => $FinancialInstitutions,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/financial/new", name="admin_setting_system_master_financial_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/FinancialInstitution/financial_edit.twig")
     */
    public function create(Request $request)
    {
        $financialInstitution = new FinancialInstitution();
        $builder = $this->formFactory
            ->createBuilder(FinancialInstitutionType::class, $financialInstitution);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->financialInstitutionRepository->save($financialInstitution);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'financialInstitution' => $financialInstitution,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_financial_edit', ['id' => $financialInstitution->getId()]);
        }

        return [
            'form' => $form->createView(),
            'financialInstitution' => $financialInstitution,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/financial/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_financial_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/FinancialInstitution/financial_edit.twig")
     */
    public function edit(Request $request, FinancialInstitution $financialInstitution)
    {
        $builder = $this->formFactory
            ->createBuilder(FinancialInstitutionType::class, $financialInstitution);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->financialInstitutionRepository->save($financialInstitution);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'financialInstitution' => $financialInstitution,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_financial_edit', ['id' => $financialInstitution->getId()]);
        }

        return [
            'form' => $form->createView(),
            'financialInstitution' => $financialInstitution,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/financial/{id}/up", requirements={"id" = "\d+"}, name="admin_setting_system_master_financial_up", methods={"PUT"})
     */
    public function up(Request $request, FinancialInstitution $financialInstitution)
    {
        $this->isTokenValid();

        try {
            $this->financialInstitutionRepository->up($financialInstitution);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$financialInstitution->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_financial');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/financial/{id}/down", requirements={"id" = "\d+"}, name="admin_setting_system_master_financial_down", methods={"PUT"})
     */
    public function down(Request $request, FinancialInstitution $financialInstitution)
    {
        $this->isTokenValid();

        try {
            $this->financialInstitutionRepository->down($financialInstitution);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$financialInstitution->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_financial');
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/financial/{id}/delete", requirements={"id" = "\d+"}, name="admin_setting_system_master_financial_delete", methods={"DELETE"})
     */
    public function delete(Request $request, FinancialInstitution $financialInstitution)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$financialInstitution->getId()]);

        try {
            $this->financialInstitutionRepository->delete($financialInstitution);

            $event = new EventArgs(
                [
                    'financialInstitution' => $financialInstitution,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$financialInstitution->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$financialInstitution->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $financialInstitution->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$financialInstitution->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_setting_system_master_financial');
    }
}
