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
use Customize\Entity\Order\OrderSearchTemplate;
use Customize\Entity\Product\ProductEvent;
use Customize\Form\Type\Admin\Order\InstructionShippingType;
use Customize\Form\Type\Admin\Product\ProductEventType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchOrderType;
use Eccube\Repository\ProductEventRepository;
use Eccube\Repository\OrderRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderSearchTemplateController extends AbstractController
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
     * @Route("/%eccube_admin_route%/order/search/template", name="admin_order_search_template", methods={"POST"})
     */
    public function store(Request $request)
    {
        $builder = $this->formFactory
            ->createBuilder(SearchOrderType::class);

        $form = $builder->getForm();
        $form->handleRequest($request);
        $searchData = $request->request->all();
        $data = [];
        if ($searchData) {
            foreach ($searchData['admin_search_order'] as $key => $item) {
                // Replace Array key
                $newKey = str_replace('admin_search_order[', '', $key);
                $data[$newKey] = $item;
            }
        }

        // $data to JSON
        $data = json_encode($data);
        $OrderSearchTemplate = new OrderSearchTemplate();
        $OrderSearchTemplate->setName($searchData['search-pattern-name']);
        $OrderSearchTemplate->setSearchContents($data);
        if ($searchData['search-pattern-type'] == 'personal') {
            // GET LoginUser
            $user = $this->getUser();
            $OrderSearchTemplate->setMember($user);
        }
        $this->entityManager->persist($OrderSearchTemplate);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_order');
    }
}
