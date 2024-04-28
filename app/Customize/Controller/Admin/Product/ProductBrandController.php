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

use Customize\Entity\Product\ProductBrand;
use Customize\Form\Type\Admin\Product\ProductBrandType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\ExportCsvRow;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Form\Type\Admin\SearchProductBrandType;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\ProductBrandRepository;
use Eccube\Service\CsvExportService;
use Eccube\Util\CacheUtil;
use Eccube\Util\FormUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductBrandController extends AbstractController
{
    /**
     * @var CsvExportService
     */
    protected $csvExportService;
    /**
     * @var CsvTypeRepository
     */
    protected $csvTypeRepository;
    /**
     * @var ProductBrandRepository
     */
    protected $productBrandRepository;
    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * ProductBrandController constructor.
     *
     * @param ProductBrandRepository $productBrandRepository
     * @param PageMaxRepository $pageMaxRepository
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     *
     */
    public function __construct(
        CsvExportService $csvExportService,
        ProductBrandRepository $productBrandRepository,
        PageMaxRepository $pageMaxRepository,
        CsvTypeRepository $csvTypeRepository
    )
    {
        $this->csvExportService = $csvExportService;
        $this->productBrandRepository = $productBrandRepository;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/brand", name="admin_product_brand", methods={"GET", "PUT"})
     * @Route("/%eccube_admin_route%/product/brand/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_product_brand_page", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductBrand/brand.twig")
     */
    public function index(Request $request)
    {
        $ProductBrands = $this->productBrandRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder(ProductBrandType::class);

        $form = $builder->getForm();

        /**
         * ページの表示件数は, 以下の順に優先される.
         * - リクエストパラメータ
         * - セッション
         * - デフォルト値
         * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
         **/
        $page_count = $this->session->get('eccube.admin.product.search.page_count',
            $this->eccubeConfig->get('eccube_default_page_count'));

        $page_count_param = (int) $request->get('page_count');
        $pageMaxis = $this->pageMaxRepository->findAll();

        if ($page_count_param) {
            foreach ($pageMaxis as $pageMax) {
                if ($page_count_param == $pageMax->getName()) {
                    $page_count = $pageMax->getName();
                    $this->session->set('eccube.admin.product.search.page_count', $page_count);
                    break;
                }
            }
        }

        $this->session->set('eccube.admin.product.brand.search',FormUtil::getViewData($form));

        return [
            'form' => $form->createView(),
            'ProductBrands' => $ProductBrands,
            'pageMaxis' => $pageMaxis,
            'page_count' => $page_count,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/brand/new", name="admin_product_brand_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductBrand/brand_edit.twig")
     */
    public function create(Request $request)
    {
        $brand = new ProductBrand();
        $builder = $this->formFactory
            ->createBuilder(ProductBrandType::class, $brand);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productBrandRepository->save($brand);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'brand' => $brand,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_brand_edit', ['id' => $brand->getId()]);
        }

        return [
            'form' => $form->createView(),
            'brand' => $brand,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/brand/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_brand_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductBrand/brand_edit.twig")
     */
    public function edit(Request $request, ProductBrand $brand)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductBrandType::class, $brand);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->productBrandRepository->save($brand);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'brand' => $brand,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_brand_edit', ['id' => $brand->getId()]);
        }

        return [
            'form' => $form->createView(),
            'brand' => $brand,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/brand/{id}/up", requirements={"id" = "\d+"}, name="admin_product_brand_up", methods={"PUT"})
     */
    public function up(Request $request, ProductBrand $brand)
    {
        $this->isTokenValid();

        try {
            $this->productBrandRepository->up($brand);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$brand->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_brand');
    }

    /**
     * @Route("/%eccube_admin_route%/product/brand/{id}/down", requirements={"id" = "\d+"}, name="admin_product_brand_down", methods={"PUT"})
     */
    public function down(Request $request, ProductBrand $brand)
    {
        $this->isTokenValid();

        try {
            $this->productBrandRepository->down($brand);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$brand->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_brand');
    }

    /**
     * @Route("/%eccube_admin_route%/product/brand/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_brand_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductBrand $brand)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$brand->getId()]);

        try {
            $this->productBrandRepository->delete($brand);

            $event = new EventArgs(
                [
                    'brand' => $brand,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$brand->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$brand->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $brand->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$brand->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_brand');
    }

    /**
     * ブランドCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/brand/export", name="admin_product_brand_export", methods={"GET"})
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
            $qb = $this->productBrandRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no','DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);

            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Brand \Customize\Entity\Product\ProductBrand */
                $Brand = $entity;

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
        $filename = 'brand_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('ブランドCSV出力ファイル名', [$filename]);

        return $response;
    }
}
