<?php

namespace Customize\Controller\Admin\Import;

use Customize\Entity\Product\ProductEvent;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\CategoryRepository;
use Eccube\Repository\ProductEventRepository;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportEventCsvController extends AbstractCsvImportController
{

    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * @var ProductEventRepository
     */
    protected $productEventRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * ProductEventController constructor.
     *
     * @param ProductEventRepository $productEventRepository
     * @param CategoryRepository $categoryRepository
     *
     */
    public function __construct(
        ProductEventRepository $productEventRepository,
        CategoryRepository $categoryRepository
    )
    {
        $this->productEventRepository = $productEventRepository;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * イベント登録CSVアップロード
     *
     * @Route("/%eccube_admin_route%/product/event_csv_import", name="admin_product_event_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Import/event.twig")
     */
    public function importEventCsv(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
    
        $headers = $this->getEventCsvHeader();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('イベントCSV登録開始');
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
                        /** @var $Event*/
                        $Event = new ProductEvent();
                        if (isset($row[$headerByKey['id']]) && strlen($row[$headerByKey['id']]) > 0) {
                            if (!preg_match('/^\d+$/', $row[$headerByKey['id']])) {
                                $this->addErrors(($data->key() + 1).'行目のIDが存在しません。');
    
                                return $this->renderWithError($form, $headers);
                            }
                            $Event = $this->productEventRepository->find($row[$headerByKey['id']]);
                            if (!$Event) {
                                $this->addErrors(($data->key() + 1).'行目の更新対象のIDが存在しません。新規登録の場合は、IDの値を空で登録してください。');
    
                                return $this->renderWithError($form, $headers);
                            }
                        }
    
                        if (!isset($row[$headerByKey['event_id']]) || StringUtil::isBlank($row[$headerByKey['event_id']])) {
                            $this->addErrors(($data->key() + 1).'行目のイベントIDが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setEventId(StringUtil::trimAll($row[$headerByKey['event_id']]));
                        }
                        
                        $Category = null;
                        if (isset($row[$headerByKey['category_id']]) && StringUtil::isNotBlank($row[$headerByKey['category_id']])) {
                            if (!preg_match('/^\d+$/', $row[$headerByKey['category_id']])) {
                                $this->addErrors(($data->key() + 1).'行目のカテゴリIDが存在しません。');
                                
                                return $this->renderWithError($form, $headers);
                            }

                            /** @var $Category Category */
                            $Category = $this->categoryRepository->find($row[$headerByKey['category_id']]);
                            if (!$Category) {
                                $this->addErrors(($data->key() + 1).'行目のカテゴリIDが存在しません。');
                                
                                return $this->renderWithError($form, $headers);
                            }
                        }
                        $Event->setCategory($Category);
                        
                        if (!isset($row[$headerByKey['name']]) || StringUtil::isBlank($row[$headerByKey['name']])) {
                            $this->addErrors(($data->key() + 1).'行目のイベントが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setName(StringUtil::trimAll($row[$headerByKey['name']]));
                        }
                        
                        $startedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $row[$headerByKey['started_at']]);
                        if (!isset($row[$headerByKey['started_at']]) || StringUtil::isBlank($row[$headerByKey['started_at']])) {
                            $this->addErrors(($data->key() + 1).'行目のイベント開始日が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else if ($startedAt === false) {
                            $this->addErrors(($data->key() + 1). '行目のイベント開始日のフォーマットが異なります');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $startedAt->setTime(0,0,0);
                            $Event->setStartedAt($startedAt);
                        }

                        $endedAt = \DateTime::createFromFormat('Y-m-d H:i:s', $row[$headerByKey['ended_at']]);
                        if (!isset($row[$headerByKey['ended_at']]) || StringUtil::isBlank($row[$headerByKey['ended_at']])) {
                            $this->addErrors(($data->key() + 1).'行目のイベント終了日が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else if ($endedAt === false) {
                            $this->addErrors(($data->key() + 1). '行目のイベント終了日のフォーマットが異なります');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $endedAt->setTime(0,0,0);
                            $Event->setEndedAt($endedAt);
                        }

                        if (!isset($row[$headerByKey['link']]) || StringUtil::isBlank($row[$headerByKey['link']])) {
                            $this->addErrors(($data->key() + 1).'行目のリンクが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setLink(StringUtil::trimAll($row[$headerByKey['link']]));
                        }

                        if (!isset($row[$headerByKey['keyword']]) || StringUtil::isBlank($row[$headerByKey['keyword']])) {
                            $this->addErrors(($data->key() + 1).'行目のキーワードが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setKeyword(StringUtil::trimAll($row[$headerByKey['keyword']]));
                        }

                        if (!isset($row[$headerByKey['display_position']]) || StringUtil::isBlank($row[$headerByKey['display_position']])) {
                            $this->addErrors(($data->key() + 1).'行目のイベント表示位置が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setDisplayPosition(StringUtil::trimAll($row[$headerByKey['display_position']]));
                        }

                        if (!isset($row[$headerByKey['display_maximum_number']]) || StringUtil::isBlank($row[$headerByKey['display_maximum_number']])) {
                            $this->addErrors(($data->key() + 1).'行目の表示最大数が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setDisplayMaximumNumber(StringUtil::trimAll($row[$headerByKey['display_maximum_number']]));
                        }

                        if (!isset($row[$headerByKey['status']]) || StringUtil::isBlank($row[$headerByKey['status']])) {
                            $this->addErrors(($data->key() + 1).'行目の状態が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setStatus(StringUtil::trimAll($row[$headerByKey['status']]));
                        }

                        if (!isset($row[$headerByKey['comment']]) || StringUtil::isBlank($row[$headerByKey['comment']])) {
                            $this->addErrors(($data->key() + 1).'行目のコメントが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setComment(StringUtil::trimAll($row[$headerByKey['comment']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_1']]) || StringUtil::isBlank($row[$headerByKey['free_comment_1']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント１が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setFreeComment1(StringUtil::trimAll($row[$headerByKey['free_comment_1']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_2']]) || StringUtil::isBlank($row[$headerByKey['free_comment_2']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント２が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setFreeComment2(StringUtil::trimAll($row[$headerByKey['free_comment_2']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_3']]) || StringUtil::isBlank($row[$headerByKey['free_comment_3']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント３が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setFreeComment3(StringUtil::trimAll($row[$headerByKey['free_comment_3']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_4']]) || StringUtil::isBlank($row[$headerByKey['free_comment_4']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント４が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setFreeComment4(StringUtil::trimAll($row[$headerByKey['free_comment_4']]));
                        }

                        if (!isset($row[$headerByKey['free_comment_5']]) || StringUtil::isBlank($row[$headerByKey['free_comment_5']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント５が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Event->setFreeComment5(StringUtil::trimAll($row[$headerByKey['free_comment_5']]));
                        }

    
                        if ($this->hasErrors()) {
                            return $this->renderWithError($form, $headers);
                        }
                        $this->entityManager->persist($Event);
                        $this->productEventRepository->save($Event);
                    }
    
                    $this->entityManager->getConnection()->commit();
                    log_info('イベントCSV登録完了');
                    $message = 'admin.common.csv_upload_complete';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }
    
        return $this->renderWithError($form, $headers);
    }

    /**
     * イベントCSVヘッダー定義
     */
    protected function getEventCsvHeader()
    {
        return [
            trans('admin.product.event_csv_import.id') => [
                'id' => 'id',
                'description' => 'admin.product.event_csv_import.id.description',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.event_id') => [
                'id' => 'event_id',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.event_csv_import.name') => [
                'id' => 'name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.event_csv_import.category_id') => [
                'id' => 'category_id',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.category_id') => [
                'id' => 'category_id',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.started_at') => [
                'id' => 'started_at',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.event_csv_import.ended_at') => [
                'id' => 'ended_at',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.event_csv_import.link') => [
                'id' => 'link',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.keyword') => [
                'id' => 'keyword',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.display_position') => [
                'id' => 'display_position',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.display_maximum_number') => [
                'id' => 'display_maximum_number',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.status') => [
                'id' => 'status',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.comment') => [
                'id' => 'comment',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.free_comment_1') => [
                'id' => 'free_comment_1',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.free_comment_2') => [
                'id' => 'free_comment_2',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.free_comment_3') => [
                'id' => 'free_comment_3',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.free_comment_4') => [
                'id' => 'free_comment_4',
                'description' => '',
                'required' => false,
            ],
            trans('admin.product.event_csv_import.free_comment_5') => [
                'id' => 'free_comment_5',
                'description' => '',
                'required' => false,
            ],
        ];
    }


    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/product/event_csv_template", name="admin_product_event_csv_template", methods={"GET"})
     *
     * @param $type
     *
     * @return StreamedResponse
     */
    public function csvTemplate(Request $request)
    {
            $headers = $this->getEventCsvHeader();
            $filename = 'event.csv';

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