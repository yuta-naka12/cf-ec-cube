<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PrivacypolicyController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/privacy-policy", name="privacy-policy")
     * @Template("Privacypolicy/privacy_policy.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}