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

use Customize\Entity\Product\ProductMaker;
use Customize\Form\Type\Admin\Product\ProductMakerType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\ProductMakerRepository;
use Eccube\Repository\ProductMakerImageRepository;
use Eccube\Service\CsvExportService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;

class ProductMakerController extends AbstractController
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
     * @var ProductMakerRepository
     *
     */
    protected $productMakerRepository;

    /**
     * @var ProductMakerImageRepository
     */
    protected $productMakerImageRepository;
    /**
     * ProductMakerController constructor.
     *
     * @param ProductMakerRepository $productMakerRepository
     * @param ProductMakerImageRepository $productMakerImageRepository
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     */
    public function __construct(
        ProductMakerRepository $productMakerRepository,
        ProductMakerImageRepository $productMakerImageRepository,
        CsvExportService $csvExportService,
        CsvTypeRepository $csvTypeRepository
    ) {
        $this->productMakerRepository = $productMakerRepository;
        $this->productMakerImageRepository = $productMakerImageRepository;
        $this->csvExportService = $csvExportService;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker", name="admin_product_maker", methods={"GET", "PUT"})
     * @Template("@admin/Product/ProductMaker/maker.twig")
     */
    public function index(Request $request)
    {
        $ProductMakers = $this->productMakerRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder();

        $event = new EventArgs(
            [
                'builder' => $builder,
                'ProductMakers' => $ProductMakers,
            ],
            $request
        );

        $form = $builder->getForm();

        return [
            'form' => $form->createView(),
            'ProductMakers' => $ProductMakers,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker/new", name="admin_product_maker_new", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductMaker/maker_edit.twig")
     */
    public function create(Request $request)
    {
        $maker = new ProductMaker();
        $builder = $this->formFactory
            ->createBuilder(ProductMakerType::class, $maker);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $makerData = $form->getData();

            // 画像の登録
            $add_images = $form->get('add_images')->getData();
            foreach ($add_images as $add_image) {
                $ProductMakerImage = new \Customize\Entity\Product\ProductMakerImage();
                $ProductMakerImage
                    ->setFileName($add_image)
                    ->setProductMaker($makerData)
                    ->setSortNo(1);
                $makerData->addProductMakerImage($ProductMakerImage);
                $this->entityManager->persist($ProductMakerImage);

                // 移動
                $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $add_image);
                $file->move($this->eccubeConfig['eccube_save_image_dir']);
            };

            // 画像の削除
            $delete_images = $form->get('delete_images')->getData();
            $fs = new Filesystem();
            foreach ($delete_images as $delete_image) {
                $ProductMakerImage = $this->productMakerImageRepository->findOneBy([
                    'ProductMaker' => $makerData,
                    'file_name' => $delete_image,
                ]);

                if ($ProductMakerImage instanceof \ProductMakerImage) {
                    $makerData->removeProductMakerImage($ProductMakerImage);
                    $this->entityManager->remove($ProductMakerImage);
                    $this->entityManager->flush();

                    // 他に同じ画像を参照する商品がなければ画像ファイルを削除
                    if (!$this->productMakerImageRepository->findOneBy(['file_name' => $delete_image])) {
                        $fs->remove($this->eccubeConfig['eccube_save_image_dir'] . '/' . $delete_image);
                    }
                } else {
                    // 追加してすぐに削除した画像は、Entityに追加されない
                    $fs->remove($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $delete_image);
                }
            }

            $this->productMakerRepository->save($maker);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'maker' => $maker,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_product_maker_edit', ['id' => $maker->getId()]);
        }

        return [
            'form' => $form->createView(),
            'maker' => $maker,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker/{id}/edit", requirements={"id" = "\d+"}, name="admin_product_maker_edit", methods={"GET", "POST"})
     * @Template("@admin/Product/ProductMaker/maker_edit.twig")
     */
    public function edit(Request $request, ProductMaker $maker)
    {
        $hasMakerImages = $this->productMakerImageRepository->findBy(['ProductMaker' => $maker]);

        $builder = $this->formFactory
            ->createBuilder(ProductMakerType::class, $maker);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $makerData = $form->getData();
            // 画像の登録
            $add_images = $form->get('add_images')->getData();
            foreach ($add_images as $add_image) {
                $filePath = realpath('/var/www/html/html/upload/save_image' . '/' . $add_image);
                // すでに登録済みの写真は再登録しない
                if (!$filePath) {
                    $ProductMakerImage = new \Customize\Entity\Product\ProductMakerImage();
                    $ProductMakerImage
                    ->setFileName($add_image)
                    ->setProductMaker($makerData)
                    ->setSortNo(1);
                    $makerData->addProductMakerImage($ProductMakerImage);
                    $this->entityManager->persist($ProductMakerImage);

                    // 移動
                    $file = new File($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $add_image);
                    $file->move($this->eccubeConfig['eccube_save_image_dir']);
                }
            };

            // 画像の削除
            $delete_images = $form->get('delete_images')->getData();
            $fs = new Filesystem();
            foreach ($delete_images as $delete_image) {
                $ProductMakerImage = $this->productMakerImageRepository->findOneBy([
                    'ProductMaker' => $makerData,
                    'file_name' => $delete_image,
                ]);

                if ($ProductMakerImage instanceof ProductMakerImage) {
                    $makerData->removeProductMakerImage($ProductMakerImage);
                    $this->entityManager->remove($ProductMakerImage);
                    $this->entityManager->flush();

                    // 他に同じ画像を参照する商品がなければ画像ファイルを削除
                    if (!$this->productMakerImageRepository->findOneBy(['file_name' => $delete_image])) {
                        $fs->remove($this->eccubeConfig['eccube_save_image_dir'] . '/' . $delete_image);
                    }
                } else {
                    // 追加してすぐに削除した画像は、Entityに追加されない
                    $fs->remove($this->eccubeConfig['eccube_temp_image_dir'] . '/' . $delete_image);
                }
            }
            $this->productMakerRepository->save($maker);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'maker' => $maker,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_product_maker_edit', ['id' => $maker->getId()]);
        }

        return [
            'form' => $form->createView(),
            'maker' => $maker,
            'hasMakerImages' => $hasMakerImages,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker/{id}/up", requirements={"id" = "\d+"}, name="admin_product_maker_up", methods={"PUT"})
     */
    public function up(Request $request, ProductMaker $maker)
    {
        $this->isTokenValid();

        try {
            $this->productMakerRepository->up($maker);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$maker->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_maker');
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker/{id}/down", requirements={"id" = "\d+"}, name="admin_product_maker_down", methods={"PUT"})
     */
    public function down(Request $request, ProductMaker $maker)
    {
        $this->isTokenValid();

        try {
            $this->productMakerRepository->down($maker);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$maker->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_product_maker');
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker/{id}/delete", requirements={"id" = "\d+"}, name="admin_product_maker_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ProductMaker $maker)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$maker->getId()]);

        try {
            $this->productMakerRepository->delete($maker);

            $event = new EventArgs(
                [
                    'maker' => $maker,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$maker->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$maker->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $maker->getName()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$maker->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_product_maker');
    }

    /**
     * @Route("/%eccube_admin_route%/product/maker/image/add", name="admin_maker_image_add", methods={"POST"})
     */
    public function addImage(Request $request)
    {
        if (!$request->isXmlHttpRequest() && $this->isTokenValid()) {
            throw new BadRequestHttpException();
        }

        $images = $request->files->get('product_maker');

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
     * カテゴリCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/maker/export", name="admin_product_maker_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_MAKER);
            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            $qb = $this->productMakerRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);
            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $Maker \Customize\Entity\Product\ProductMaker */
                $Maker = $entity;

                // CSV出力項目と合致するデータを取得.
                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();
                foreach ($Csvs as $Csv) {
                    $ExportCsvRow->setData($csvService->getData($Csv, $Maker));
                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'maker_'.$now->format('YmdHis').'.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename='.$filename);
        $response->send();

        log_info('メーカーCSV出力ファイル名', [$filename]);

        return $response;
    }
}
