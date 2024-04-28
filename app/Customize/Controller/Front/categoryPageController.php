<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class categoryPageController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/category", name="category")
     * @Template("category.twig")
     */
    public function testMethod()
    {
        return [];
    }
}
