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

namespace Customize\Controller\Admin\Setting;

use Customize\Entity\Customer\CustomerSearchTemplate;
use Customize\Entity\Setting\CsvOutputTemplate;
use Customize\Entity\Setting\CsvOutputTemplateItem;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Repository\CsvRepository;
use Eccube\Repository\ProductEventRepository;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\CsvTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Security\Core\User\UserInterface;

class CsvOutputTemplateController extends AbstractController
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var CsvRepository
     */
    protected $csvRepository;

    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;

    /**
     * ProductEventController constructor.
     * @param ProductEventRepository $instructionShippingRepository
     *
     * @param CustomerRepository $customerRepository
     */
    public function __construct(
        ProductEventRepository $instructionShippingRepository,
        CustomerRepository $customerRepository,
        CsvRepository $csvRepository,
        CsvTypeRepository $csvTypeRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->csvRepository = $csvRepository;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/csv-output-template", name="admin_csv_output_template", methods={"POST"})
     */
    public function store(Request $request)
    {
        $requestData = $request->request->all();

        // テンプレートの保存処理
        $CsvTemplate = new CsvOutputTemplate();
        $CsvTemplate->setTitle($requestData['template-name']);
        $CsvType = $this->csvTypeRepository->find($requestData['csv-type-id']);
        $CsvTemplate->setCsvType($CsvType);
        if ($requestData['template-type'] == 'personal') {
            // GET LoginUser
            $user = $this->getUser();
            $CsvTemplate->setMember($user);
        }
        $this->entityManager->persist($CsvTemplate);
        $this->entityManager->flush();

        //
        $nonOutputs = $requestData['csv-output'];
        foreach ($nonOutputs as $item) {
            $csv = $this->csvRepository->find($item);
            $OutputItem = new CsvOutputTemplateItem();
            $OutputItem->setEntityName($csv->getEntityName());
            $OutputItem->setFieldName($csv->getFieldName());
            $OutputItem->setReferenceFieldName($csv->getReferenceFieldName());
            $OutputItem->setDispName($csv->getDispName());
            $OutputItem->setSortNo($csv->getSortNo());
            $OutputItem->setEnabled($csv->isEnabled());
            $OutputItem->setCsvId($csv->getId());
            $OutputItem->setCsvOutputTemplate($CsvTemplate);
            $this->entityManager->persist($OutputItem);
            $this->entityManager->flush();
        }

        $outputs = $requestData['csv-not-output'];
        foreach ($outputs as $item) {
            $csv = $this->csvRepository->find($item);
            $OutputItem = new CsvOutputTemplateItem();
            $OutputItem->setEntityName($csv->getEntityName());
            $OutputItem->setFieldName($csv->getFieldName());
            $OutputItem->setReferenceFieldName($csv->getReferenceFieldName());
            $OutputItem->setDispName($csv->getDispName());
            $OutputItem->setSortNo($csv->getSortNo());
            $OutputItem->setEnabled($csv->isEnabled());
            $OutputItem->setCsvId($csv->getId());
            $OutputItem->setCsvOutputTemplate($CsvTemplate);
            $this->entityManager->persist($OutputItem);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('admin_customer');
    }
}
