<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class NetFormController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/net-form", name="net-form")
     * @Template("NetForm/net_form.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
  