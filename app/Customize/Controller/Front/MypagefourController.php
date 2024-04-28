<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MypagefourController extends AbstractController
{
     /**
     * @Method("GET")
     * @Route("/mypagefour", name="mypagefour")
     * @Template("Mypagefour/mypage_four.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
  