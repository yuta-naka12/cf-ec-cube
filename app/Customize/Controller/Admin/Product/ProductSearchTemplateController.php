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

use Customize\Entity\Order\InstructionShipping;
use Customize\Entity\Product\ProductSearchTemplate;
use Customize\Entity\Product\ProductEvent;
use Customize\Form\Type\Admin\Order\InstructionShippingType;
use Customize\Form\Type\Admin\Product\ProductEventType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchProductType;
use Eccube\Repository\ProductEventRepository;
use Eccube\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;

class ProductSearchTemplateController extends AbstractController
{
    /**
     * @var ProductEventRepository
     */
    protected $instructionShippingRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductEventController constructor.
     *
     * @param ProductEventRepository $instructionShippingRepository
     * @param ProductRepository $productRepository
     */
    public function __construct(
        ProductEventRepository $instructionShippingRepository,
        ProductRepository $productRepository
    )
    {
        $this->productRepository = $productRepository;

    }

    /**
     * @Route("/%eccube_admin_route%/product/search/template", name="admin_product_search_template", methods={"POST"})
     */
    public function store(Request $request)
    {
        $builder = $this->formFactory
            ->createBuilder(SearchProductType::class);

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
        $OrderSearchTemplate = new ProductSearchTemplate();
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
