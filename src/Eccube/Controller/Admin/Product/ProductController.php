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

use Customize\Entity\Master\BroadCategory;
use Customize\Entity\Master\TemperatureRange;
use Customize\Entity\Product\ProductSpecification;
use Customize\Repository\Admin\Master\TemperatureRangeRepository;
use Customize\Repository\Admin\Product\ProductSearchTemplateRepository;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\ExportCsvRow;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Product;
use Eccube\Entity\ProductCategory;
use Eccube\Entity\ProductClass;
use Eccube\Entity\ProductImage;
use Eccube\Entity\ProductStock;
use Eccube\Entity\ProductTag;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\ProductType;
use Eccube\Form\Type\Admin\SearchProductType;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\Master\ProductStatusRepository;
use Eccube\Repository\ProductBrandRepository;
use Eccube\Repository\ProductMakerRepository;
use Eccube\Repository\ProductSupplierRepository;
use Eccube\Repository\ProductGiftRepository;
use Eccube\Repository\ProductIconRepository;
use Eccube\Repository\Master\BulkBuyingRepository;
use Eccube\Repository\Master\PurchasingGroupRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductImageRepository;
use Eccube\Repository\ProductRepository;
use Customize\Repository\Admin\Product\ProductTopicRepository;
use Eccube\Repository\ProductSpecificationRepository;
use Eccube\Repository\TagRepository;
use Eccube\Repository\TaxRuleRepository;
use Eccube\Service\CsvExportService;
use Eccube\Util\CacheUtil;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;

class ProductController extends AbstractController
{
    /**
     * @var CsvExportService
     */
    protected $csvExportService;

    /**
     * @var ProductClassRepository
     */
    protected $productClassRepository;

    /**
     * @var ProductTopicRepository
     */
    protected $productTopicRepository;

    /**
     * @var ProductImageRepository
     */
    protected $productImageRepository;

    /**
     * @var PurchasingGroupRepository
     */
    protected $purchasingGroupRepository;

    /**
     * @var ProductBrandRepository
     */
    protected $productBrandRepository;

    /**
     * @var ProductMakerRepository
     */
    protected $productMakerRepository;

    /**
     * @var ProductSupplierRepository
     */
    protected $productSupplierRepository;

    /**
     * @var ProductGiftRepository
     */
    protected $productGiftRepository;

    /**
     * @var ProductIconRepository
     */
    protected $productIconRepository;

    /**
     * @var BulkBuyingRepository
     */
    protected $bulkBuyingRepository;

    /**
     * @var TaxRuleRepository
     */
    protected $taxRuleRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var BaseInfo
     */
    protected $BaseInfo;

    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var ProductSearchTemplateRepository
     */
    protected $productSearchTemplateRepository;

    /**
     * @var ProductStatusRepository
     */
    protected $productStatusRepository;

    /**
     * @var TagRepository
     */
    protected $tagRepository;

    /**
     * @var TemperatureRangeRepository
     */
    protected $temperatureRangeRepository;

    /**
     * ProductController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param ProductClassRepository $productClassRepository
     * @param ProductTopicRepository $productTopicRepository
     * @param ProductImageRepository $productImageRepository
     * @param PurchasingGroupRepository $productPurchasingGroupRepository
     * @param ProductBrandRepository $productBrandRepository
     * @param ProductMakerRepository $productMakerRepository
     * @param ProductSupplierRepository $productSupplierRepository
     * @param ProductGiftRepository $productGiftRepository
     * @param ProductIconRepository $productIconRepository
     * @param BulkBuyingRepository $BulkBuyingRepository
     * @param TaxRuleRepository $taxRuleRepository
     * @param CategoryRepository $categoryRepository
     * @param ProductRepository $productRepository
     * @param BaseInfoRepository $baseInfoRepository
     * @param PageMaxRepository $pageMaxRepository
     * @param ProductStatusRepository $productStatusRepository
     * @param TagRepository $tagRepository
     * @param ProductSearchTemplateRepository $productSearchTemplateRepository
     * @param TemperatureRangeRepository $temperatureRangeRepository
     */
    public function __construct(
        CsvExportService $csvExportService,
        ProductClassRepository $productClassRepository,
        ProductTopicRepository $productTopicRepository,
        ProductImageRepository $productImageRepository,
        PurchasingGroupRepository $purchasingGroupRepository,
        ProductBrandRepository $productBrandRepository,
        ProductMakerRepository $productMakerRepository,
        ProductSupplierRepository $productSupplierRepository,
        ProductGiftRepository $productGiftRepository,
        ProductIconRepository $productIconRepository,
        BulkBuyingRepository $bulkBuyingRepository,
        ProductSearchTemplateRepository $productSearchTemplateRepository,
        TaxRuleRepository $taxRuleRepository,
        CategoryRepository $categoryRepository,
        ProductRepository $productRepository,
        BaseInfoRepository $baseInfoRepository,
        PageMaxRepository $pageMaxRepository,
        ProductStatusRepository $productStatusRepository,
        TagRepository $tagRepository,
        TemperatureRangeRepository $temperatureRangeRepository
    ) {
        $this->csvExportService = $csvExportService;
        $this->productClassRepository = $productClassRepository;
        $this->productTopicRepository = $productTopicRepository;
        $this->productImageRepository = $productImageRepository;
        $this->purchasingGroupRepository = $purchasingGroupRepository;
        $this->productBrandRepository = $productBrandRepository;
        $this->productMakerRepository = $productMakerRepository;
        $this->productSupplierRepository = $productSupplierRepository;
        $this->productGiftRepository = $productGiftRepository;
        $this->productIconRepository = $productIconRepository;
        $this->bulkBuyingRepository = $bulkBuyingRepository;
        $this->taxRuleRepository = $taxRuleRepository;
        $this->categoryRepository = $categoryRepository;
        $this->productRepository = $productRepository;
        $this->productSearchTemplateRepository = $productSearchTemplateRepository;
        $this->BaseInfo = $baseInfoRepository->get();
        $this->pageMaxRepository = $pageMaxRepository;
        $this->productStatusRepository = $productStatusRepository;
        $this->tagRepository = $tagRepository;
        $this->temperatureRangeRepository = $temperatureRangeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product", name="admin_product", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/product/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_product_page", methods={"GET", "POST"})
     * @Template("@admin/Product/index.twig")
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {
        $user = $this->getUser();
        // Get Product search templates
        $orderSearchTemplates = $this->productSearchTemplateRepository->getAll($user);
        $builder = $this->formFactory
            ->createBuilder(SearchProductType::class);

        $event = new EventArgs(
            [
                'builder' => $builder,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_INDEX_INITIALIZE, $event);

        $searchForm = $builder->getForm();

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

        if ('POST' === $request->getMethod()) {
            $requestData = $request->request->all();
            if (!empty($requestData['search-template'])) {
                $searchTemplates = $this->productSearchTemplateRepository->find($requestData['search-template']);
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

        $searchData['name_code'] = $searchForm->get('name_code')->getData();
        $searchData['BroadCategory'] = $searchForm->get('BroadCategory')->getData();
        $searchData['MiddleCategory'] = $searchForm->get('MiddleCategory')->getData();
        $searchData['Category'] = $searchForm->get('Category')->getData();
        $searchData['DeliveryCalculation'] = $searchForm->get('DeliveryCalculation')->getData();
        $searchData['PackingManagement'] = $searchForm->get('PackingManagement')->getData();
        $searchData['Repack'] = $searchForm->get('Repack')->getData();
        $searchData['ProcessedProductCategory'] = $searchForm->get('ProcessedProductCategory')->getData();
        $searchData['ProductSupplier'] = $searchForm->get('ProductSupplier')->getData();
        $searchData['DecompressionMethod'] = $searchForm->get('DecompressionMethod')->getData();

        $qb = $this->productRepository->getQueryBuilderBySearchDataForAdmin($searchData);


        $event = new EventArgs(
            [
                'qb' => $qb,
                'searchData' => $searchData,
            ],
            $request
        );

        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_INDEX_SEARCH, $event);

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

        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $page_count,
            'has_errors' => false,
            'orderSearchTemplates' => $orderSearchTemplates,
            'OrderStatuses' => $this->productStatusRepository->findBy([], ['sort_no' => 'ASC']),
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/classes/{id}/load", name="admin_product_classes_load", methods={"GET"}, requirements={"id" = "\d+"}, methods={"GET"})
     * @Template("@admin/Product/product_class_popup.twig")
     * @ParamConverter("Product", options={"repository_method":"findWithSortedClassCategories"})
     */
    public function loadProductClasses(Request $request, Product $Product)
    {
        if (!$request->isXmlHttpRequest() && $this->isTokenValid()) {
            throw new BadRequestHttpException();
        }

        $data = [];
        /** @var $Product ProductRepository */
        if (!$Product) {
            throw new NotFoundHttpException();
        }

        if ($Product->hasProductClass()) {
            $class = $Product->getProductClasses();
            foreach ($class as $item) {
                $data[] = $item;
            }
        }

        return [
            'data' => $data,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/product/image/add", name="admin_product_image_add", methods={"POST"})
     */
    public function addImage(Request $request)
    {
        if (!$request->isXmlHttpRequest() && $this->isTokenValid()) {
            throw new BadRequestHttpException();
        }

        $images = $request->files->get('admin_product');

        $allowExtensions = ['gif', 'jpg', 'jpeg', 'png'];
        $files = [];
        if (count($images) > 0) {
            foreach ($images as $img) {
                foreach ($img as $image) {
                    //ファイルフォーマット検証
                    $mimeType = $image->getMimeType();
                    if (0 !== strpos($mimeType, 'image')) {
                        throw new UnsupportedMediaTypeHttpException();
                    }

                    // 拡張子
                    $extension = $image->getClientOriginalExtension();
                    if (!in_array(strtolower($extension), $allowExtensions)) {
                        throw new UnsupportedMediaTypeHttpException();
                    }

                    $filename = date('mdHis') . uniqid('_') . '.' . $extension;
                    $image->move($this->eccubeConfig['eccube_temp_image_dir'], $filename);
                    $files[] = $filename;
                }
            }
        }

        $event = new EventArgs(
            [
                'images' => $images,
                'files' => $files,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_ADD_IMAGE_COMPLETE, $event);
        $files = $event->getArgument('files');

        return $this->json(['files' => $files], 200);
    }

    /**
     * @Route("/%eccube_admin_route%/product/product/new", name="admin_product_product_new", methods={"GET", "POST"})
     * @Template("@admin/Product/product.twig")
     */
    public function register(Request $request, RouterInterface $router, CacheUtil $cacheUtil, $page_no = null, PaginatorInterface $paginator)
    {
        $Product = new Product();

        // フォームの作成 //
        $builder = $this->formFactory
            ->createBuilder(ProductType::class, $Product);
        $form = $builder->getForm();
        $form->handleRequest($request);

        $searchFormBuilder = $this->formFactory
            ->createBuilder(SearchProductType::class);
        $searchForm = $searchFormBuilder->getForm();
        $searchForm->handleRequest($request);

        // 商品コード検索処理 //
        $results = null;
        if ($searchForm->isSubmitted() && $searchForm->isValid()) {
            $searchData = $searchForm->getData();
            $searchData['code'] = $searchForm->get('code')->getData();
            $qb = $this->productRepository->getQueryBuilderBySearchProductCodeForAdmin($searchData);
            $results = $qb->getQuery()->getResult();
            if (count($results) === 0) {
                $results = 'admin.common.search_no_result';
            }
        }

        // エラー箇所取得 //
        if ($searchForm->isSubmitted() && !$searchForm->isValid()) {
            $errors = [];
            $fields = [];
            foreach ($searchForm->getErrors(true, false) as $error) {
                $errors[] = $error->getMessage();
                // フィールドも出力したい
                $fields[] = $error->getOrigin()->getName();
            }
            // dumpでエラーのフィ＝ルドを確認可能
            // dump($errors);
            // dump($fields);
            // die();
        }

        // 登録フォームの処理 //
        if ($form->isSubmitted() && $form->isValid()) {
            // 大分類 //
            $broad_category = $form->get('BroadCategory')->getData();
            $Product->setBroadCategory($broad_category);

            // 中分類 //
            $middle_category = $form->get('MiddleCategory')->getData();
            $Product->setMiddleCategory($middle_category);

            // 送料計算用区分 //
            $delivery_calculation = $form->get('DeliveryCalculation')->getData();
            $Product->setDeliveryCalculation($delivery_calculation);

            // 　詰込管理区分 //
            $packing_management = $form->get('PackingManagement')->getData();
            $Product->setPackingManagement($packing_management);

            // リパック区分 //
            $repack = $form->get('Repack')->getData();
            $Product->setRepack($repack);

            // 加工区分 //
            $processed_product_category_id = $form->get('ProcessedProductCategory')->getData();
            $Product->setProcessedProductCategory($processed_product_category_id);

            // 仕入先 //
            $ProductSupplier = $form->get('ProductSupplier')->getData();
            $Product->setProductSupplier($ProductSupplier);

            // 解凍区分 //
            $decompression_method_id = $form->get('DecompressionMethod')->getData();
            $Product->setDecompressionMethod($decompression_method_id);

            // サイトカテゴリ //
            $category = $form->get('Category')->getData();
            $Product->setCategory($category);

            // 商品登録
            $this->productRepository->save($Product);

            // 画像の登録
            $add_images = $form->get('add_images')->getData();
            foreach ($add_images as $add_image) {
                $object = json_decode($add_image);
                $file_name = $object->file;
                $image_type = $object->type;
                $ProductImage = new \Eccube\Entity\ProductImage();
                $ProductImage
                    ->setFileName($file_name)
                    ->setImageType($image_type)
                    ->setProduct($Product)
                    ->setSortNo(1);
                $Product->addProductImage($ProductImage);
                $this->entityManager->persist($ProductImage);

                // 移動
                $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $file_name);
                $file->move($this->eccubeConfig['eccube_save_image_dir']);
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('admin_product_product_edit', ['id' => $Product->getId()]);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            foreach ($form->getErrors(true, false) as $error) {
                $errors[] = $error;
            }
            // dumpでエラーのフィ＝ルドを確認可能
            // dump($errors);
            // die();
        }
        return [
            'form' => $form->createView(),
            'searchForm' => $searchForm->createView(),
            'results' => $results,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/product/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_product_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/product.twig")
     */
    public function edit(Request $request, $id, RouterInterface $router, CacheUtil $cacheUtil, $page_no = null, PaginatorInterface $paginator)
    {
        $Product = $this->productRepository->find($id);

        $form = $this->formFactory
            ->createBuilder(ProductType::class, $Product)
            ->getForm();

        // 画像のセット //
        $images = [];
        $ProductImages = $Product->getProductImage();
        foreach ($ProductImages as $ProductImage) {
            $images[] = json_encode([
                'file' => $ProductImage->getFileName(),
                'type' => $ProductImage->getImageType()
            ]);
        }
        $form['images']->setData($images);

        // edit処理
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // 大分類 //
            $broad_category_id = $form->get('BroadCategory')->getData();
            $Product->setBroadCategory($broad_category_id);

            // 中分類 //
            $middle_category_id = $form->get('MiddleCategory')->getData();
            $Product->setMiddleCategory($middle_category_id);

            // 送料計算用区分 //
            $delivery_calculation = $form->get('DeliveryCalculation')->getData();
            $Product->setDeliveryCalculation($delivery_calculation);

            // 　詰込管理区分 //
            $packing_management = $form->get('PackingManagement')->getData();
            $Product->setPackingManagement($packing_management);

            // リパック区分 //
            $repack = $form->get('Repack')->getData();
            $Product->setRepack($repack);

            // 加工区分 //
            $processed_product_category_id = $form->get('ProcessedProductCategory')->getData();
            $Product->setProcessedProductCategory($processed_product_category_id);

            // 仕入先 //
            $ProductSupplier = $form->get('ProductSupplier')->getData();
            $Product->setProductSupplier($ProductSupplier);

            // 解凍区分 //
            $decompression_method_id = $form->get('DecompressionMethod')->getData();
            $Product->setDecompressionMethod($decompression_method_id);

            // サイトカテゴリ //
            $category = $form->get('Category')->getData();
            $Product->setCategory($category);

            // 商品登録
            $this->productRepository->save($Product);

            // 画像の登録
            $add_images = $form->get('add_images')->getData();
            foreach ($add_images as $add_image) {
                $object = json_decode($add_image);
                $file_name = $object->file;
                $image_type = $object->type;
                $ProductImage = new \Eccube\Entity\ProductImage();
                $ProductImage
                    ->setFileName($file_name)
                    ->setImageType($image_type)
                    ->setProduct($Product)
                    ->setSortNo(1);
                $Product->addProductImage($ProductImage);
                $this->entityManager->persist($ProductImage);

                // 移動
                $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $file_name);
                $file->move($this->eccubeConfig['eccube_save_image_dir']);
            }

            // 画像の削除
            $delete_images = $form->get('delete_images')->getData();
            $fs = new Filesystem();
            foreach ($delete_images as $delete_image) {
                $object = json_decode($delete_image);
                $file_name = $object->file;
                $ProductImage = $this->productImageRepository->findOneBy([
                    'Product' => $Product,
                    'file_name' => $file_name,
                ]);

                if ($ProductImage instanceof ProductImage) {
                    $Product->removeProductImage($ProductImage);
                    $this->entityManager->remove($ProductImage);
                    $this->entityManager->flush();

                    // 他に同じ画像を参照する商品がなければ画像ファイルを削除
                    if (!$this->productImageRepository->findOneBy(['file_name' => $file_name])) {
                        $fs->remove($this->eccubeConfig['eccube_save_image_dir'] . '/' . $file_name);
                    }
                } else {
                    // 追加してすぐに削除した画像は、Entityに追加されない
                    $fs->remove($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $file_name);
                }
            }

            $this->entityManager->flush();

            $sortNos = $request->get('sort_no_images');
            if ($sortNos) {
                foreach ($sortNos as $sortNo) {
                    list($filename, $sortNo_val) = explode('//', $sortNo);
                    $ProductImage = $this->productImageRepository
                        ->findOneBy([
                            'file_name' => $filename,
                            'Product' => $Product,
                        ]);
                    $ProductImage->setSortNo($sortNo_val);
                    $this->entityManager->persist($ProductImage);
                }
            }
            $this->entityManager->flush();

            $Product->setUpdateDate(new \DateTime());
            $this->entityManager->flush();

            return $this->redirectToRoute('admin_product_product_edit', ['id' => $Product->getId()]);
        }

        return [
            'Product' => $Product,
            'form' => $form->createView(),
        ];
    }
    // !====================================================================================================
    // {
    //     $has_class = false;
    //     if (is_null($id)) {
    //         $Product = new Product();
    //         $ProductClass = new ProductClass();
    //         $ProductStatus = $this->productStatusRepository->find(ProductStatus::DISPLAY_HIDE);
    //         $Product
    //             ->addProductClass($ProductClass)
    //             ->setStatus($ProductStatus);
    //         $ProductClass
    //             ->setVisible(true)
    //             ->setStockUnlimited(true)
    //             ->setProduct($Product);
    //         $ProductStock = new ProductStock();
    //         $ProductClass->setProductStock($ProductStock);
    //         $ProductStock->setProductClass($ProductClass);

    //         $ProductSpecification = new ProductSpecification();
    //         $ProductSpecification->setProduct($Product);
    //     } else {
    // $Product = $this->productRepository->findWithSortedClassCategories($id);
    //         $ProductClass = null;
    //         $ProductStock = null;
    //         if (!$Product) {
    //             throw new NotFoundHttpException();
    //         }
    //         // 規格無しの商品の場合は、デフォルト規格を表示用に取得する
    //         $has_class = $Product->hasProductClass();
    //         if (!$has_class) {
    //             $ProductClasses = $Product->getProductClasses();
    //             foreach ($ProductClasses as $pc) {
    //                 if (!is_null($pc->getClassCategory1())) {
    //                     continue;
    //                 }
    //                 if ($pc->isVisible()) {
    //                     $ProductClass = $pc;
    //                     break;
    //                 }
    //             }
    //             if ($this->BaseInfo->isOptionProductTaxRule() && $ProductClass->getTaxRule()) {
    //                 $ProductClass->setTaxRate($ProductClass->getTaxRule()->getTaxRate());
    //             }
    //             $ProductStock = $ProductClass->getProductStock();
    //         }
    //         $ProductSpecification = $Product->getProductSpecification();
    //     }

    //     $builder = $this->formFactory
    //         ->createBuilder(ProductType::class, $Product);

    //     // 規格あり商品の場合、規格関連情報をFormから除外
    //     if ($has_class) {
    //         $builder->remove('class');
    //     }

    //     $event = new EventArgs(
    //         [
    //             'builder' => $builder,
    //             'Product' => $Product,
    //         ],
    //         $request
    //     );
    //     $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_EDIT_INITIALIZE, $event);

    //     $form = $builder->getForm();

    //     if (!$has_class) {
    //         $ProductClass->setStockUnlimited($ProductClass->isStockUnlimited());
    //         $form['class']->setData($ProductClass);
    //     }

    //     // ファイルの登録
    //     $images = [];
    //     $ProductImages = $Product->getProductImage();
    //     foreach ($ProductImages as $ProductImage) {
    //         $images[] = [
    //             'file' => $ProductImage->getFileName(),
    //             'type' => $ProductImage->getImageType()
    //         ];
    //     }
    //     $form['images']->setData($images);

    //     $categories = [];
    //     $ProductCategories = $Product->getProductCategories();
    //     foreach ($ProductCategories as $ProductCategory) {
    //         /* @var $ProductCategory \Eccube\Entity\ProductCategory */
    //         $categories[] = $ProductCategory->getCategory();
    //     }
    //     $form['Category']->setData($categories);

    //     $builder = $this->formFactory
    //         ->createBuilder(SearchProductType::class);
    //     $searchForm = $builder->getForm();

    //     $results = null;

    //     // formにProductSpecificationをセット
    //     $form['specification']->setData($ProductSpecification);

    //     if ('POST' === $request->getMethod()) {
    //         $form->handleRequest($request);
    //         $searchForm->handleRequest($request);
    //         if ($searchForm->isSubmitted()) {
    //             $searchData = $searchForm->getData();
    //             $qb = $this->productRepository->getQueryBuilderBySearchIdForAdmin($searchData);
    //             $results = $qb->getQuery()->getResult();
    //             if (count($results) === 0) {
    //                 $results = 'admin.common.search_no_result';
    //             }
    //         }
    //         $errors = [];
    //         $fields = [];
    //         if ($form->isSubmitted() && !$form->isValid()) {
    //             foreach ($form->getErrors(true, false) as $error) {
    //                 $errors[] = $error->current()->getMessage();
    //                 // フィールドも出力したい
    //                 $fields[] = $error->current()->getOrigin()->getName();
    //             }
    //             // dumpでエラーのフィ＝ルドを確認可能
    //         }
    //         if ($form->isSubmitted() && $form->isValid()) {
    //             log_info('商品登録開始', [$id]);
    //             $Product = $form->getData();

    //             if (!$has_class) {
    //                 $ProductClass = $form['class']->getData();

    //                 // 個別消費税
    //                 if ($this->BaseInfo->isOptionProductTaxRule()) {
    //                     if ($ProductClass->getTaxRate() !== null) {
    //                         if ($ProductClass->getTaxRule()) {
    //                             $ProductClass->getTaxRule()->setTaxRate($ProductClass->getTaxRate());
    //                         } else {
    //                             $taxrule = $this->taxRuleRepository->newTaxRule();
    //                             $taxrule->setTaxRate($ProductClass->getTaxRate());
    //                             $taxrule->setApplyDate(new \DateTime());
    //                             $taxrule->setProduct($Product);
    //                             $taxrule->setProductClass($ProductClass);
    //                             $ProductClass->setTaxRule($taxrule);
    //                         }

    //                         $ProductClass->getTaxRule()->setTaxRate($ProductClass->getTaxRate());
    //                     } else {
    //                         if ($ProductClass->getTaxRule()) {
    //                             $this->taxRuleRepository->delete($ProductClass->getTaxRule());
    //                             $ProductClass->setTaxRule(null);
    //                         }
    //                     }
    //                 }
    //                 $this->entityManager->persist($ProductClass);

    //                 // 在庫情報を作成
    //                 if (!$ProductClass->isStockUnlimited()) {
    //                     $ProductStock->setStock($ProductClass->getStock());
    //                 } else {
    //                     // 在庫無制限時はnullを設定
    //                     $ProductStock->setStock(null);
    //                 }
    //                 $this->entityManager->persist($ProductStock);
    //             }

    //             // カテゴリの登録
    //             // 一度クリア
    //             /* @var $Product \Eccube\Entity\Product */
    //             foreach ($Product->getProductCategories() as $ProductCategory) {
    //                 $Product->removeProductCategory($ProductCategory);
    //                 $this->entityManager->remove($ProductCategory);
    //             }
    //             $this->entityManager->persist($Product);
    //             $this->entityManager->flush();

    //             $count = 1;
    //             $Categories = $form->get('Category')->getData();
    //             $categoriesIdList = [];
    //             foreach ($Categories as $Category) {
    //                 foreach ($Category->getPath() as $ParentCategory) {
    //                     if (!isset($categoriesIdList[$ParentCategory->getId()])) {
    //                         $ProductCategory = $this->createProductCategory($Product, $ParentCategory, $count);
    //                         $this->entityManager->persist($ProductCategory);
    //                         $count++;
    //                         /* @var $Product \Eccube\Entity\Product */
    //                         $Product->addProductCategory($ProductCategory);
    //                         $categoriesIdList[$ParentCategory->getId()] = true;
    //                     }
    //                 }
    //                 if (!isset($categoriesIdList[$Category->getId()])) {
    //                     $ProductCategory = $this->createProductCategory($Product, $Category, $count);
    //                     $this->entityManager->persist($ProductCategory);
    //                     $count++;
    //                     /* @var $Product \Eccube\Entity\Product */
    //                     $Product->addProductCategory($ProductCategory);
    //                     $categoriesIdList[$Category->getId()] = true;
    //                 }
    //             }


    //             //
    //             $Product->setProductTopic($form->get('ProductTopic')->getData());
    //             // メーカー登録
    //             $Product->setProductMaker($form->get('ProductMaker')->getData());
    //             // 仕入先登録
    //             $Product->setProductSupplier($form->get('ProductSupplier')->getData());
    //             // ギフト登録
    //             $Product->setProductGift($form->get('ProductGift')->getData());
    //             // まとめ買いグループ登録
    //             $Product->setBulkBuying($form->get('BulkBuying')->getData());
    //             // アイコン登録;
    //             $ProductIcons = $form->get('ProductIcon')->getData();
    //             $hasProductIcons = $Product->getProductIcon();
    //             // 一度登録されていたリレーションをすべて削除
    //             if ($hasProductIcons) {
    //                 foreach ($hasProductIcons as $hasProductIcon) {
    //                     $Product->removeProductIcon($hasProductIcon);
    //                     $hasProductIcon->setProduct(null);
    //                 }
    //             }
    //             // 新しくアイコン登録
    //             foreach ($ProductIcons as $ProductIcon) {
    //                 $Product->addProductIcon($ProductIcon);
    //                 $ProductIcon->setProduct($Product);
    //             }

    //             $this->entityManager->persist($Product);

    //             // 画像の登録
    //             $add_images = $form->get('add_images')->getData();
    //             foreach ($add_images as $add_image) {
    //                 $object = json_decode($add_image);
    //                 $file_name = $object->file;
    //                 $image_type = $object->type;
    //                 $ProductImage = new \Eccube\Entity\ProductImage();
    //                 $ProductImage
    //                     ->setFileName($file_name)
    //                     ->setImageType($image_type)
    //                     ->setProduct($Product)
    //                     ->setProductClass($ProductClass)
    //                     ->setSortNo(1);
    //                 $Product->addProductImage($ProductImage);
    //                 $this->entityManager->persist($ProductImage);

    //                 // 移動
    //                 $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $file_name);
    //                 $file->move($this->eccubeConfig['eccube_save_image_dir']);
    //             }

    //             // 画像の削除
    //             $delete_images = $form->get('delete_images')->getData();
    //             $fs = new Filesystem();
    //             foreach ($delete_images as $delete_image) {
    //                 $object = json_decode($delete_image);
    //                 $file_name = $object->file;
    //                 $ProductImage = $this->productImageRepository->findOneBy([
    //                     'Product' => $Product,
    //                     'file_name' => $file_name,
    //                 ]);

    //                 if ($ProductImage instanceof ProductImage) {
    //                     $Product->removeProductImage($ProductImage);
    //                     $this->entityManager->remove($ProductImage);
    //                     $this->entityManager->flush();

    //                     // 他に同じ画像を参照する商品がなければ画像ファイルを削除
    //                     if (!$this->productImageRepository->findOneBy(['file_name' => $file_name])) {
    //                         $fs->remove($this->eccubeConfig['eccube_save_image_dir'] . '/' . $file_name);
    //                     }
    //                 } else {
    //                     // 追加してすぐに削除した画像は、Entityに追加されない
    //                     $fs->remove($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $file_name);
    //                 }
    //             }

    //             $this->entityManager->flush();

    //             $sortNos = $request->get('sort_no_images');
    //             if ($sortNos) {
    //                 foreach ($sortNos as $sortNo) {
    //                     list($filename, $sortNo_val) = explode('//', $sortNo);
    //                     $ProductImage = $this->productImageRepository
    //                         ->findOneBy([
    //                             'file_name' => $filename,
    //                             'Product' => $Product,
    //                         ]);
    //                     $ProductImage->setSortNo($sortNo_val);
    //                     $this->entityManager->persist($ProductImage);
    //                 }
    //             }
    //             $this->entityManager->flush();

    //             // 商品タグの登録
    //             // 商品タグを一度クリア
    //             $ProductTags = $Product->getProductTag();
    //             foreach ($ProductTags as $ProductTag) {
    //                 $Product->removeProductTag($ProductTag);
    //                 $this->entityManager->remove($ProductTag);
    //             }

    //             // 商品タグの登録
    //             $Tags = $form->get('Tag')->getData();
    //             foreach ($Tags as $Tag) {
    //                 $ProductTag = new ProductTag();
    //                 $ProductTag
    //                     ->setProduct($Product)
    //                     ->setTag($Tag);
    //                 $Product->addProductTag($ProductTag);
    //                 $this->entityManager->persist($ProductTag);
    //             }

    //             // ProductSpecificationの登録
    //             $ProductSpecification = $form['specification']->getData();
    //             $Product->setProductSpecification($ProductSpecification);
    //             $this->productSpecificationRepository->save($ProductSpecification);
    //             $Product->setUpdateDate(new \DateTime());
    //             $this->entityManager->flush();

    //             log_info('商品登録完了', [$id]);

    //             $event = new EventArgs(
    //                 [
    //                     'form' => $form,
    //                     'Product' => $Product,
    //                 ],
    //                 $request
    //             );
    //             $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_EDIT_COMPLETE, $event);

    //             $this->addSuccess('admin.common.save_complete', 'admin');

    //             if ($returnLink = $form->get('return_link')->getData()) {
    //                 try {
    //                     // $returnLinkはpathの形式で渡される. pathが存在するかをルータでチェックする.
    //                     $pattern = '/^' . preg_quote($request->getBasePath(), '/') . '/';
    //                     $returnLink = preg_replace($pattern, '', $returnLink);
    //                     $result = $router->match($returnLink);
    //                     // パラメータのみ抽出
    //                     $params = array_filter($result, function ($key) {
    //                         return 0 !== \strpos($key, '_');
    //                     }, ARRAY_FILTER_USE_KEY);

    //                     // pathからurlを再構築してリダイレクト.
    //                     return $this->redirectToRoute($result['_route'], $params);
    //                 } catch (\Exception $e) {
    //                     // マッチしない場合はログ出力してスキップ.
    //                     log_warning('URLの形式が不正です。');
    //                 }
    //             }

    //             $cacheUtil->clearDoctrineCache();

    //             return $this->redirectToRoute('admin_product_product_edit', ['id' => $Product->getId()]);
    //         }
    //     }

    //     // 検索結果の保持
    //     $builder = $this->formFactory
    //         ->createBuilder(SearchProductType::class);

    //     $event = new EventArgs(
    //         [
    //             'builder' => $builder,
    //             'Product' => $Product,
    //         ],
    //         $request
    //     );
    //     $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_EDIT_SEARCH, $event);


    //     // if ('POST' === $request->getMethod()) {
    //     //     $searchForm->handleRequest($request);
    //     //     if ($searchForm->isSubmitted() && $searchForm->isValid()) {
    //     //         $searchData = $searchForm->getData();
    //     //         $qb = $this->productRepository->getQueryBuilderBySearchDataForAdmin($searchData);
    //     //         dump($qb);
    //     //     }
    //     // }



    //     // Get Tags
    //     $TagsList = $this->tagRepository->getList();

    //     // ツリー表示のため、ルートからのカテゴリを取得
    //     $TopCategories = $this->categoryRepository->getList(null);
    //     $PurchasingGroups = $this->purchasingGroupRepository->findAll();
    //     $Brands = $this->productBrandRepository->findAll();
    //     $Topics = $this->productTopicRepository->findAll();
    //     $Makers = $this->productMakerRepository->findAll();
    //     $Suppliers = $this->productSupplierRepository->findAll();
    //     $Gifts = $this->productGiftRepository->findAll();
    //     $BulkBuyings = $this->bulkBuyingRepository->findAll();
    //     $Icons = $this->productIconRepository->findAll();
    //     $ChoicedCategoryIds = array_map(function ($Category) {
    //         return $Category->getId();
    //     }, $form->get('Category')->getData());


    //     function choicedProductElementToOne($ProductElement)
    //     {
    //         if ($ProductElement) {
    //             return $ProductElement->getId();
    //         }
    //     }

    //     function choicedProductElementsToMany($ProductElements)
    //     {
    //         $arrayElements = [];
    //         if ($ProductElements) {
    //             foreach ($ProductElements as $ProductElement) {
    //                 $arrayElements[] = $ProductElement->getId();
    //             }
    //         }
    //         return $arrayElements;
    //     }

    //     $ChoicedPurchasingGroupId = choicedProductElementToOne($Product->getPurchasingGroup());
    //     $ChoicedBrandId = choicedProductElementToOne($Product->getProductBrand());
    //     $ChoicedTopicId = choicedProductElementToOne($Product->getProductTopic());
    //     $ChoicedMakerId = choicedProductElementToOne($Product->getProductMaker());
    //     $ChoicedSupplierId = choicedProductElementToOne($Product->getProductSupplier());
    //     $ChoicedGiftId = choicedProductElementToOne($Product->getProductGift());
    //     $ChoicedBulkBuyingId = choicedProductElementToOne($Product->getBulkBuying());
    //     $ChoicedIconIds = choicedProductElementsToMany($Product->getProductIcon());
    //     return [
    //         'Product' => $Product,
    //         'TagsList' => $TagsList,
    //         'form' => $form->createView(),
    //         'searchForm' => $searchForm->createView(),
    //         'has_class' => $has_class,
    //         'id' => $id,
    //         'TopCategories' => $TopCategories,
    //         'PurchasingGroups' => $PurchasingGroups,
    //         'Brands' => $Brands,
    //         'Topics' => $Topics,
    //         'Makers' => $Makers,
    //         'Suppliers' => $Suppliers,
    //         'Gifts' => $Gifts,
    //         'BulkBuyings' => $BulkBuyings,
    //         'Icons' => $Icons,
    //         'ChoicedCategoryIds' => $ChoicedCategoryIds,
    //         'ChoicedPurchasingGroupId' => $ChoicedPurchasingGroupId,
    //         'ChoicedBrandId' => $ChoicedBrandId,
    //         'ChoicedTopicId' => $ChoicedTopicId,
    //         'ChoicedMakerId' => $ChoicedMakerId,
    //         'ChoicedSupplierId' => $ChoicedSupplierId,
    //         'ChoicedGiftId' => $ChoicedGiftId,
    //         'ChoicedBulkBuyingId' => $ChoicedBulkBuyingId,
    //         'ChoicedIconIds' => $ChoicedIconIds,
    //         'results' => $results,
    //     ];
    // }
    // !====================================================================================================================================

    /**
     * @Route("/%eccube_admin_route%/product/product/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id = null, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();
        $session = $request->getSession();
        $page_no = intval($session->get('eccube.admin.product.search.page_no'));
        $page_no = $page_no ? $page_no : Constant::ENABLED;
        $message = null;
        $success = false;

        if (!is_null($id)) {
            /* @var $Product \Eccube\Entity\Product */
            $Product = $this->productRepository->find($id);
            if (!$Product) {
                if ($request->isXmlHttpRequest()) {
                    $message = trans('admin.common.delete_error_already_deleted');

                    return $this->json(['success' => $success, 'message' => $message]);
                } else {
                    $this->deleteMessage();
                    $rUrl = $this->generateUrl('admin_product_page', ['page_no' => $page_no]) . '?resume=' . Constant::ENABLED;

                    return $this->redirect($rUrl);
                }
            }

            if ($Product instanceof Product) {
                log_info('商品削除開始', [$id]);

                $deleteImages = $Product->getProductImage();
                $ProductClasses = $Product->getProductClasses();

                try {
                    $this->productRepository->delete($Product);
                    $this->entityManager->flush();

                    $event = new EventArgs(
                        [
                            'Product' => $Product,
                            'ProductClass' => $ProductClasses,
                            'deleteImages' => $deleteImages,
                        ],
                        $request
                    );
                    $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_DELETE_COMPLETE, $event);
                    $deleteImages = $event->getArgument('deleteImages');

                    // 画像ファイルの削除(commit後に削除させる)
                    /** @var ProductImage $deleteImage */
                    foreach ($deleteImages as $deleteImage) {
                        if ($this->productImageRepository->findOneBy(['file_name' => $deleteImage->getFileName()])) {
                            continue;
                        }
                        try {
                            $fs = new Filesystem();
                            $fs->remove($this->eccubeConfig['eccube_save_image_dir'] . '/' . $deleteImage);
                        } catch (\Exception $e) {
                            // エラーが発生しても無視する
                        }
                    }

                    log_info('商品削除完了', [$id]);

                    $success = true;
                    $message = trans('admin.common.delete_complete');

                    $cacheUtil->clearDoctrineCache();
                } catch (ForeignKeyConstraintViolationException $e) {
                    log_info('商品削除エラー', [$id]);
                    $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $Product->getName()]);
                }
            } else {
                log_info('商品削除エラー', [$id]);
                $message = trans('admin.common.delete_error');
            }
        } else {
            log_info('商品削除エラー', [$id]);
            $message = trans('admin.common.delete_error');
        }

        if ($request->isXmlHttpRequest()) {
            return $this->json(['success' => $success, 'message' => $message]);
        } else {
            if ($success) {
                $this->addSuccess($message, 'admin');
            } else {
                $this->addError($message, 'admin');
            }

            $rUrl = $this->generateUrl('admin_product_page', ['page_no' => $page_no]) . '?resume=' . Constant::ENABLED;

            return $this->redirect($rUrl);
        }
    }

    /**
     * @Route("/%eccube_admin_route%/product/product/{id}/copy", requirements={"id" = "\d+"}, name="admin_product_product_copy", methods={"POST"})
     */
    public function copy(Request $request, $id = null)
    {
        $this->isTokenValid();

        if (!is_null($id)) {
            $Product = $this->productRepository->find($id);
            if ($Product instanceof Product) {
                $CopyProduct = clone $Product;
                $CopyProduct->copy();
                $ProductStatus = $this->productStatusRepository->find(ProductStatus::DISPLAY_HIDE);
                $CopyProduct->setStatus($ProductStatus);

                $CopyProductCategories = $CopyProduct->getProductCategories();
                foreach ($CopyProductCategories as $Category) {
                    $this->entityManager->persist($Category);
                }

                // 規格あり商品の場合は, デフォルトの商品規格を取得し登録する.
                if ($CopyProduct->hasProductClass()) {
                    $dummyClass = $this->productClassRepository->findOneBy([
                        'visible' => false,
                        'ClassCategory1' => null,
                        'ClassCategory2' => null,
                        'Product' => $Product,
                    ]);
                    $dummyClass = clone $dummyClass;
                    $dummyClass->setProduct($CopyProduct);
                    $CopyProduct->addProductClass($dummyClass);
                }

                $CopyProductClasses = $CopyProduct->getProductClasses();
                foreach ($CopyProductClasses as $Class) {
                    $Stock = $Class->getProductStock();
                    $CopyStock = clone $Stock;
                    $CopyStock->setProductClass($Class);
                    $this->entityManager->persist($CopyStock);

                    $TaxRule = $Class->getTaxRule();
                    if ($TaxRule) {
                        $CopyTaxRule = clone $TaxRule;
                        $CopyTaxRule->setProductClass($Class);
                        $CopyTaxRule->setProduct($CopyProduct);
                        $this->entityManager->persist($CopyTaxRule);
                    }
                    $this->entityManager->persist($Class);
                }
                $Images = $CopyProduct->getProductImage();
                foreach ($Images as $Image) {
                    // 画像ファイルを新規作成
                    $extension = pathinfo($Image->getFileName(), PATHINFO_EXTENSION);
                    $filename = date('mdHis') . uniqid('_') . '.' . $extension;
                    try {
                        $fs = new Filesystem();
                        $fs->copy($this->eccubeConfig['eccube_save_image_dir'] . '/' . $Image->getFileName(), $this->eccubeConfig['eccube_save_image_dir'] . '/' . $filename);
                    } catch (\Exception $e) {
                        // エラーが発生しても無視する
                    }
                    $Image->setFileName($filename);

                    $this->entityManager->persist($Image);
                }
                $Tags = $CopyProduct->getProductTag();
                foreach ($Tags as $Tag) {
                    $this->entityManager->persist($Tag);
                }

                $this->entityManager->persist($CopyProduct);

                $this->entityManager->flush();

                $event = new EventArgs(
                    [
                        'Product' => $Product,
                        'CopyProduct' => $CopyProduct,
                        'CopyProductCategories' => $CopyProductCategories,
                        'CopyProductClasses' => $CopyProductClasses,
                        'images' => $Images,
                        'Tags' => $Tags,
                    ],
                    $request
                );
                $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_COPY_COMPLETE, $event);

                $this->addSuccess('admin.product.copy_complete', 'admin');

                return $this->redirectToRoute('admin_product_product_edit', ['id' => $CopyProduct->getId()]);
            } else {
                $this->addError('admin.product.copy_error', 'admin');
            }
        } else {
            $msg = trans('admin.product.copy_error');
            $this->addError($msg, 'admin');
        }

        return $this->redirectToRoute('admin_product');
    }

    /**
     * 商品CSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/export", name="admin_product_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_PRODUCT);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            // 商品データ検索用のクエリビルダを取得.
            $qb = $this->csvExportService
                ->getProductQueryBuilder($request);

            // Get stock status
            $isOutOfStock = 0;
            $session = $request->getSession();
            if ($session->has('eccube.admin.product.search')) {
                $searchData = $session->get('eccube.admin.product.search', []);
                if (isset($searchData['stock_status']) && $searchData['stock_status'] === 0) {
                    $isOutOfStock = 1;
                }
            }

            // joinする場合はiterateが使えないため, select句をdistinctする.
            // http://qiita.com/suin/items/2b1e98105fa3ef89beb7
            // distinctのmysqlとpgsqlの挙動をあわせる.
            // http://uedatakeshi.blogspot.jp/2010/04/distinct-oeder-by-postgresmysql.html
            $qb->resetDQLPart('select')
                ->resetDQLPart('orderBy')
                ->orderBy('p.update_date', 'DESC');

            if ($isOutOfStock) {
                $qb->select('p, pc')
                    ->distinct();
            } else {
                $qb->select('p')
                    ->distinct();
            }
            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);

            $this->csvExportService->exportData(function ($entity, CsvExportService $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Product \Eccube\Entity\Product */
                $Product = $entity;

                /** @var $ProductClasses \Eccube\Entity\ProductClass[] */
                $ProductClasses = $Product->getProductClasses();

                foreach ($ProductClasses as $ProductClass) {
                    $ExportCsvRow = new ExportCsvRow();

                    // CSV出力項目と合致するデータを取得.
                    foreach ($Csvs as $Csv) {
                        // 商品データを検索.
                        $ExportCsvRow->setData($csvService->getData($Csv, $Product));
                        if ($ExportCsvRow->isDataNull()) {
                            // 商品規格情報を検索.
                            $ExportCsvRow->setData($csvService->getData($Csv, $ProductClass));
                        }

                        $event = new EventArgs(
                            [
                                'csvService' => $csvService,
                                'Csv' => $Csv,
                                'ProductClass' => $ProductClass,
                                'ExportCsvRow' => $ExportCsvRow,
                            ],
                            $request
                        );
                        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_CSV_EXPORT, $event);

                        $ExportCsvRow->pushData();
                    }

                    // $row[] = number_format(memory_get_usage(true));
                    // 出力.
                    $csvService->fputcsv($ExportCsvRow->getRow());
                }
            });
        });

        $now = new \DateTime();
        $filename = 'product_' . $now->format('YmdHis') . '.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->send();

        log_info('商品CSV出力ファイル名', [$filename]);

        return $response;
    }

    /**
     * ProductCategory作成
     *
     * @param \Eccube\Entity\Product $Product
     * @param \Eccube\Entity\Category $Category
     * @param integer $count
     *
     * @return \Eccube\Entity\ProductCategory
     */
    private function createProductCategory($Product, $Category, $count)
    {
        $ProductCategory = new ProductCategory();
        $ProductCategory->setProduct($Product);
        $ProductCategory->setProductId($Product->getId());
        $ProductCategory->setCategory($Category);
        $ProductCategory->setCategoryId($Category->getId());

        return $ProductCategory;
    }

    /**
     * Bulk public action
     *
     * @Route("/%eccube_admin_route%/product/bulk/product-status/{id}", requirements={"id" = "\d+"}, name="admin_product_bulk_product_status", methods={"POST"})
     *
     * @param Request $request
     * @param ProductStatus $ProductStatus
     *
     * @return RedirectResponse
     */
    public function bulkProductStatus(Request $request, ProductStatus $ProductStatus, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();

        /** @var Product[] $Products */
        $Products = $this->productRepository->findBy(['id' => $request->get('ids')]);
        $count = 0;
        foreach ($Products as $Product) {
            try {
                $Product->setStatus($ProductStatus);
                $this->productRepository->save($Product);
                $count++;
            } catch (\Exception $e) {
                $this->addError($e->getMessage(), 'admin');
            }
        }
        try {
            if ($count) {
                $this->entityManager->flush();
                $msg = $this->translator->trans('admin.product.bulk_change_status_complete', [
                    '%count%' => $count,
                    '%status%' => $ProductStatus->getName(),
                ]);
                $this->addSuccess($msg, 'admin');
                $cacheUtil->clearDoctrineCache();
            }
        } catch (\Exception $e) {
            $this->addError($e->getMessage(), 'admin');
        }

        return $this->redirectToRoute('admin_product', ['resume' => Constant::ENABLED]);
    }
}
