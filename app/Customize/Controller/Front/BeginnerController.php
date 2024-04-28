<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class BeginnerController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/beginner", name="beginner")
     * @Template("Beginner/begginer.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
