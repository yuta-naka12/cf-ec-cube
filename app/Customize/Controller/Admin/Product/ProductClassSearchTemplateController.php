<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Customize\Controller\Admin\Product;

use Customize\Entity\Product\ProductClassSearchTemplate;
use Eccube\Controller\AbstractController;
use Eccube\Form\Type\Admin\SearchProductClassType;
use Eccube\Repository\ProductEventRepository;
use Eccube\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductClassSearchTemplateController extends AbstractController
{

    /**
     *
     */
    public function __construct()
    {
    }

    /**
     * @Route("/%eccube_admin_route%/product/class/search/template", name="admin_product_class_search_template", methods={"POST"})
     */
    public function store(Request $request)
    {
        $builder = $this->formFactory
            ->createBuilder(SearchProductClassType::class);

        $form = $builder->getForm();
        $form->handleRequest($request);
        $searchData = $request->request->all();

        $data = [];
        if ($searchData) {
            foreach ($searchData['admin_search_product'] as $key => $item) {
                // Replace Array key
                $newKey = str_replace('admin_search_product[', '', $key);
                $data[$newKey] = $item;
            }
        }

        // $data to JSON
        $data = json_encode($data);
        $OrderSearchTemplate = new ProductClassSearchTemplate();
        $OrderSearchTemplate->setName($searchData['search-pattern-name']);
        $OrderSearchTemplate->setSearchContents($data);
        if ($searchData['search-pattern-type'] == 'personal') {
            // GET LoginUser
            $user = $this->getUser();
            $OrderSearchTemplate->setMember($user);
        }
        $this->entityManager->persist($OrderSearchTemplate);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_product');
    }
}
