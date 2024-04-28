<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class BenefitsController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/benefits", name="benefits")
     * @Template("Benefits/benefits.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
