<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Customize\Entity\Product\ProductGenre;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class GenrePageController extends AbstractController
{
    /**
     * @Method("GET")
     * @Route("/genres", name="genres")
     * @Template("genres.twig")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(ProductGenre::class);
        $genres =  $repository->findAll();
        return [
            'genres' => $genres
        ];
    }
}