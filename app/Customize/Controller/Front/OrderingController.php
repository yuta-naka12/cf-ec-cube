<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class OrderingController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/ordering", name="ordering")
     * @Template("Ordering/ordering.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
