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

namespace Customize\Controller\Admin\CallList;

use Doctrine\Common\Collections\ArrayCollection;
use Eccube\Form\Type\Admin\OrderType;
use Eccube\Event\EccubeEvents;
use Eccube\Entity\Order;
use Eccube\Entity\Shipping;
use Customize\Entity\Order\Sender;
use Eccube\Service\OrderHelper;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Service\PurchaseFlow\Processor\OrderNoProcessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseException;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Eccube\Repository\SenderRepository;
use Eccube\Repository\CustomerRepository;
use Customize\Form\Type\Admin\Master\BulkBuyingType;
use Eccube\Repository\Master\BulkBuyingRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Customize\Entity\Master\BulkBuying;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Form\Type\Admin\SearchSenderType;
use Eccube\Repository\DeliveryRepository;
use Customize\Entity\CallList\CallList;
use Customize\Entity\Contact\Contact;
use Customize\Form\Type\Admin\Contact\ContactType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchProductType;
use Eccube\Repository\CallList\CallListGroupRepository;
use Eccube\Repository\CallList\CallListRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class CallListOrderController extends AbstractController
{
    /**
     * @var CallListGroupRepository
     */
    protected $callListGroupRepository;

    /**
     * @var PurchaseFlow
     */
    protected $purchaseFlow;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

     /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * @var CallListRepository
     */
    protected $callListRepository;

    /**
     * @var OrderRepository
     */
    protected $orderRepository;

    /**
     * @var OrderNoProcessor
     */
    protected $orderNoProcessor;

    /**
     * @var DeliveryRepository
     */
    protected $deliveryRepository;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var OrderHelper
     */
    private $orderHelper;

    /**
     * @var SenderRepository
     */
    protected $senderRepository;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * ContactController constructor.
     *
     * @param CallListGroupRepository $callListGroupRepository
     * @param CallListRepository $callListRepository
     * @param ProductRepository $productRepository
     * @param DeliveryRepository $deliveryRepository
     * @param SerializerInterface $serializer
     * @param SenderRepository $senderRepository
     * @param CustomerRepository $customerRepository
     * @param OrderRepository $orderRepository
     * @param OrderNoProcessor $orderNoProcessor
     * @param BulkBuyingRepository $BulkBuyingRepository
     * @param PurchaseFlow $orderPurchaseFlow

     * @param OrderHelper $orderHelper

     */
    public function __construct(
        CallListGroupRepository $callListGroupRepository,
        CallListRepository $callListRepository,
        ProductRepository $productRepository,
        DeliveryRepository $deliveryRepository,
        SerializerInterface $serializer,
        OrderRepository $orderRepository,
        CustomerRepository $customerRepository,
        OrderNoProcessor $orderNoProcessor,
        BulkBuyingRepository $bulkBuyingRepository,
        PurchaseFlow $orderPurchaseFlow,
        SenderRepository $senderRepository,
        OrderHelper $orderHelper
    )
    {
        $this->callListGroupRepository = $callListGroupRepository;
        $this->callListRepository = $callListRepository;
        $this->productRepository = $productRepository;
        $this->serializer = $serializer;
        $this->purchaseFlow = $orderPurchaseFlow;
        $this->customerRepository = $customerRepository;
        $this->orderNoProcessor = $orderNoProcessor;
        $this->deliveryRepository = $deliveryRepository;
        $this->senderRepository = $senderRepository;
        $this->bulkBuyingRepository = $bulkBuyingRepository;
        $this->orderRepository = $orderRepository;
        $this->orderHelper = $orderHelper;
    }

    /**
     * @Route("/%eccube_admin_route%/call-list/{id}/order", name="admin_call_list_order", methods={"GET", "POST"})
     * @Template("@admin/CallList/call_list_order.twig")
     */
    public function index(Request $request, $id)
    {
        // $bulkBuying = $this->bulkBuyingRepository->find(4);

        // $products = $bulkBuying->getProducts();

        // foreach ($products as $product) {
        //     dd($product);
        // }
        $CallList = $this->callListRepository->find($id);
        $Customer = $CallList->getCustomer();
        $BulkBuyings = $this->bulkBuyingRepository->findAll();
        $qb = $this->orderRepository->getQueryBuilderByCustomer($Customer);
        $Orders = $qb->getQuery()->getResult();

        $builder = $this->formFactory->createBuilder(SearchProductType::class);
        $searchProductModalForm = $builder->getForm();

        $builder = $this->formFactory->createBuilder();

        $form = $builder->getForm();

        $bulkEntityFormBuilder = $this->formFactory->createBuilder();

        $bulkEntityFormBuilder->add('bulk_name', ChoiceType::class, [
            'choices' => $BulkBuyings,
            'choice_label' => function (BulkBuying $bulkBuying) {
                return $bulkBuying->getName();
            },
            'choice_attr' => function($choice, $key, $value) {
                $products = $choice->getProducts();
                if (is_array($products)) {
                    $products = array_map(function($product) {
                        return $product->getId();
                    }, $products);
                } else {
                    $products = $products->getId();
                }
                return [
                    'quantity' => $choice->getQuantity(),
                    'discount_rate' => $choice->getDiscountRate(),
                    'products' => json_encode($products)
                ];
            },
            'expanded' => true,
            'required' => false
        ]);
        
        
        
        $bulkEntityForm = $bulkEntityFormBuilder->getForm();

        return [
            'form' => $form->createView(),
            'CallList' => $CallList,
            'Orders' => $Orders,
            'searchProductModalForm' => $searchProductModalForm->createView(),
            'bulkEntityForm' => $bulkEntityForm->createView(),
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/call-list/{id}/order/comfirm", name="admin_call_list_order_confirm", methods={"POST"})
     * @Template("@admin/CallList/confirm.twig")
      */
    public function confirm(Request $request, $id)
    {
        $CallList = $this->callListRepository->find($id);
       
    
        $x=$request->request->all();
        return [
            'CallList' => $CallList,
            'Request' => $request->request->all(),
            'OrderForm' => $x["order-form"],
            'order-total-price-input' => $x["order-total-price-input"],
            'use-point' => $x["use-point"],
            'order-tax-input' => $x["order-tax-input"],
            'order-total-price-input' => $x["order-total-price-input"],
            'data' => json_encode($request->request->all()),
        ];
    }


    /**
     * @Route("/%eccube_admin_route%/call-list/{id}/order/complete", name="admin_call_list_order_complete", methods={"POST", "GET"  })
     * @Template("@admin/CallList/confirm.twig")
     */
    public function complete(Request $request, $id, RouterInterface $router)
    {
        
        $CallList = $this->callListRepository->find($id);
        $CallList->setStatusId(2); 
        $this->callListRepository->save($CallList);

        $this->entityManager->persist($CallList);
        $this->entityManager->flush();

        $customer = $this->customerRepository->find($id);

        $x = json_decode($request->request->all()["request"]);
    

        $TargetOrder = null;
        $OriginOrder = null;
      
        $TargetOrder = new Order();
        $TargetOrder->addShipping((new Shipping())->setOrder($TargetOrder));

        $TargetOrder->setSender((new Sender())->addOrder($TargetOrder));

        $preOrderId = $this->orderHelper->createPreOrderId();

        $TargetOrder->setPreOrderId($preOrderId);

        // 編集前の受注情報を保持
        $OriginOrder = clone $TargetOrder;
        $OriginItems = new ArrayCollection();
        foreach ($TargetOrder->getOrderItems() as $Item) {
            $OriginItems->add($Item);
        }

        $builder = $this->formFactory->createBuilder(OrderType::class, $TargetOrder);
        
        $event = new EventArgs(
            [
                'builder' => $builder,
                'OriginOrder' => $OriginOrder,
                'TargetOrder' => $TargetOrder,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_EDIT_INDEX_INITIALIZE, $event);

        $form = $builder->getForm();
        
        // OrderItemsを設定
        if ($x->{"order-form"} !== null) {
            $orders = (array) $x->{"order-form"};
            
            $orderItems = [];
            foreach ($orders as $key => $order) {
                if (!empty($order->code && $order->amount)) {
                    $orderItems[] = [
                        "product_name" => $order->name,
                        "ProductClass" => $order->class,
                        "order_item_type" => OrderItemType::PRODUCT,
                        "price" => $order->price,
                        "quantity" => $order->amount,
                        "tax_type" => "",
                    ];
                }
            }
            $data = $request->request->all();
            $data['order']['OrderItems'] = $orderItems;
            $request->request->replace($data);
        }
        $form->handleRequest($request);

        $purchaseContext = new PurchaseContext($OriginOrder, $customer);  

        
        
        if ($form->isSubmitted() && $form['OrderItems']->isValid()) {
            $event = new EventArgs(
                [
                    'builder' => $builder,
                    'OriginOrder' => $OriginOrder,
                    'TargetOrder' => $TargetOrder,
                    'PurchaseContext' => $purchaseContext,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_ORDER_EDIT_INDEX_PROGRESS, $event);

            $flowResult = $this->purchaseFlow->validate($TargetOrder, $purchaseContext);
     
            if ($flowResult->hasWarning()) {
                foreach ($flowResult->getWarning() as $warning) {
                    $this->addWarning($warning->getMessage(), 'admin');
                }
            }

            if ($flowResult->hasError()) {
                foreach ($flowResult->getErrors() as $error) {
                    $this->addError($error->getMessage(), 'admin');
                }
            }

            $this->purchaseFlow->prepare($TargetOrder, $purchaseContext);
            $this->purchaseFlow->commit($TargetOrder, $purchaseContext);

       
            
            $Sender = $this->senderRepository->find(strval($CallList->getCustomer()->getId()));
            $TargetOrder->setSender($Sender);

          
            $TargetOrder->setCustomer($customer);
            $TargetOrder->setPref($customer["Pref"]);
            $TargetOrder->setSex($customer["Sex"]);
            $TargetOrder->setJob($customer["Job"]);
            $TargetOrder->setCountry($customer["Country"]);
            $TargetOrder->setName01($customer["name01"]);
            $TargetOrder->setName02($customer["name02"]);
            $TargetOrder->setKana01($customer["kana01"]);
            $TargetOrder->setKana02($customer["kana02"]);
            $TargetOrder->setUsePoint($customer["point"]);
            $TargetOrder->setOrderComment($x->{"order-comment"});
            $TargetOrder->setShippingComment($x->{"delivery-comment"});
            $TargetOrder->setMessageForThisTime($x->{"rumour"});

            $shippings = $TargetOrder->getShippings();
            $firstShipping = $shippings->first();
            $firstShipping->setName01($customer["name01"]);
            $firstShipping->setName02($customer["name02"]);
            $firstShipping->setKana01($customer["kana01"]);
            $firstShipping->setKana02($customer["kana02"]);
       


            $this->entityManager->persist($TargetOrder);
            $this->entityManager->flush();

            

            $this->orderNoProcessor->process($TargetOrder, $purchaseContext);
            $this->entityManager->flush();
            
        }
        return $this->redirectToRoute('admin_call_list');
    }
    
}