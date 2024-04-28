<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Eccube\Entity\Category;

class SearchController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/search", name="search")
     * @Template("Search/search.twig")
     */
    public function index(Request $request)
    {
        $categoryRepo = $this->getDoctrine()->getRepository(Category::class);
        $categories = $categoryRepo->findAll();;

        return[
            'categories' => $categories
        ];
    }

}





  
