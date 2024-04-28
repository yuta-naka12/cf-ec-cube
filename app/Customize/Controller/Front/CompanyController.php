<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class CompanyController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/company", name="company")
     * @Template("Company/company.twig")
     */
    public function index(Request $request)
    {
        return;
    }
}
