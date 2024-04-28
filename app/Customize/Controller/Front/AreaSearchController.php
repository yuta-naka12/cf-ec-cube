<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class AreaSearchController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/areasearch", name="areasearch")
     * @Template("AreaSearch/area_search.twig")
     */
    public function index(Request $request)
    {
        return [];
    }
}
