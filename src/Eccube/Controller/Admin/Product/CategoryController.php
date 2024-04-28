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
use Eccube\Entity\Category;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\CategoryType;
use Eccube\Repository\CategoryRepository;
use Eccube\Service\CsvExportService;
use Eccube\Util\CacheUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\File;

class CategoryController extends AbstractController
{
    /**
     * @var CsvExportService
     */
    protected $csvExportService;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * CategoryController constructor.
     *
     * @param CsvExportService $csvExportService
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(
        CsvExportService $csvExportService,
        CategoryRepository $categoryRepository
    ) {
        $this->csvExportService = $csvExportService;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/category", name="admin_product_category", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/product/category/{parent_id}", requirements={"parent_id" = "\d+"}, name="admin_product_category_show", methods={"GET"})
     * @Template("@admin/Product/Category/category_list.twig")
     */
    public function index(Request $request, $parent_id = null, $id = null, CacheUtil $cacheUtil)
    {
       
        if ($parent_id) {
            /** @var Category $Parent */
            $Parent = $this->categoryRepository->find($parent_id);
            if (!$Parent) {
                throw new NotFoundHttpException();
            }
        } else {
            $Parent = null;
        }
        if ($id) {
            
            $TargetCategory = $this->categoryRepository->find($id);
            if (!$TargetCategory) {
                throw new NotFoundHttpException();
            }
            $Parent = $TargetCategory->getParent();
        } else {
            $TargetCategory = new \Eccube\Entity\Category();
            $TargetCategory->setParent($Parent);
            if ($Parent) {
                $TargetCategory->setHierarchy($Parent->getHierarchy() + 1);
            } else {
                $TargetCategory->setHierarchy(1);
            }
        }

        $Categories = $this->categoryRepository->getList($Parent);
        // $Categories = $this->categoryRepository->getList();

        // ツリー表示のため、ルートからのカテゴリを取得
        $TopCategories = $this->categoryRepository->getList(null);

        $builder = $this->formFactory
            ->createBuilder(CategoryType::class, $TargetCategory);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Parent' => $Parent,
                'TargetCategory' => $TargetCategory,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_CATEGORY_INDEX_INITIALIZE, $event);

        $form = $builder->getForm();

        $forms = [];
        foreach ($Categories as $Category) {
            $forms[$Category->getId()] = $this->formFactory
                ->createNamed('category_'.$Category->getId(), CategoryType::class, $Category);
        }

        if ($request->getMethod() === 'POST') {
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                if ($this->eccubeConfig['eccube_category_nest_level'] < $TargetCategory->getHierarchy()) {
                    throw new BadRequestHttpException();
                }
                log_info('カテゴリ登録開始', [$id]);

                // 画像の登録
                $add_image = $form->get('add_image')->getData();

                $TargetCategory->setImagePath($add_image);
                // 移動
                $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $add_image);
                $file->move($this->eccubeConfig['eccube_save_image_dir']);

                $this->categoryRepository->save($TargetCategory);

                log_info('カテゴリ登録完了', [$id]);

                // $formが保存されたフォーム
                // 下の編集用フォームの場合とイベント名が共通のため
                // このイベントのリスナーではsubmitされているフォームを判定する必要がある
                $event = new EventArgs(
                    [
                        'form' => $form,
                        'Parent' => $Parent,
                        'TargetCategory' => $TargetCategory,
                    ],
                    $request
                );
                $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_CATEGORY_INDEX_COMPLETE, $event);

                $this->addSuccess('admin.common.save_complete', 'admin');

                $cacheUtil->clearDoctrineCache();

                if ($Parent) {
                    return $this->redirectToRoute('admin_product_category_show', ['parent_id' => $Parent->getId()]);
                } else {
                    return $this->redirectToRoute('admin_product_category');
                }
            }

          

            foreach ($forms as $editForm) {
                $editForm->handleRequest($request);
                if ($editForm->isSubmitted() && $editForm->isValid()) {
                    $this->categoryRepository->save($editForm->getData());

                    // $editFormが保存されたフォーム
                    // 上の新規登録用フォームの場合とイベント名が共通のため
                    // このイベントのリスナーではsubmitされているフォームを判定する必要がある
                    $event = new EventArgs(
                        [
                            'form' => $form,
                            'editForm' => $editForm,
                            'Parent' => $Parent,
                            'TargetCategory' => $editForm->getData(),
                        ],
                        $request
                    );

                    $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_CATEGORY_INDEX_COMPLETE, $event);

                    $this->addSuccess('admin.common.save_complete', 'admin');

                    $cacheUtil->clearDoctrineCache();

                    if ($Parent) {
                        return $this->redirectToRoute('admin_product_category_show', ['parent_id' => $Parent->getId()]);
                    } else {
                        return $this->redirectToRoute('admin_product_category');
                    }
                }
            }
        }

        $formViews = [];
        $formErrors = [];
        foreach ($forms as $key => $value) {
            $formViews[$key] = $value->createView();
            $formErrors[$key]['count'] = $value->getErrors(true)->count();
        }

        $Ids = [];
        if ($Parent && $Parent->getParents()) {
            foreach ($Parent->getParents() as $item) {
                $Ids[] = $item['id'];
            }
        }
        $Ids[] = intval($parent_id);

        return [
            'form' => $form->createView(),
            'Parent' => $Parent,
            'Ids' => $Ids,
            'Categories' => $Categories,
            'TopCategories' => $TopCategories,
            'TargetCategory' => $TargetCategory,
            'forms' => $formViews,
            'error_forms' => $formErrors,
        ];
    }


    /**
     * @Route("/%eccube_admin_route%/product/category/new", name="admin_product_category_new", methods={"GET", "POST"})
     * @Template("@admin/Product/Category/category_create.twig")
     */
    public function create(Request $request, $parent_id = null, $id = null, CacheUtil $cacheUtil) 
    {
        if ($parent_id) {
            /** @var Category $Parent */
            $Parent = $this->categoryRepository->find($parent_id);
            if (!$Parent) {
                throw new NotFoundHttpException();
            }
        } else {
            $Parent = null;
        }
        if ($id) {
            $TargetCategory = $this->categoryRepository->find($id);
            if (!$TargetCategory) {
                throw new NotFoundHttpException();
            }
            $Parent = $TargetCategory->getParent();
        } else {
            $TargetCategory = new \Eccube\Entity\Category();
            $TargetCategory->setParent($Parent);
            if ($Parent) {
                $TargetCategory->setHierarchy($Parent->getHierarchy() + 1);
            } else {
                $TargetCategory->setHierarchy(1);
            }
        }

        $Categories = $this->categoryRepository->getList($Parent);

        $TopCategories = $this->categoryRepository->getList(null);

        $builder = $this->formFactory
            ->createBuilder(CategoryType::class, $TargetCategory);


        $form = $builder->getForm();
        

         

        return [
            'form' => $form->createView(),
            'Parent' => $Parent,
            'Categories' => $Categories,
            'TopCategories' => $TopCategories,
            'TargetCategory' => $TargetCategory,
        ];
    }


      /**
     * @Route("/%eccube_admin_route%/product/category/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_category_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/Category/category_edit.twig")
     */
    public function edit(Request $request,$id , Category $TargetCategory)
    {

        $TargetCategory = $this->categoryRepository->find($id);

            // Handle the form submission
            $form = $this->createForm(CategoryType::class, $TargetCategory);
            $form->handleRequest($request);
         
        if ($form->isSubmitted() && $form->isValid()) {

            // log_info('カテゴリ登録開始', [$id]);
            $add_image = $form->get('add_image')->getData();

            $TargetCategory->setImagePath($add_image);
            // 移動
            $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $add_image);
            $file->move($this->eccubeConfig['eccube_save_image_dir']);

            $this->categoryRepository->save($TargetCategory);            

            // log_info('カテゴリ登録完了', [$id]);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'TargetCategory' => $TargetCategory,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

           
             return $this->redirectToRoute('admin_product_category');
        }

        return [
            'form' => $form->createView(),
            'TargetCategory' => $TargetCategory,
        ];
    }
  

    /**
     * @Route("/%eccube_admin_route%/product/category/{id}/up", requirements={"id" = "\d+"}, name="admin_product_category_up", methods={"PUT"})
     */
    public function up(Request $request, Category $category)
    {
        $this->isTokenValid();

        try {
            $this->categoryRepository->up($category);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$category->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_category');
    }

    /**
     * @Route("/%eccube_admin_route%/product/category/{id}/down", requirements={"id" = "\d+"}, name="admin_product_category_down", methods={"PUT"})
     */
    public function down(Request $request, Category $category)
    {
        $this->isTokenValid();

        try {
            $this->categoryRepository->down($category);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$category->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_category');
    }



    /**
     * @Route("/%eccube_admin_route%/product/category/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_category_delete", methods={"DELETE"})
     */
    public function delete(Request $request, $id, CacheUtil $cacheUtil)
    {
        $this->isTokenValid();

        $TargetCategory = $this->categoryRepository->find($id);
        if (!$TargetCategory) {
            $this->deleteMessage();

            return $this->redirectToRoute('admin_product_category');
        }
        $Parent = $TargetCategory->getParent();

        log_info('カテゴリ削除開始', [$id]);

        try {
            $this->categoryRepository->delete($TargetCategory);

            $event = new EventArgs(
                [
                    'Parent' => $Parent,
                    'TargetCategory' => $TargetCategory,
                ], $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_CATEGORY_DELETE_COMPLETE, $event);

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('カテゴリ削除完了', [$id]);

            $cacheUtil->clearDoctrineCache();
        } catch (\Exception $e) {
            log_info('カテゴリ削除エラー', [$id, $e]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $TargetCategory->getName()]);
            $this->addError($message, 'admin');
        }

        if ($Parent) {
            return $this->redirectToRoute('admin_product_category_show', ['parent_id' => $Parent->getId()]);
        } else {
            return $this->redirectToRoute('admin_product_category');
        }
    }

    /**
     * @Route("/%eccube_admin_route%/product/category/sort_no/move", name="admin_product_category_sort_no_move", methods={"POST"})
     */
    public function moveSortNo(Request $request, CacheUtil $cacheUtil)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new BadRequestHttpException();
        }

        if ($this->isTokenValid()) {
            $sortNos = $request->request->all();
            foreach ($sortNos as $categoryId => $sortNo) {
                /* @var $Category \Eccube\Entity\Category */
                $Category = $this->categoryRepository
                    ->find($categoryId);
                $Category->setSortNo($sortNo);
                $this->entityManager->persist($Category);
            }
            $this->entityManager->flush();

            $cacheUtil->clearDoctrineCache();

            return new Response('Successful');
        }
    }

    /**
     * カテゴリCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/category/export", name="admin_product_category_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_CATEGORY);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->categoryRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Category \Eccube\Entity\Category */
                $Category = $entity;
                // CSV出力項目と合致するデータを取得.
                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();
                foreach ($Csvs as $Csv) {
                    $ExportCsvRow->setData($csvService->getData($Csv, $Category));

                    $event = new EventArgs(
                        [
                            'csvService' => $csvService,
                            'Csv' => $Csv,
                            'Category' => $Category,
                            'ExportCsvRow' => $ExportCsvRow,
                        ],
                        $request
                    );
                    $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_PRODUCT_CATEGORY_CSV_EXPORT, $event);

                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'category_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('カテゴリCSV出力ファイル名', [$filename]);

        return $response;
    }

        /**
     * @Route("/%eccube_admin_route%/product/category/image/add", name="admin_category_image_add", methods={"POST"})
     */
    public function addImage(Request $request)
    {
        if (!$request->isXmlHttpRequest() && $this->isTokenValid()) {
            throw new BadRequestHttpException();
        }

        $images = $request->files->get('admin_category');

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

}
