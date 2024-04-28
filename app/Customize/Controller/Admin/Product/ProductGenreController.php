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

use Customize\Entity\Product\ProductGenre;
use Customize\Entity\Product\ProductGenreDisplayMode;
use Customize\Form\Type\Admin\Product\ProductGenreType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\ExportCsvRow;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\ProductGenreRepository;
use Eccube\Repository\ProductGenreDisplayModeRepository;
use Eccube\Service\CsvExportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductGenreController extends AbstractController
{
    /**
     * @var ProductGenreRepository
     */
    protected $productGenreRepository;
    /**
     * @var ProductGenreDisplayModeRepository
     */
    protected $productGenreDisplayModeRepository;
    /**
     * @var CsvExportService
     */
    protected $csvExportService;
    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;

    /**
     * ProductGenreController constructor.
     *
     * @param ProductGenreRepository $productGenreRepository
     * @param ProductGenreDisplayModeRepository $productGenreDisplayModeRepository
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     */
    public function __construct(
        ProductGenreRepository $productGenreRepository,
        ProductGenreDisplayModeRepository $productGenreDisplayModeRepository,
        CsvExportService $csvExportService,
        CsvTypeRepository $csvTypeRepository
    )
    {
        $this->productGenreRepository = $productGenreRepository;
        $this->productGenreDisplayModeRepository = $productGenreDisplayModeRepository;
        $this->csvExportService = $csvExportService;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/genre", name="admin_product_genre", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductGenre/genre.twig")
     */
    public function index(Request $request)
    {
        $ProductGenres = $this->productGenreRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $genre = new EventArgs(
            [
                'builder' => $builder,
                'ProductGenres' => $ProductGenres,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductGenres' => $ProductGenres,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/genre/new", name="admin_product_genre_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductGenre/genre_edit.twig")
     */
    public function create(Request $request)
    {
        $productGenre = new ProductGenre();

        $builder = $this->formFactory
        ->createBuilder(ProductGenreType::class, $productGenre);

        $form = $builder->getForm();
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {

            // 商品表示モード登録処理
            $genre = $form->getData();
            // dd($genre);
            $requestDisplayModes = $form->get('ProductGenreDisplayMode')->getData();
       
            foreach ($requestDisplayModes as $requestDisplayMode) {
                $displayMode = $this->productGenreDisplayModeRepository->find($requestDisplayMode);
                $genre->addProductGenreDisplayMode($displayMode);
                $displayMode->addProductGenre($genre);
                $this->entityManager->persist($displayMode);
                $this->entityManager->persist($genre);
            }
            

            $this->productGenreRepository->save($productGenre);

            $genre = new EventArgs(
                [
                    'form' => $form,
                    'genre' => $productGenre,
                ],
                $request
            );
            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_genre_edit', ['id' => $productGenre->getId()]);
        }
        return [
            'form' => $form->createView(),
            'genre' => $productGenre,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/genre/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_genre_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductGenre/genre_edit.twig")
     */
    public function edit(Request $request, ProductGenre $genre)
    {
        $hasDisplayModes = $genre->getProductGenreDisplayMode();
        $builder = $this->formFactory
            ->createBuilder(ProductGenreType::class, $genre);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // 商品表示モード編集処
            // 一度全て削除
            foreach ($hasDisplayModes as $hasDisplayMode) {
                $displayMode = $this->productGenreDisplayModeRepository->find($hasDisplayMode);
                $genre->removeProductGenreDisplayMode($hasDisplayMode);
                $displayMode->removeProductGenre($genre);
                $this->entityManager->remove($genre);
                $this->entityManager->flush();
            }

            $requestDisplayModes = $form->get('ProductGenreDisplayMode')->getData();

            foreach ($requestDisplayModes as $requestDisplayMode) {
                $displayMode = $this->productGenreDisplayModeRepository->find($requestDisplayMode);
                $genre->addProductGenreDisplayMode($displayMode);
                $displayMode->addProductGenre($genre);
                $this->entityManager->persist($displayMode);
                $this->entityManager->persist($genre);
            }

            $this->productGenreRepository->save($genre);

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_genre_edit', ['id' => $genre->getId()]);

        } else {
            $vals = array();
            foreach ($hasDisplayModes as $hasDisplayMode) {
                $vals[] = (string)$hasDisplayMode->getId();
            }
            if (!empty($vals)) {
                $form->get('ProductGenreDisplayMode')->setData($vals);
            }
        }

        return [
            'form' => $form->createView(),
            'genre' => $genre,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/genre/{id}/up", requirements={"id" = "\d+"}, name="admin_product_genre_up", methods={"PUT"})
     *     */
    public function up(Request $request, ProductGenre $genre)
    {
        $this->isTokenValid();

        try {
            $this->productGenreRepository->up($genre);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$genre->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_genre');
    }

    /**
     * @Route("/%eccube_admin_route%/product/genre/{id}/down", requirements={"id" = "\d+"}, name="admin_product_genre_down", methods={"PUT"})
     */
    public function down(Request $request, ProductGenre $genre)
    {
        $this->isTokenValid();

        try {
            $this->productGenreRepository->down($genre);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$genre->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_genre');
    }

    /**
     * @Route("/%eccube_admin_route%/product/genre/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_genre_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductGenre $genre)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$genre->getId()]);

        try {
            $this->productGenreRepository->delete($genre);

            $event = new EventArgs(
                [
                    'genre' => $genre,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$genre->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$genre->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $genre->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$genre->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_genre');
    }

    /**
     * イベントCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/genre/export", name="admin_product_genre_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_GENRE);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->productGenreRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');
                // データ行の出力.
                $this->csvExportService->setExportQueryBuilder($qb);
                $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                    
                $hasDisplayModes = $entity->getProductGenreDisplayMode();

                $displayModeText = '';
                foreach ($hasDisplayModes as $hasDisplayMode) {
                    $displayModeText .= $hasDisplayMode->getName() . '、';
                }
                $Csvs = $csvService->getCsvs();
                /** @var $Genre \Customize\Entity\Product\ProductGenre */
                $Genre = $entity;

                // CSV出力項目と合致するデータを取得.
                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();
                foreach ($Csvs as $Csv) {
                    if ($Csv['disp_name'] === '商品表示モード') {
                        $ExportCsvRow->setData($displayModeText);
                    } else {
                        $ExportCsvRow->setData($csvService->getData($Csv, $Genre));
                    }
                    $ExportCsvRow->pushData();
                }

                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'genre_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('イベントCSV出力ファイル名', [$filename]);

        return $response;
    }
}
