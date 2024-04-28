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

namespace Eccube\Controller\Admin\Setting\Shop;

use Customize\Entity\Setting\CsvOutputTemplate;
use Customize\Repository\Admin\Setting\CsvOutputTemplateItemRepository;
use Customize\Repository\Admin\Setting\CsvOutputTemplateRepository;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\CsvRepository;
use Eccube\Repository\Master\CsvTypeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CsvController
 */
class CsvController extends AbstractController
{
    /**
     * @var CsvRepository
     */
    protected $csvRepository;

    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;

    /**
     * @var CsvOutputTemplateRepository
     */
    protected $csvOutputTemplateRepository;

    /**
     * @var CsvOutputTemplateRepository
     */
    protected $csvOutputTemplateItemRepository;

    /**
     * CsvController constructor.
     *
     * @param CsvRepository $csvRepository
     * @param CsvTypeRepository $csvTypeRepository
     */
    public function __construct(
        CsvRepository $csvRepository,
        CsvTypeRepository $csvTypeRepository,
        CsvOutputTemplateRepository $csvOutputTemplateRepository,
        CsvOutputTemplateItemRepository $csvOutputTemplateItemRepository
    ) {
        $this->csvRepository = $csvRepository;
        $this->csvTypeRepository = $csvTypeRepository;
        $this->csvOutputTemplateRepository = $csvOutputTemplateRepository;
        $this->csvOutputTemplateItemRepository = $csvOutputTemplateItemRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/setting/shop/csv/{id}",
     *     requirements={"id" = "\d+"},
     *     defaults={"id" = CsvType::CSV_TYPE_ORDER},
     *     name="admin_setting_shop_csv",
     *     methods={"GET", "POST"}
     * )
     * @Template("@admin/Setting/Shop/csv.twig")
     */
    public function index(Request $request, CsvType $CsvType)
    {
        $builder = $this->createFormBuilder();

        $builder->add(
            'csv_type',
            \Eccube\Form\Type\Master\CsvType::class,
            [
                'label' => 'admin.setting.shop.csv.csv_columns',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'data' => $CsvType,
            ]
        );

        // テンプレートを指定している場合
        if (!empty($request->get('template-id'))) {
            $template = $this->csvOutputTemplateRepository->findBy(
                ['CsvType' => $CsvType, 'id' => $request->get('template-id')],
            );
            if (!empty($template)) {
                $targetCsvOutputTemplate = $template[0];
                $items = $this->csvOutputTemplateItemRepository->findBy(
                    ['CsvOutputTemplate' => $targetCsvOutputTemplate],
                );
                if (!empty($items)) {
                    foreach ($items as $item) {
                        $targetCsv = $this->csvRepository->find($item->getCsvId());
                        $targetCsv->setSortNo($item->getSortNo());
                        $targetCsv->setEnabled($item->getEnabled());
                        $this->entityManager->persist($targetCsv);
                        $this->entityManager->flush();
                    }
                }
            }
        }

        // Get Customer search templates
        $user = $this->getUser();
        $OutputTemplates = $this->csvOutputTemplateRepository->getAll($user);

        $builder->add(
            'csv_output_templates',
            EntityType::class,
            [
                'class' => CsvOutputTemplate::class,
                'choice_label' => 'title',
                'required' => false,
                'expanded' => false,
                'choices' => $OutputTemplates,
                'placeholder' => '選択して下さい',
                'attr' => ['id' => 'csv-output-templates'],
            ]
        );

        $CsvNotOutput = $this->csvRepository->findBy(
            ['CsvType' => $CsvType, 'enabled' => false],
            ['sort_no' => 'ASC']
        );

        $builder->add(
            'csv_not_output',
            EntityType::class,
            [
                'class' => 'Eccube\Entity\Csv',
                'choice_label' => 'disp_name',
                'required' => false,
                'expanded' => false,
                'multiple' => true,
                'choices' => $CsvNotOutput,
            ]
        );

        $CsvOutput = $this->csvRepository->findBy(
            ['CsvType' => $CsvType, 'enabled' => true],
            ['sort_no' => 'ASC']
        );

        $builder->add(
            'csv_output',
            EntityType::class,
            [
                'class' => 'Eccube\Entity\Csv',
                'choice_label' => 'disp_name',
                'required' => false,
                'expanded' => false,
                'multiple' => true,
                'choices' => $CsvOutput,
            ]
        );

        $CsvTemplate = $this->csvRepository->findBy(
            ['CsvType' => $CsvType],
            ['sort_no' => 'ASC']
        );

        $event = new EventArgs(
            [
                'builder' => $builder,
                'CsvOutput' => $CsvOutput,
                'CsvType' => $CsvType,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SHOP_CSV_INDEX_INITIALIZE, $event);

        $form = $builder->getForm();

        // csv_output/csv_not_outputのチェックに引っかかるため, tokenチェックは個別に行う
        if ('POST' === $request->getMethod() && $this->isTokenValid()) {
            $data = $request->get('form');
            if (isset($data['csv_not_output'])) {
                $Csvs = $data['csv_not_output'];
                $sortNo = 1;
                foreach ($Csvs as $csv) {
                    $c = $this->csvRepository->find($csv);
                    $c->setSortNo($sortNo);
                    $c->setEnabled(false);
                    $sortNo++;
                }
            }

            if (isset($data['csv_output'])) {
                $Csvs = $data['csv_output'];
                $sortNo = 1;
                foreach ($Csvs as $csv) {
                    $c = $this->csvRepository->find($csv);
                    $c->setSortNo($sortNo);
                    $c->setEnabled(true);
                    $sortNo++;
                }
            }

            $this->entityManager->flush();

            $event = new EventArgs(
                [
                    'form' => $form,
                    'CsvOutput' => $CsvOutput,
                    'CsvType' => $CsvType,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_SETTING_SHOP_CSV_INDEX_COMPLETE, $event);

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_shop_csv', ['id' => $CsvType->getId()]);
        }

        return [
            'form' => $form->createView(),
            'id' => $CsvType->getId(),
        ];
    }
}