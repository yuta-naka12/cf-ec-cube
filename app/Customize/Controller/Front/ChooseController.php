<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class ChooseController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/choose", name="choose")
     * @Template("Choose/choose.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}