<?php

namespace Customize\Controller\Admin\Import;

use Customize\Entity\Product\ProductGift;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\ProductGiftRepository;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportGiftCsvController extends AbstractCsvImportController
{

    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * @var ProductGiftRepository
     */
    protected $productGiftRepository;

    /**
     * ProductGiftController constructor.
     *
     * @param ProductGiftRepository $productGiftRepository
     *
     */
    public function __construct(
        ProductGiftRepository $productGiftRepository
    )
    {
        $this->productGiftRepository = $productGiftRepository;
    }

    /**
     * ギフト登録CSVアップロード
     *
     * @Route("/%eccube_admin_route%/product/gift_csv_import", name="admin_product_gift_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Import/gift.twig")
     */
    public function importGiftCsv(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
    
        $headers = $this->getGiftCsvHeader();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('ギフトCSV登録開始');
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
                        /** @var $Gift*/
                        $Gift = new ProductGift();
                        if (isset($row[$headerByKey['id']]) && strlen($row[$headerByKey['id']]) > 0) {
                            if (!preg_match('/^\d+$/', $row[$headerByKey['id']])) {
                                $this->addErrors(($data->key() + 1).'行目のIDが存在しません。');
    
                                return $this->renderWithError($form, $headers);
                            }
                            $Gift = $this->productGiftRepository->find($row[$headerByKey['id']]);
                            if (!$Gift) {
                                $this->addErrors(($data->key() + 1).'行目の更新対象のIDが存在しません。新規登録の場合は、IDの値を空で登録してください。');
    
                                return $this->renderWithError($form, $headers);
                            }
                        }
    
                        if (!isset($row[$headerByKey['name']]) || StringUtil::isBlank($row[$headerByKey['name']])) {
                            $this->addErrors(($data->key() + 1).'行目のギフトが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Gift->setName(StringUtil::trimAll($row[$headerByKey['name']]));
                        }
                        
                        if (!isset($row[$headerByKey['gift_name']]) || StringUtil::isBlank($row[$headerByKey['gift_name']])) {
                            $this->addErrors(($data->key() + 1).'行目のギフト名が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Gift->setGiftName(StringUtil::trimAll($row[$headerByKey['gift_name']]));
                        }

                        if (!isset($row[$headerByKey['comment']]) || StringUtil::isBlank($row[$headerByKey['comment']])) {
                            $this->addErrors(($data->key() + 1).'行目のコメントが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Gift->setComment(StringUtil::trimAll($row[$headerByKey['comment']]));
                        }

                        if (!isset($row[$headerByKey['note']]) || StringUtil::isBlank($row[$headerByKey['note']])) {
                            $this->addErrors(($data->key() + 1).'行目の備考（内部用）が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Gift->setNote(StringUtil::trimAll($row[$headerByKey['note']]));
                        }
    
                        if ($this->hasErrors()) {
                            return $this->renderWithError($form, $headers);
                        }
                        $this->entityManager->persist($Gift);
                        $this->productGiftRepository->save($Gift);
                    }
    
                    $this->entityManager->getConnection()->commit();
                    log_info('ギフトCSV登録完了');
                    $message = 'admin.common.csv_upload_complete';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }
    
        return $this->renderWithError($form, $headers);
    }

    /**
     * ギフトCSVヘッダー定義
     */
    protected function getGiftCsvHeader()
    {
        return [
            trans('admin.product.gift_csv_import.id') => [
                'id' => 'id',
                'description' => 'admin.product.gift_csv_import.id.description',
                'required' => false,
            ],
            trans('admin.product.gift_csv_import.name') => [
                'id' => 'name',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.gift_csv_import.gift_name') => [
                'id' => 'gift_name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.gift_csv_import.comment') => [
                'id' => 'comment',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.gift_csv_import.note') => [
                'id' => 'note',
                'description' => '',
                'required' => false,
            ],
        ];
    }


    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/product/gift_csv_template", name="admin_product_gift_csv_template", methods={"GET"})
     *
     * @param $type
     *
     * @return StreamedResponse
     */
    public function csvTemplate(Request $request)
    {
            $headers = $this->getGiftCsvHeader();
            $filename = 'gift.csv';

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