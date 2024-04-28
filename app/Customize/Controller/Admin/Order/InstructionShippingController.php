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

namespace Customize\Controller\Admin\Order;

use Customize\Entity\Order\InstructionShipping;
use Customize\Entity\Product\ProductEvent;
use Customize\Form\Type\Admin\Order\InstructionShippingType;
use Customize\Form\Type\Admin\Product\ProductEventType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\ProductEventRepository;
use Eccube\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class InstructionShippingController extends AbstractController
{
    /**
     * @var ProductEventRepository
     */
    protected $instructionShippingRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * ProductEventController constructor.
     *
     * @param ProductEventRepository $instructionShippingRepository
     * @param OrderRepository $orderRepository
     */
    public function __construct(
        ProductEventRepository $instructionShippingRepository,
        OrderRepository $orderRepository
    )
    {
        $this->instructionShippingRepository = $instructionShippingRepository;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/order/instruction-shipping", name="admin_order_instruction_shipping", methods={"GET", "POST"})
     * @Template("@admin/Order/InstructionShipping/instruction_shipping.twig")
     */
    public function index(Request $request)
    {
        $instructionShipping = new InstructionShipping();
        $builder = $this->formFactory
            ->createBuilder(InstructionShippingType::class, $instructionShipping);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $request->request->all();
            $instructionShipping = $data["instruction_shipping"];
            $deliveryDate = $instructionShipping['delivery_date'];
            $qb = $this->orderRepository->createQueryBuilder('p');
            $qb->where('p.order_date <= :date')
                ->setParameter('order_date', $deliveryDate['year'])
                ->getQuery();

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_order_instruction_shipping');
        }
        return [
            'form' => $form->createView(),
            'event' => $instructionShipping,
        ];
    }
}
