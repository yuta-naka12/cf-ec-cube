<?php

namespace Customize\Controller\Admin\Import;

use Customize\Entity\Product\ProductGenre;
use Customize\Entity\Product\ProductGenreDisplayMode;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\ProductGenreRepository;
use Eccube\Repository\ProductGenreDisplayModeRepository;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\Validator\Constraints\Length;

class ImportGenreCsvController extends AbstractCsvImportController
{

    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * @var ProductGenreRepository
     */
    protected $productGenreRepository;
    /**
     * @var ProductGenreDisplayModeRepository
     */
    protected $productGenreDisplayModeRepository;

    /**
     * ProductGenreController constructor.
     *
     * @param ProductGenreRepository $productGenreRepository
     * @param ProductGenreDisplayModeRepository $productGenreRepository
     *
     */
    public function __construct(
        ProductGenreRepository $productGenreRepository,
        ProductGenreDisplayModeRepository $productGenreDisplayModeRepository
    )
    {
        $this->productGenreRepository = $productGenreRepository;
        $this->productGenreDisplayModeRepository = $productGenreDisplayModeRepository;
    }

    /**
     * ジャンル登録CSVアップロード
     *
     * @Route("/%eccube_admin_route%/product/genre_csv_import", name="admin_product_genre_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Import/genre.twig")
     */
    public function importGenreCsv(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
    
        $headers = $this->getGenreCsvHeader();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('ジャンルCSV登録開始');
                    $data = $this->getImportData($formFile);
                    // dd($data->key());
                    if ($data === false) {
                        $this->addErrors(trans('admin.common.csv_invalid_format'));
    
                        return $this->renderWithError($form, $headers, false);
                    }
    
                    $getId = function ($item) {
                        return $item['id'];
                    };
                    $requireHeader = array_keys(array_map($getId, array_filter($headers, function ($value) {
                        return $value['required'];
                    })));
    
                    $headerByKey = array_flip(array_map($getId, $headers));
    
                    $columnHeaders = $data->getColumnHeaders();
                    if (count(array_diff($requireHeader, $columnHeaders)) > 0) {
                        $this->addErrors(trans('admin.common.csv_invalid_format'));
    
                        return $this->renderWithError($form, $headers, false);
                    }
    
                    $size = count($data);
                    if ($size < 1) {
                        $this->addErrors(trans('admin.common.csv_invalid_no_data'));
    
                        return $this->renderWithError($form, $headers, false);
                    }
                    $this->entityManager->getConfiguration()->setSQLLogger(null);
                    $this->entityManager->getConnection()->beginTransaction();
                    // CSVファイルの登録処理
                    foreach ($data as $row) {
                        /** @var $Genre*/
                        $Genre = new ProductGenre();
                        if (isset($row[$headerByKey['id']]) && strlen($row[$headerByKey['id']]) > 0) {
                            if (!preg_match('/^\d+$/', $row[$headerByKey['id']])) {
                                $this->addErrors(($data->key() + 1).'行目のIDが存在しません。');
    
                                return $this->renderWithError($form, $headers);
                            }
                            $Genre = $this->productGenreRepository->find($row[$headerByKey['id']]);
                            if (!$Genre) {
                                $this->addErrors(($data->key() + 1).'行目の更新対象のIDが存在しません。新規登録の場合は、IDの値を空で登録してください。');
    
                                return $this->renderWithError($form, $headers);
                            }
                        }
    
                        if (!isset($row[$headerByKey['name']]) || StringUtil::isBlank($row[$headerByKey['name']])) {
                            $this->addErrors(($data->key() + 1).'行目のジャンルが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setName(StringUtil::trimAll($row[$headerByKey['name']]));
                        }

                        if (!isset($row[$headerByKey['genre_hierarchy']]) || StringUtil::isBlank($row[$headerByKey['genre_hierarchy']])) {
                            $this->addErrors(($data->key() + 1).'行目のジャンル階層が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setGenreHierarchy(StringUtil::trimAll($row[$headerByKey['genre_hierarchy']]));
                        }
                        
                        if (!isset($row[$headerByKey['genre_name']]) || StringUtil::isBlank($row[$headerByKey['genre_name']])) {
                            $this->addErrors(($data->key() + 1).'行目のジャンル名が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setGenreName(StringUtil::trimAll($row[$headerByKey['genre_name']]));
                        }
                        
                        if (!isset($row[$headerByKey['genre_name_2']]) || StringUtil::isBlank($row[$headerByKey['genre_name_2']])) {
                            $this->addErrors(($data->key() + 1).'行目のジャンル名2が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setGenreName2(StringUtil::trimAll($row[$headerByKey['genre_name_2']]));
                        }

                        if (!isset($row[$headerByKey['comment']]) || StringUtil::isBlank($row[$headerByKey['comment']])) {
                            $this->addErrors(($data->key() + 1).'行目のコメントが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setComment(StringUtil::trimAll($row[$headerByKey['comment']]));
                        }

                        if (!isset($row[$headerByKey['display_mode_default']]) || StringUtil::isBlank($row[$headerByKey['display_mode_default']])) {
                            $this->addErrors(($data->key() + 1).'行目の商品表示モードデフォルトが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setDisplayModeDefault(StringUtil::trimAll($row[$headerByKey['display_mode_default']]));
                        }

                        if (!isset($row[$headerByKey['free_space_top']]) || StringUtil::isBlank($row[$headerByKey['free_space_top']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリースペース上部が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setFreeSpaceTop(StringUtil::trimAll($row[$headerByKey['free_space_top']]));
                        }

                        if (!isset($row[$headerByKey['free_space_bottom']]) || StringUtil::isBlank($row[$headerByKey['free_space_bottom']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリースペース下部が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setFreeSpaceBottom(StringUtil::trimAll($row[$headerByKey['free_space_bottom']]));
                        }

                        if (!isset($row[$headerByKey['status']]) || StringUtil::isBlank($row[$headerByKey['status']])) {
                            $this->addErrors(($data->key() + 1).'行目の状態が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Genre->setStatus(StringUtil::trimAll($row[$headerByKey['status']]));
                        }

                        $this->registerProductGenreDisplayMode($row, $Genre, $data, $headerByKey);
                        
                        if ($this->hasErrors()) {
                            return $this->renderWithError($form, $headers);
                        }


                        $this->entityManager->persist($Genre);
                        $this->productGenreRepository->save($Genre);
                    }
    
                    $this->entityManager->getConnection()->commit();
                    log_info('ジャンルCSV登録完了');
                    $message = 'admin.common.csv_upload_complete';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }
    
        return $this->renderWithError($form, $headers);
    }

    /**
     * ジャンルCSVヘッダー定義
     */
    protected function getGenreCsvHeader()
    {
        return [
            trans('admin.product.genre_csv_import.id') => [
                'id' => 'id',
                'description' => 'admin.product.genre_csv_import.id.description',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.name') => [
                'id' => 'name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.genre_csv_import.genre_hierarchy') => [
                'id' => 'genre_hierarchy',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.genre_csv_import.genre_name') => [
                'id' => 'genre_name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.genre_csv_import.genre_name_2') => [
                'id' => 'genre_name_2',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.comment') => [
                'id' => 'comment',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.display_mode_default') => [
                'id' => 'display_mode_default',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.free_space_top') => [
                'id' => 'free_space_top',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.free_space_bottom') => [
                'id' => 'free_space_bottom',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.status') => [
                'id' => 'status',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.genre_csv_import.display_mode') => [
                'id' => 'display_mode',
                'description' => 'admin.product.genre_csv_import.display_mode.description',
                'required' => true,
            ],
        ];
    }


    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/product/genre_csv_template", name="admin_product_genre_csv_template", methods={"GET"})
     *
     * @param $type
     *
     * @return StreamedResponse
     */
    public function csvTemplate(Request $request)
    {
            $headers = $this->getGenreCsvHeader();
            $filename = 'genre.csv';

        return $this->sendTemplateResponse($request, array_keys($headers), $filename);
    }


    /**
     * 登録、更新時のエラー画面表示
     */
    protected function addErrors($message)
    {
        $this->errors[] = $message;
    }

    /**
     * @return array
     */
    protected function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return boolean
     */
    protected function hasErrors()
    {
        return count($this->getErrors()) > 0;
    }

    /**
     * 登録、更新時のエラー画面表示
     *
     * @param FormInterface $form
     * @param array $headers
     * @param bool $rollback
     *
     * @return array
     *
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function renderWithError($form, $headers, $rollback = true)
    {
        if ($this->hasErrors()) {
            if ($rollback) {
                $this->entityManager->getConnection()->rollback();
            }
        }

        $this->removeUploadedFile();

        if ($this->isSplitCsv) {
            return $this->json([
                'success' => !$this->hasErrors(),
                'success_message' => trans('admin.common.csv_upload_line_success', [
                    '%from%' => $this->convertLineNo(2),
                    '%to%' => $this->currentLineNo, ]),
                'errors' => $this->errors,
                'error_message' => trans('admin.common.csv_upload_line_error', [
                    '%from%' => $this->convertLineNo(2), ]),
            ]);
        }

        return [
            'form' => $form->createView(),
            'headers' => $headers,
            'errors' => $this->errors,
        ];
    }

    protected function convertLineNo($currentLineNo)
    {
        if ($this->isSplitCsv) {
            return ($this->eccubeConfig['eccube_csv_split_lines']) * ($this->csvFileNo - 1) + $currentLineNo;
        }

        return $currentLineNo;
    }

    protected function registerProductGenreDisplayMode($row, $Genre, $data, $headerByKey)
    {
        if (empty($row[$headerByKey['display_mode']])) {
            $this->addErrors(($data->key() + 1).'行目の商品表示モードに空欄が含まれています。');
        }

        if (StringUtil::isNotBlank($row[$headerByKey['display_mode']])) {
            // 商品表示モードの削除
            $hasDisplayModes = $Genre->getProductGenreDisplayMode();
            foreach ($hasDisplayModes as $hasDisplayMode) {
                $Genre->removeProductGenreDisplayMode($hasDisplayMode);
                $this->entityManager->remove($hasDisplayMode);
            }

            // 商品表示モードの登録
            $displayModeNames = array_filter(explode('、', $row[$headerByKey['display_mode']]));

            foreach ($displayModeNames as $displayModeName) {
                $matchedDisplayMode = $this->productGenreDisplayModeRepository->findBy(['name' => $displayModeName]);
                if (!empty($matchedDisplayMode)) {
                    $displayModes[] = $matchedDisplayMode[0];
                } else {
                    $this->addErrors(($data->key() + 1).'行目の商品表示モードに登録されていない表示モードが含まれています。');
                }
            }
            // 登録処理
            foreach ($displayModes as $displayMode) {
                $displayMode->addProductGenre($Genre);
                $Genre->addProductGenreDisplayMode($displayMode);
                $this->entityManager->persist($displayMode);
                $this->entityManager->persist($Genre);
            }
        }
    }
}

