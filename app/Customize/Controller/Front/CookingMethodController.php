<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CookingMethodController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/cooking-method", name="cooking-method")
     * @Template("CookingMethod/cooking_method.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
