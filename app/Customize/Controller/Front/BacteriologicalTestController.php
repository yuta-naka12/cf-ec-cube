<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class BacteriologicalTestController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/bacteriological-test", name="bacteriological-test")
     * @Template("BacteriologicalTest/bacteriological_test.twig")
     */
    public function index(Request $request)
    {
        return [];
    }
}
