<?php

namespace Customize\Controller\Admin\Import;

use Customize\Entity\Master\MtbAddress;
use Customize\Entity\Product\ProductBrand;
use Customize\Repository\Admin\Master\MtbAddressRepository;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\ProductBrandRepository;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportAddressCsvController extends AbstractCsvImportController
{

    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * ImportAddressCsvController constructor.
     *
     * @param MtbAddressRepository $mtbAddressRepository
     *
     */
    public function __construct(
        MtbAddressRepository $mtbAddressRepository
    )
    {
        $this->mtbAddressRepository = $mtbAddressRepository;
    }

    /**
     * @var MtbAddressRepository
     */
    protected $mt;

    /**
     * 住所マスタ登録CSVアップロード
     *
     * @Route("/%eccube_admin_route%/product/address_csv_import", name="admin_mtb_address_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Import/address.twig")
     */
    public function importBrandCsv(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();

        $headers = $this->getBrandCsvHeader();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('住所マスタCSV登録開始');
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
                        /** @var $Address*/
                        $Address = new MtbAddress();
                        if (isset($row[$headerByKey['dc_code']]) && strlen($row[$headerByKey['dc_code']]) > 0) {
//                            $Address = $this->mtbAddressRepository->find($row[$headerByKey['dc_code']]);
//                            if (!$Address) {
//                                $this->addErrors(($data->key() + 1).'行目の更新対象のIDが存在しません。新規登録の場合は、IDの値を空で登録してください。');
//                                return $this->renderWithError($form, $headers);
//                            }
                        }

                        if (!isset($row[$headerByKey['dc_code']]) || StringUtil::isBlank($row[$headerByKey['dc_code']])) {
                            $this->addErrors(($data->key() + 1).'行目のDCコードが設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setDcCode(StringUtil::trimAll($row[$headerByKey['dc_code']]));
                        }

                        if (!isset($row[$headerByKey['name']]) || StringUtil::isBlank($row[$headerByKey['name']])) {
                            $this->addErrors(($data->key() + 1).'行目の住所が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setName(StringUtil::trimAll($row[$headerByKey['name']]));
                        }

                        if (!isset($row[$headerByKey['absence_rate']]) || StringUtil::isBlank($row[$headerByKey['absence_rate']])) {
                            $this->addErrors(($data->key() + 1).'行目の留守宅率が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setAbsenceRate(StringUtil::trimAll($row[$headerByKey['absence_rate']]));
                        }

                        if (!isset($row[$headerByKey['delivery_week_1']]) || StringUtil::isBlank($row[$headerByKey['delivery_week_1']])) {
                            $this->addErrors(($data->key() + 1).'行目の配送週1が設定されていません。');
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setDeliveryWeek1(StringUtil::trimAll($row[$headerByKey['delivery_week_1']]));
                        }

                        if (!isset($row[$headerByKey['delivery_week_2']]) || StringUtil::isBlank($row[$headerByKey['delivery_week_2']])) {
                            $this->addErrors(($data->key() + 1).'行目の配送週2が設定されていません。');
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setDeliveryWeek2(StringUtil::trimAll($row[$headerByKey['delivery_week_2']]));
                        }

                        if (!isset($row[$headerByKey['calculation_item']]) || StringUtil::isBlank($row[$headerByKey['calculation_item']])) {
                            $this->addErrors(($data->key() + 1).'行目の計算項目が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setCalculationItem(StringUtil::trimAll($row[$headerByKey['calculation_item']]));
                        }

                        if (!isset($row[$headerByKey['delivery_index']]) || StringUtil::isBlank($row[$headerByKey['delivery_index']])) {
                            $this->addErrors(($data->key() + 1).'行目の配送順序が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setDeliveryIndex(StringUtil::trimAll($row[$headerByKey['delivery_index']]));
                        }

                        if (!isset($row[$headerByKey['driver_code']]) || StringUtil::isBlank($row[$headerByKey['driver_code']])) {
                            $this->addErrors(($data->key() + 1).'行目の配送者コードが設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setDriverCode(StringUtil::trimAll($row[$headerByKey['driver_code']]));
                        }

                        if (!isset($row[$headerByKey['packing_district_area_code']]) || StringUtil::isBlank($row[$headerByKey['packing_district_area_code']])) {
                            $this->addErrors(($data->key() + 1).'行目の詰込地区コードが設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setPackingDistrictAreaCode(StringUtil::trimAll($row[$headerByKey['packing_district_area_code']]));
                        }

                        if (!isset($row[$headerByKey['warehouse_export_classification']]) || StringUtil::isBlank($row[$headerByKey['warehouse_export_classification']])) {
                            $this->addErrors(($data->key() + 1).'行目の倉庫出国分が設定されていません。');

                            return $this->renderWithError($form, $headers);
                        } else {
                            $Address->setWarehouseExportClassification(StringUtil::trimAll($row[$headerByKey['warehouse_export_classification']]));
                        }

                        if ($this->hasErrors()) {
                            return $this->renderWithError($form, $headers);
                        }
                        $this->entityManager->persist($Address);
                        $this->mtbAddressRepository->save($Address);
                    }

                    $this->entityManager->getConnection()->commit();
                    log_info('住所CSV登録完了');
                    $message = 'admin.common.csv_upload_complete';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }

        return $this->renderWithError($form, $headers);
    }

    /**
     * 住所CSVヘッダー定義
     */
    protected function getBrandCsvHeader()
    {
        return [
            trans('admin.product.mtb_address_csv_import.dc_code') => [
                'id' => 'dc_code',
                'description' => 'admin.product.mtb_address_csv_import.id.description',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.course_code') => [
                'id' => 'course_code',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.name') => [
                'id' => 'name',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.absence_rate') => [
                'id' => 'absence_rate',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.delivery_week_1') => [
                'id' => 'delivery_week_1',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.delivery_week_2') => [
                'id' => 'delivery_week_2',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.calculation_item') => [
                'id' => 'calculation_item',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.delivery_index') => [
                'id' => 'delivery_index',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.driver_code') => [
                'id' => 'driver_code',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.packing_district_area_code') => [
                'id' => 'packing_district_area_code',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.person_in_charge') => [
                'id' => 'person_in_charge',
                'description' => '',
                'required' => true,
            ],
            trans('admin.product.mtb_address_csv_import.warehouse_export_classification') => [
                'id' => 'warehouse_export_classification',
                'description' => '',
                'required' => true,
            ],
        ];
    }


    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/product/brand_csv_template", name="admin_product_brand_csv_template", methods={"GET"})
     *
     * @param $type
     *
     * @return StreamedResponse
     */
    public function csvTemplate(Request $request)
    {
        $headers = $this->getBrandCsvHeader();
        $filename = 'brand.csv';

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
