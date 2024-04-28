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

use Customize\Entity\Master\BulkBuying;
use Customize\Entity\Product\ProductSupplier;
use Customize\Repository\Admin\Product\ProductClassSearchTemplateRepository;
use Customize\Repository\Admin\Product\ProductSearchTemplateRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\EntityManager;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Category;
use Eccube\Entity\ExportCsvRow;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
use Eccube\Entity\ProductImage;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Form\Type\Admin\ProductClassType;
use Eccube\Form\Type\Admin\SearchProductClassType;
use Eccube\Form\Type\Admin\SearchProductType;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductImageRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Service\CsvExportService;
use Eccube\Util\CacheUtil;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ProductClassController extends AbstractController
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
     * @var ProductClassRepository
     */
    protected $productClassRepository;
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var ProductSearchTemplateRepository
     */
    protected $productSearchTemplateRepository;

    /**
     * @var ProductClassSearchTemplateRepository
     */
    protected $productClassSearchTemplateRepository;

    /**
     * @var ProductImageRepository
     */
    protected $productImageRepository;

    /**
     * ProductClassController constructor.
     *
     * @param ProductClassRepository $productClassRepository
     * @param ProductRepository $productRepository
     * @param PageMaxRepository $pageMaxRepository
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     * @param ProductRepository $productRepository
     * @param ProductSearchTemplateRepository $productSearchTemplateRepository
     * @param ProductClassSearchTemplateRepository $productClassSearchTemplateRepository
     * @param ProductImageRepository $productImageRepository
     *
     */
    public function __construct(
        CsvExportService $csvExportService,
        ProductClassRepository $productClassRepository,
        ProductRepository $productRepository,
        PageMaxRepository $pageMaxRepository,
        CsvTypeRepository $csvTypeRepository,
        ProductSearchTemplateRepository $productSearchTemplateRepository,
        ProductClassSearchTemplateRepository $productClassSearchTemplateRepository,
        ProductImageRepository $productImageRepository
    ) {
        $this->csvExportService = $csvExportService;
        $this->productClassRepository = $productClassRepository;
        $this->productRepository = $productRepository;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->csvTypeRepository = $csvTypeRepository;
        $this->productRepository = $productRepository;
        $this->productSearchTemplateRepository = $productSearchTemplateRepository;
        $this->productClassSearchTemplateRepository = $productClassSearchTemplateRepository;
        $this->productImageRepository = $productImageRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/class", name="admin_product_class", methods={"GET", "PUT","POST"})
     * @Route("/%eccube_admin_route%/product/class/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_product_class_page", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductClass/class.twig")
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        $ProductClasss = $this->productClassRepository->findBy([], ['id' => 'DESC']);
        // Productを取得
        $user = $this->getUser();
        $orderSearchTemplates = $this->productClassSearchTemplateRepository->getAll($user);

        $builder = $this->formFactory->createBuilder(SearchProductClassType::class);

        $searchForm = $builder->getForm();

        // ===========================
        /**
         * ページの表示件数は, 以下の順に優先される.
         * - リクエストパラメータ
         * - セッション
         * - デフォルト値
         * また, セッションに保存する際は mtb_page_maxと照合し, 一致した場合のみ保存する.
         **/
        $page_count = $this->session->get(
            'eccube.admin.product.search.page_count',
            $this->eccubeConfig->get('eccube_default_page_count')
        );

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
        // ===========================

        if ('POST' === $request->getMethod()) {
            $requestData = $request->request->all();
            if (!empty($requestData['search-template'])) {
                $searchTemplates = $this->productClassSearchTemplateRepository->find($requestData['search-template']);
                $templateSearchData = json_decode($searchTemplates['search_contents'], true);
                $unsetKeys = [
                    'admin_search_product',
                    'search-pattern-name',
                    'search-pattern-type',
                    'search-template',
                    'search-pattern-type',
                ];
                foreach ($unsetKeys as $key) {
                    unset($templateSearchData[$key]);
                }
                $requestData['admin_search_product'] = $templateSearchData;
                $request->request->replace($requestData);
            }

            $searchForm->handleRequest($request);

            if ($searchForm->isValid()) {
                /**
                 * 検索が実行された場合は, セッションに検索条件を保存する.
                 * ページ番号は最初のページ番号に初期化する.
                 */
                $page_no = 1;
                $searchData = $searchForm->getData();

                // 検索条件, ページ番号をセッションに保持.
                $this->session->set('eccube.admin.product.search', FormUtil::getViewData($searchForm));
                $this->session->set('eccube.admin.product.search.page_no', $page_no);
            } else {
                // 検索エラーの際は, 詳細検索枠を開いてエラー表示する.
                return [
                    'searchForm' => $searchForm->createView(),
                    'pagination' => [],
                    'pageMaxis' => $pageMaxis,
                    'orderSearchTemplates' => $orderSearchTemplates,
                    'page_no' => $page_no,
                    'page_count' => $page_count,
                    'has_errors' => true,
                ];
            }
        } else {
            if (null !== $page_no || $request->get('resume')) {
                /*
                 * ページ送りの場合または、他画面から戻ってきた場合は, セッションから検索条件を復旧する.
                 */
                if ($page_no) {
                    // ページ送りで遷移した場合.
                    $this->session->set('eccube.admin.product.search.page_no', (int) $page_no);
                } else {
                    // 他画面から遷移した場合.
                    $page_no = $this->session->get('eccube.admin.product.search.page_no', 1);
                }
                $viewData = $this->session->get('eccube.admin.product.search', []);
                $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
            } else {
                /**
                 * 初期表示の場合.
                 */
                $page_no = 1;
                $viewData = [];
                // submit default value
                if ($statusId = (int) $request->get('order_status_id')) {
                    $viewData = ['status' => [$statusId]];
                }
                $searchData = FormUtil::submitAndGetData($searchForm, $viewData);

                // セッション中の検索条件, ページ番号を初期化.
                $this->session->set('eccube.admin.product.search', $viewData);
                $this->session->set('eccube.admin.product.search.page_no', $page_no);
            }
        }

        $addFields = [
            'code',
            'name',
            'Category',
            'ProductSupplier',
            'BulkBuying',
            'DeliveryCalculation',
            'SubscriptionPurchase',
            'price_start',
            'price_end',
            'discount_period_price_start',
            'discount_period_price_end',
            'purchase_limit_start',
            'purchase_limit_end',
            'purchase_minimum_start',
            'purchase_minimum_end',
            'ProductIcon',
            'NewProductCategory',
            'IntroduceGoods',
            'create_date_start',
            'create_date_end',
            'update_date_start',
            'update_date_end',
            'stock_start',
            'stock_end',
            'campaign_config'
        ];

        foreach ($addFields as $field) {
            $searchData[$field] = $searchForm->get($field)->getData();
        }

        $qb = $this->productClassRepository->getQueryBuilderBySearchDataForAdmin($searchData);

        $sortKey = $searchData['sortkey'];

        if (empty($this->productRepository::COLUMNS[$sortKey]) || $sortKey == 'code' || $sortKey == 'status') {
            $pagination = $paginator->paginate(
                $qb,
                $page_no,
                $page_count
            );
        } else {
            $pagination = $paginator->paginate(
                $qb,
                $page_no,
                $page_count,
                ['wrap-queries' => true]
            );
        }

        // $this->session->set('eccube.admin.product.brand.search', FormUtil::getViewData($form));

        return [
            'searchForm' => $searchForm->createView(),
            'ProductClasses' => $ProductClasss,
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $page_count,
            'has_errors' => false,
            'orderSearchTemplates' => $orderSearchTemplates,
            // 'OrderStatuses' => $this->productStatusRepository->findAll();
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/class/new", name="admin_product_class_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductClass/class_edit.twig")
     */
    public function create(Request $request)
    {
        $class = new ProductClass();
        $builder = $this->formFactory
            ->createBuilder(ProductClassType::class, $class);

        $form = $builder->getForm();
        $form->handleRequest($request);

        $builder = $this->formFactory->createBuilder(SearchProductClassType::class);
        $searchForm = $builder->getForm();
        $searchForm->handleRequest($request);

        $results = null;
        if ($searchForm->isSubmitted()) {
            $searchData = $searchForm->getData();
            $qb = $this->productClassRepository->getQueryBuilderBySearchIdForAdmin($searchData);
            $results = $qb->getQuery()->getResult();
            if (count($results) === 0) {
                $results = 'admin.common.search_no_result';
            }
        }
        $product = '';
        if (!empty($form->get('Product')->getData())) {
            $product = $form->get('Product')->getData();
        }


        if ($form->isSubmitted() && $form->isValid()) {
            //紹介品区分
            $IntroduceGoods = $form->get('IntroduceGoods')->getData();
            $class->setIntroduceGood($IntroduceGoods);

            // 新商品区分
            $newProductCategory = $form->get('NewProductCategory')->getData();
            $class->setNewProductCategory($newProductCategory);

            // 定期購入区分
            $subscriptionPurchase = $form->get('SubscriptionPurchase')->getData();
            $class->setSubscriptionPurchase($subscriptionPurchase);

            //まとめ買いグループ
            $bulkBuying = $form->get('BulkBuying')->getData();
            $class->setBulkBuying($bulkBuying);

            // 商品登録
            $product = $form->get('Product')->getData();
            $class->setProduct($product);

            // アイコン1
            $icon_1 = $form->get('ProductIcon1')->getData();
            $class->setProductIcon1($icon_1);

            // アイコン2
            $icon_2 = $form->get('ProductIcon2')->getData();
            $class->setProductIcon2($icon_2);

            // アイコン3
            $icon_3 = $form->get('ProductIcon3')->getData();
            $class->setProductIcon3($icon_3);

            // パンフレット登録処理
            $this->productClassRepository->save($class);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'brand' => $class,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_class_edit', ['id' => $class->getId()]);
        }

        return [
            'searchForm' => $searchForm->createView(),
            'form' => $form->createView(),
            'Class' => $class,
            'Product' => $product,
            'results' => $results,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/class/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_class_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductClass/class_edit.twig")
     */
    public function edit(Request $request, ProductClass $class)
    {
        $builder = $this->formFactory
            ->createBuilder(ProductClassType::class, $class);

        $form = $builder->getForm();
        $form->handleRequest($request);

        $Product = $class->getProduct();

        $builder = $this->formFactory->createBuilder(SearchProductClassType::class);
        $searchForm = $builder->getForm();
        $searchForm->handleRequest($request);

        $results = null;
        if ($searchForm->isSubmitted()) {
            $searchData = $searchForm->getData();
            $qb = $this->productClassRepository->getQueryBuilderBySearchIdForAdmin($searchData);
            $results = $qb->getQuery()->getResult();
            if (count($results) === 0) {
                $results = 'admin.common.search_no_result';
            }
        }

        if ($form->isSubmitted() && $form->isValid()) {
            //紹介品区分
            $IntroduceGoods = $form->get('IntroduceGoods')->getData();
            $class->setIntroduceGood($IntroduceGoods);

            // 新商品区分
            $newProductCategory = $form->get('NewProductCategory')->getData();
            $class->setNewProductCategory($newProductCategory);

            // 定期購入区分
            $subscriptionPurchase = $form->get('SubscriptionPurchase')->getData();
            $class->setSubscriptionPurchase($subscriptionPurchase);

            //まとめ買いグループ
            $bulkBuying = $form->get('BulkBuying')->getData();
            $class->setBulkBuying($bulkBuying);

            // 商品登録
            $product = $form->get('Product')->getData();
            $class->setProduct($product);

            // アイコン1
            $icon_1 = $form->get('ProductIcon1')->getData();
            $class->setProductIcon1($icon_1);

            // アイコン2
            $icon_2 = $form->get('ProductIcon2')->getData();
            $class->setProductIcon2($icon_2);

            // アイコン3
            $icon_3 = $form->get('ProductIcon3')->getData();
            $class->setProductIcon3($icon_3);

            // パンフレット登録処理
            $this->productClassRepository->save($class);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'brand' => $class,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_class_edit', ['id' => $class->getId()]);
        }

        // エラー出力
        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true, false) as $error) {
                $errors[] = $error->current()->getMessage();
                // フィールド名を取得
                $fields[] = $error->current()->getOrigin()->getName();
            }
            // dump($errors);
            // dump($fields);
            // die();
        }

        return [
            'searchForm' => $searchForm->createView(),
            'form' => $form->createView(),
            'Class' => $class,
            'results' => $results,
            'Product' => $Product,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/class/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_class_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductClass $class)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$class->getId()]);

        try {
            $this->productClassRepository->delete($class);
            $this->entityManager->flush();

            $event = new EventArgs(
                [
                    'brand' => $class,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');
            log_info('メンバー削除完了', [$class->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$class->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $class->getProduct()->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$class->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_class');
    }

    /**
     * パンフレットマスタCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/class/export", name="admin_product_class_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_PRODUCT_CLASS);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            // ブランドデータ検索用のクエリビルダを取得.
            $qb = $this->productClassRepository
                ->createQueryBuilder('c')
                ->orderBy('c.id', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);

            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Class ProductClass */
                $Class = $entity;

                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();

                // CSV出力項目と合致するデータを取得.
                foreach ($Csvs as $Csv) {
                    // ブランドデータを検索.
                    $ExportCsvRow->setData($csvService->getData($Csv, $Class));

                    // one to one　や one to many は上の処理で事足りるが, one to one をした後に one to many をする場合はこの処理が必要
                    if ($Csv->getEntityName() === Product::class) {
                        $Product = $Class->getProduct();
                        $ExportCsvRow->setData($csvService->getData($Csv, $Product));
                    }

                    $ExportCsvRow->pushData();
                }

                // $row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'product_class_' . $now->format('YmdHis') . '.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->send();

        log_info('CSV出力ファイル名', [$filename]);

        return $response;
    }


    /**
     * 自動入力.
     *
     * @Route("/%eccube_admin_route%/product/class/new/autofill", name="admin_product_class_autofill", methods={"POST"})
     *
     */
    public function autofill(Request $request)
    {
        $product_code = $request->get('product_code');

        // $product = $this->productRepository->find($product_code);
        $product = $this->productRepository->findOneBy(['code' => $product_code]);

        $data = [];
        // 商品コード
        if ($product->getName()) {
            $data['product_name'] = $product->getName();
        }

        // サイトカテゴリ
        if ($product->getCategory()) {
            $data['Category'] = $product->getCategory()->getId();
        }
        // 画像
        if ($product->getProductImage()) {
            if (empty($data['product_images'])) {
                $data['product_images'] = [];
            }
            $productImages = $product->getProductImage();
            foreach ($productImages as $productImage) {
                $ProductImage = $this->productImageRepository->find($productImage->getId());
                $type = $ProductImage->getImageType();

                if (empty($data['product_images'][$type])) {
                    $data['product_images'][$type] = [];
                }
                $data['product_images'][$type][] = [
                    'id' => $ProductImage->getId(),
                    'file_name' => $ProductImage->getFileName(),
                    'image_type' => $type,
                    'sort_no' => $ProductImage->getSortNo(),
                ];
            }
        }
        // 商品説明１
        if ($product->getDescriptionDetail()) {
            $data['description_detail'] = $product->getDescriptionDetail();
        }
        // 送料計算用区分
        if ($product->getDeliveryCalculation()) {
            $data['DeliveryCalculation'] = $product->getDeliveryCalculation()->getId();
        }
        // 仕入先
        if ($product->getProductSupplier()) {
            $data['ProductSupplier'] = $product->getProductSupplier()->getId();
        }
        // 量目
        if ($product->getWeight()) {
            $data['weight'] = $product->getWeight();
        }
        // 加工場所
        if ($product->getProcessingPlace()) {
            $data['processing_place'] = $product->getProcessingPlace();
        }
        // 調理方法
        if ($product->getCookingMethod()) {
            $data['cooking_method'] = $product->getCookingMethod();
        }
        // 解凍区分
        if ($product->getDecompressionMethod()) {
            $data['DecompressionMethod'] = $product->getDecompressionMethod()->getId();
        }
        // 塩分
        if ($product->getSaltAmount()) {
            $data['salt_amount'] = $product->getSaltAmount();
        }
        // カロリー
        if ($product->getCalorie()) {
            $data['calorie'] = $product->getCalorie();
        }
        // アレルギー
        if ($product->getAllergy()) {
            $data['allergy'] = $product->getAllergy();
        }
        // 原材料
        if ($product->getRawMaterials()) {
            $data['raw_materials'] = $product->getRawMaterials();
        }

        return new JsonResponse($data);
    }
}
