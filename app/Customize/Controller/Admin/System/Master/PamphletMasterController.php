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

use Customize\Entity\Master\Pamphlet;
use Customize\Form\Type\Admin\Master\PamphletType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\PamphletRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PamphletMasterController extends AbstractController
{
    /**
     * @var PamphletRepository
     */
    protected $pamphletMaster;

    /**
     * PamphletMasterController constructor.
     *
     * @param PamphletRepository $pamphletMaster
     */
    public function __construct(
        PamphletRepository $pamphletMaster
    )
    {
        $this->pamphletMaster = $pamphletMaster;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/pamphlet", name="admin_setting_system_master_pamphlet", methods={"GET", "PUT"})
     * @Template("@admin/Setting/System/Master/Pamphlet/pamphlet.twig")
     */
    public function index(Request $request)
    {
        $pamphlets = $this->pamphletMaster->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'BulkBuyings' => $pamphlets,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'Pamphletss' => $pamphlets,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/pamphlet/new", name="admin_setting_system_master_pamphlet_new", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/Pamphlet/pamphlet_edit.twig")
     */
    public function create(Request $request)
    {
        $pamphlet = new Pamphlet();
        $builder = $this->formFactory
            ->createBuilder(PamphletType::class, $pamphlet);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pamphletMaster->save($pamphlet);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'bulk_buying' => $pamphlet,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_setting_system_master_pamphlet_edit', ['id' => $pamphlet->getId()]);
        }

        return [
            'form' => $form->createView(),
            'Pamphlet' => $pamphlet,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/setting/system/master/pamphlet/{id}/edit", requirements={"id" = "\d+"}, name="admin_setting_system_master_pamphlet_edit", methods={"GET", "POST"})
     * @Template("@admin/Setting/System/Master/Pamphlet/pamphlet_edit.twig")
     */
    public function edit(Request $request, Pamphlet $pamphlet)
    {
        $builder = $this->formFactory
            ->createBuilder(PamphletType::class, $pamphlet);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->pamphletMaster->save($pamphlet);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'bulk_buying' => $pamphlet,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_system_master_pamphlet_edit', ['id' => $pamphlet->getId()]);
        }

        return [
            'form' => $form->createView(),
            'bulk_buying' => $pamphlet,
        ];
    }
}