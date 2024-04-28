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

use Customize\Entity\Product\ProductTopic;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Customize\Repository\Admin\Product\ProductTopicRepository;
use Customize\Form\Type\Admin\Product\ProductTopicType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ProductTopicController extends AbstractController
{
    /**
     * @var ProductTopicRepository
     */
    protected $productTopicRepository;

    /**
     * ProductTopicController constructor.
     *
     * @param ProductTopicRepository $productTopicRepository
     */
    public function __construct(
        ProductTopicRepository $productTopicRepository
    ) {
        $this->productTopicRepository = $productTopicRepository; 
    }

    /**
     * @Route("/%eccube_admin_route%/product/topic", name="admin_product_topic", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductTopic/topic.twig")
     */
    public function index(Request $request)
    {
        $ProductTopics = $this->productTopicRepository->findBy([], ['sort_no' => 'DESC']);;
    //    dd($ProductTopics);
        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductTopics' => $ProductTopics,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductTopics' => $ProductTopics
        ];
    }

        /**
     * @Route("/%eccube_admin_route%/product/topic/new", name="admin_product_topic_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductTopic/topic_edit.twig")
     */
    public function create(Request $request)
    {
        $topic = new ProductTopic();
        $builder = $this->formFactory
            ->createBuilder(ProductTopicType::class, $topic);

        $form = $builder->getForm();
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->productTopicRepository->save($topic);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'topic' => $topic,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_topic_edit', ['id' => $topic->getId()]);
        }

        return [
            'form' => $form->createView(),
            'topic' => $topic,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/topic/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_topic_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductTopic/topic_edit.twig")
     */
    public function edit(Request $request, ProductTopic $topic)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductTopicType::class, $topic);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productTopicRepository->save($topic);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'topic' => $topic,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_topic_edit', ['id' => $topic->getId()]);
        }

        return [
            'form' => $form->createView(),
            'topic' => $topic,
        ];
    }

        /**
     * @Route("/%eccube_admin_route%/product/topic/{id}/up", requirements={"id" = "\d+"}, name="admin_product_topic_up", methods={"PUT"})
     */
    public function up(Request $request, ProductTopic $topic)
    {
        $this->isTokenValid();

        try {
            $this->productTopicRepository->up($topic);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$topic->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_topic');
    }

    /**
     * @Route("/%eccube_admin_route%/product/topic/{id}/down", requirements={"id" = "\d+"}, name="admin_product_topic_down", methods={"PUT"})
     */
    public function down(Request $request, ProductTopic $topic)
    {
        $this->isTokenValid();

        try {
            $this->productTopicRepository->down($topic);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$topic->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_topic');
    }

       /**
     * @Route("/%eccube_admin_route%/product/topic/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_topic_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductTopic $topic)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$topic->getId()]);

        try {
            $this->productTopicRepository->delete($topic);

            $event = new EventArgs(
                [
                    'topic' => $topic,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$topic->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$topic->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $topic->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$topic->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_topic');
    }


        /**
     * ブランドCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/topic/export", name="admin_product_topic_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_BRAND);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            // ブランドデータ検索用のクエリビルダを取得.
            $qb = $this->productTopicRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no','DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);

            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Topic \Customize\Entity\Product\ProductTopic */
                $Topic = $entity;

                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();

                // CSV出力項目と合致するデータを取得.
                foreach ($Csvs as $Csv) {
                    // ブランドデータを検索.
                    $ExportCsvRow->setData($csvService->getData($Csv, $Brand));

                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'topic_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('ブランドCSV出力ファイル名', [$filename]);

        return $response;
    }

}