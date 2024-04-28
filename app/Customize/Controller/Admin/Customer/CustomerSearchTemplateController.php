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

namespace Customize\Controller\Admin\Customer;

use Customize\Entity\Order\InstructionShipping;
use Customize\Entity\Customer\CustomerSearchTemplate;
use Customize\Entity\Product\ProductEvent;
use Customize\Form\Type\Admin\Order\InstructionShippingType;
use Customize\Form\Type\Admin\Product\ProductEventType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Repository\ProductEventRepository;
use Eccube\Repository\CustomerRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerSearchTemplateController extends AbstractController
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * ProductEventController constructor.
     * @param ProductEventRepository $instructionShippingRepository

     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        ProductEventRepository $instructionShippingRepository,

        CustomerRepository $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;

    }

    /**
     * @Route("/%eccube_admin_route%/customer/search/template", name="admin_customer_search_template", methods={"POST"})
     */
    public function store(Request $request)
    {
        $builder = $this->formFactory
            ->createBuilder(SearchCustomerType::class);

        $form = $builder->getForm();
        $form->handleRequest($request);
        $searchData = $request->request->all();

        $data = [];
        if ($searchData) {
            foreach ($searchData['admin_search_customer'] as $key => $item) {
                // Replace Array key
                $newKey = str_replace('admin_search_customer[', '', $key);
                $data[$newKey] = $item;
            }
        }

        // $data to JSON
        $data = json_encode($data);
        $OrderSearchTemplate = new CustomerSearchTemplate();
        $OrderSearchTemplate->setName($searchData['search-pattern-name']);
        $OrderSearchTemplate->setSearchContents($data);
        if ($searchData['search-pattern-type'] == 'personal') {
            // GET LoginUser
            $user = $this->getUser();
            $OrderSearchTemplate->setMember($user);
        }
        $this->entityManager->persist($OrderSearchTemplate);
        $this->entityManager->flush();

        return $this->redirectToRoute('admin_customer');
    }
}
