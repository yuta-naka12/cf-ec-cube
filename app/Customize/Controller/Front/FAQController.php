<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class FAQController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/faq", name="faq")
     * @Template("FAQ/faq.twig")
     */
    public function index(Request $request)
    {
        return;
    }

    /**
     * @Method("GET")
     * @Route("/noshi", name="faq_noshi")
     * @Template("FAQ/noshi.twig")
     */
    public function noshi(Request $request)
    {
        return;
    }

    /**
     * @Method("GET")
     * @Route("/hukusuu", name="faq_hukusuu")
     * @Template("FAQ/hukusuu.twig")
     */
    public function hukusu(Request $request)
    {
        return;
    }
}
