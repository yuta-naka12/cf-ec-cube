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

namespace Eccube\Controller\Admin\Product;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Tag;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\ProductTag;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\TagRepository;
use Eccube\Service\CsvExportService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Routing\Annotation\Route;

class TagController extends AbstractController
{
    /**
     * @var TagRepository
     */
    protected $tagRepository;
    /**
     * @var CsvExportService
     */
    protected $csvExportService;
    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;

    /**
     * TagController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     *
     */
    public function __construct(
        TagRepository $tagRepository,
        CsvExportService $csvExportService,
        CsvTypeRepository $csvTypeRepository
    )
    {
        $this->tagRepository = $tagRepository;
        $this->csvExportService = $csvExportService;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/tag", name="admin_product_tag", methods={"GET", "POST"})
     * @Template("@admin/Product/tag.twig")
     *
     * @param Request $request
     *
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function index(Request $request)
    {
        $Tag = new Tag();
        $Tags = $this->tagRepository->getList();

        /**
         * 新規登録用フォーム
         **/
        $builder = $this->formFactory
            ->createBuilder(ProductTag::class, $Tag);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Tag' => $Tag,
            ],
            $request
        );

        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_TAG_INDEX_INITIALIZE, $event);

        $form = $builder->getForm();

        /**
         * 編集用フォーム
         */
        $forms = [];
        foreach ($Tags as $EditTag) {
            $id = $EditTag->getId();
            $forms[$id] = $this
                ->formFactory
                ->createNamed('tag_'.$id, ProductTag::class, $EditTag);
        }

        if ('POST' === $request->getMethod()) {
            /*
             * 登録処理
             */
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $this->tagRepository->save($form->getData());

                $this->dispatchComplete($request, $form, $form->getData());

                $this->addSuccess('admin.common.save_complete', 'admin');

                return $this->redirectToRoute('admin_product_tag');
            }
            /*
             * 編集処理
             */
            foreach ($forms as $editForm) {
                $editForm->handleRequest($request);
                if ($editForm->isSubmitted() && $editForm->isValid()) {
                    $this->tagRepository->save($editForm->getData());

                    $this->dispatchComplete($request, $editForm, $editForm->getData());

                    $this->addSuccess('admin.common.save_complete', 'admin');

                    return $this->redirectToRoute('admin_product_tag');
                }
            }
        }

        $formViews = [];
        foreach ($forms as $key => $value) {
            $formViews[$key] = $value->createView();
        }

        return [
            'form' => $form->createView(),
            'Tag' => $Tag,
            'Tags' => $Tags,
            'forms' => $formViews,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/tag/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_tag_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tag $Tag)
    {
        $this->isTokenValid();

        log_info('タグ削除開始', [$Tag->getId()]);

        try {
            $this->tagRepository->delete($Tag);

            $event = new EventArgs(
                [
                    'Tag' => $Tag,
                ], $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_TAG_DELETE_COMPLETE, $event);

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('タグ削除完了', [$Tag->getId()]);
        } catch (\Exception $e) {
            log_info('タグ削除エラー', [$Tag->getId(), $e]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $Tag->getName()]);
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_tag');
    }

    /**
     * @Route("/%eccube_admin_route%/product/tag/sort_no/move", name="admin_product_tag_sort_no_move", methods={"POST"})
     */
    public function moveSortNo(Request $request)
    {
        if ($request->isXmlHttpRequest() && $this->isTokenValid()) {
            $sortNos = $request->request->all();
            foreach ($sortNos as $tagId => $sortNo) {
                /* @var $Tag \Eccube\Entity\Tag */
                $Tag = $this->tagRepository
                    ->find($tagId);
                $Tag->setSortNo($sortNo);
                $this->entityManager->persist($Tag);
            }
            $this->entityManager->flush();
        }

        return new Response();
    }

    protected function dispatchComplete(Request $request, FormInterface $form, Tag $Tag)
    {
        $event = new EventArgs(
            [
                'form' => $form,
                'Tag' => $Tag,
            ],
            $request
        );

        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_TAG_INDEX_COMPLETE, $event);
    }

    /**
     * タグCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/tag/export", name="admin_product_tag_export", methods={"GET"})
     *
     * @param Request $request
     *
     * @return StreamedResponse
     */
    public function export(Request $request)
    {
        // タイムアウトを無効にする.
        set_time_limit(0);

        // sql loggerを無効にする.
        $em = $this->entityManager;
        $em->getConfiguration()->setSQLLogger(null);

        $response = new StreamedResponse();
        $response->setCallback(function () use ($request) {
            // CSV種別を元に初期化.
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_TAG);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->tagRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Tag \Eccube\Entity\Tag */
                $Tag = $entity;

                // CSV出力項目と合致するデータを取得.
                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();
                foreach ($Csvs as $Csv) {
                    $ExportCsvRow->setData($csvService->getData($Csv, $Tag));
                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'tag_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('タグCSV出力ファイル名', [$filename]);

        return $response;
    }
}
