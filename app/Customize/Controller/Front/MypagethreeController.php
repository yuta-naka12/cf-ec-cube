<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class MypagethreeController extends AbstractController
{
     /**
     * @Method("GET")
     * @Route("/mypagethree", name="mypagethree")
     * @Template("Mypagethree/mypage_three.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
  