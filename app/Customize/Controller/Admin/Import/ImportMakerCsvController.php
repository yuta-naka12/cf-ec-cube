<?php

namespace Customize\Controller\Admin\Import;

use Customize\Entity\Product\ProductMaker;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\ProductMakerRepository;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportMakerCsvController extends AbstractCsvImportController
{
  
    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * @var ProductMakerRepository
     */
    protected $productMakerRepository;

    /**
     * ProductMakerController constructor.
     *
     * @param ProductMakerRepository $productMakerRepository
     *
     */
    public function __construct(
        ProductMakerRepository $productMakerRepository
    )
    {
        $this->productMakerRepository = $productMakerRepository;
    }

    /**
     * メーカー登録CSVアップロード
     *
     * @Route("/%eccube_admin_route%/product/maker_csv_import", name="admin_product_maker_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Import/maker.twig")
     */
    public function importMakerCsv(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
    
        $headers = $this->getMakerCsvHeader();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('メーカーCSV登録開始');
                    $data = $this->getImportData($formFile);
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
                        /** @var $Maker*/
                        $Maker = new ProductMaker();
                        if (isset($row[$headerByKey['id']]) && strlen($row[$headerByKey['id']]) > 0) {
                            if (!preg_match('/^\d+$/', $row[$headerByKey['id']])) {
                                $this->addErrors(($data->key() + 1).'行目のIDが存在しません。');
    
                                return $this->renderWithError($form, $headers);
                            }
                            $Maker = $this->productMakerRepository->find($row[$headerByKey['id']]);
                            if (!$Maker) {
                                $this->addErrors(($data->key() + 1).'行目の更新対象のIDが存在しません。新規登録の場合は、IDの値を空で登録してください。');
    
                                return $this->renderWithError($form, $headers);
                            }
                        }
    
                        if (!isset($row[$headerByKey['name']]) || StringUtil::isBlank($row[$headerByKey['name']])) {
                            $this->addErrors(($data->key() + 1).'行目のメーカーが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setName(StringUtil::trimAll($row[$headerByKey['name']]));
                        }
                        
                        if (!isset($row[$headerByKey['maker_name']]) || StringUtil::isBlank($row[$headerByKey['maker_name']])) {
                            $this->addErrors(($data->key() + 1).'行目のメーカー名が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setMakerName(StringUtil::trimAll($row[$headerByKey['maker_name']]));
                        }

                        if (!isset($row[$headerByKey['maker_name_2']]) || StringUtil::isBlank($row[$headerByKey['maker_name_2']])) {
                            $this->addErrors(($data->key() + 1).'行目のメーカー名2が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setMakerName2(StringUtil::trimAll($row[$headerByKey['maker_name_2']]));
                        }

                        if (!isset($row[$headerByKey['link']]) || StringUtil::isBlank($row[$headerByKey['link']])) {
                            $this->addErrors(($data->key() + 1).'行目のリンクが設定されていません。');
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setLink(StringUtil::trimAll($row[$headerByKey['link']]));
                        }

                        if (!isset($row[$headerByKey['comment']]) || StringUtil::isBlank($row[$headerByKey['comment']])) {
                            $this->addErrors(($data->key() + 1).'行目のコメントが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setComment(StringUtil::trimAll($row[$headerByKey['comment']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_1']]) || StringUtil::isBlank($row[$headerByKey['free_comment_1']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント１が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setFreeComment1(StringUtil::trimAll($row[$headerByKey['free_comment_1']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_2']]) || StringUtil::isBlank($row[$headerByKey['free_comment_2']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント２が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Maker->setFreeComment2(StringUtil::trimAll($row[$headerByKey['free_comment_2']]));
                        }
    
                        if ($this->hasErrors()) {
                            return $this->renderWithError($form, $headers);
                        }
                        $this->entityManager->persist($Maker);
                        $this->productMakerRepository->save($Maker);
                    }
    
                    $this->entityManager->getConnection()->commit();
                    log_info('メーカーCSV登録完了');
                    $message = 'admin.common.csv_upload_complete';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }
    
        return $this->renderWithError($form, $headers);
    }

    /**
     * メーカーCSVヘッダー定義
     */
    protected function getMakerCsvHeader()
    {
        return [
            trans('admin.product.maker_csv_import.id') => [
                'id' => 'id',
                'description' => 'admin.product.maker_csv_import.id.description',
                'required' => false,
            ],
            trans('admin.product.maker_csv_import.name') => [
                'id' => 'name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.maker_csv_import.maker_name') => [
                'id' => 'maker_name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.maker_csv_import.maker_name_2') => [
                'id' => 'maker_name_2',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.maker_csv_import.link') => [
                'id' => 'link',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.maker_csv_import.comment') => [
                'id' => 'comment',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.maker_csv_import.free_comment_1') => [
                'id' => 'free_comment_1',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.maker_csv_import.free_comment_2') => [
                'id' => 'free_comment_2',
                'description' => '',
                'required' => false,
            ],
        ];
    }


    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/product/maker_csv_template", name="admin_product_maker_csv_template", methods={"GET"})
     *
     * @param $type
     *
     * @return StreamedResponse
     */
    public function csvTemplate(Request $request)
    {
            $headers = $this->getMakerCsvHeader();
            $filename = 'maker.csv';

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
}