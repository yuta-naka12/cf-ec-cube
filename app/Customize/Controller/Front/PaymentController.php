<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PaymentController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/payment", name="payment")
     * @Template("Payment/payment.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
