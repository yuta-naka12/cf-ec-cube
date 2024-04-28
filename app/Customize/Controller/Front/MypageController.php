<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MypageController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/mypage-otoiawaserireki", name="mypage")
     * @Template("Mypage/mypage.twig")
     */
    public function index(Request $request)
    {
        return;
    }

}
  