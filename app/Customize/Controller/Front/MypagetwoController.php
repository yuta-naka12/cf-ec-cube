<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MypagetwoController extends AbstractController
{
     /**
     * @Method("GET")
     * @Route("/mypagetwo", name="mypagetwo")
     * @Template("Mypagetwo/mypage_two.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
  