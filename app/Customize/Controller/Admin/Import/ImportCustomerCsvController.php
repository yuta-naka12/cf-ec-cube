<?php

namespace Customize\Controller\Admin\Import;

use DateTime;
use Eccube\Entity\Customer;
use Eccube\Controller\Admin\AbstractCsvImportController;
use Eccube\Form\Type\Admin\CsvImportType;
use Eccube\Repository\CustomerRepository;
use Eccube\Util\CacheUtil;
use Eccube\Util\StringUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportCustomerCsvController extends AbstractCsvImportController
{
    private $errors = [];

    protected $isSplitCsv = false;

    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * CustomerController constructor. 
     *
     * @param CustomerRepository $customerRepository
     *
     */
    public function __construct(
        CustomerRepository $customerRepository
    )
    {
        $this->customerRepository = $customerRepository;
    }

    /**
     * ブランド登録CSVアップロード
     *
     * @Route("/%eccube_admin_route%/product/customer_csv_import", name="admin_product_customer_csv_import", methods={"GET", "POST"})
     * @Template("@admin/Import/customer.twig")
     */
    public function importCustomerCsv(Request $request, CacheUtil $cacheUtil)
    {
        $form = $this->formFactory->createBuilder(CsvImportType::class)->getForm();
    
        $headers = $this->getCustomerCsvHeader();
        if ('POST' === $request->getMethod()) {
            $form->handleRequest($request);
            // dd(request());
            if ($form->isValid()) {
                $formFile = $form['import_file']->getData();
                if (!empty($formFile)) {
                    log_info('ブランドCSV登録開始');
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
                        /** @var $Customer*/
                        $Customer = new Customer();
                        if (isset($row[$headerByKey['id']]) && strlen($row[$headerByKey['id']]) > 0) {
                            if (!preg_match('/^\d+$/', $row[$headerByKey['id']])) {
                                $this->addErrors(($data->key() + 1).'行目のIDが存在しません。');
    
                                return $this->renderWithError($form, $headers);
                            }
                            // dd($this->customerRepository);
                            $Customer = $this->customerRepository->find($row[$headerByKey['id']]);
                            
                            if (!$Customer) {
                                $this->addErrors(($data->key() + 1).'行目の更新対象のIDが存在しません。新規登録の場合は、IDの値を空で登録してください。');
    
                                return $this->renderWithError($form, $headers);
                            }
                        }


                        if (!isset($row[$headerByKey['name01']]) || StringUtil::isBlank($row[$headerByKey['name01']])) {
                            $this->addErrors(($data->key() + 1).'行目のブランドが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Customer->setName01(StringUtil::trimAll($row[$headerByKey['name01']]));
                        }

                        if (!isset($row[$headerByKey['name02']]) || StringUtil::isBlank($row[$headerByKey['name02']])) {
                            $this->addErrors(($data->key() + 1).'行目のブランドが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Customer->setName02(StringUtil::trimAll($row[$headerByKey['name02']]));
                        }
                        

                        if (!isset($row[$headerByKey['email']]) || StringUtil::isBlank($row[$headerByKey['email']])) {
                            $this->addErrors(($data->key() + 1).'行目のコメントが設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Customer->setEmail(StringUtil::trimAll($row[$headerByKey['email']]));
                        }

                        if (!isset($row[$headerByKey['password']]) || StringUtil::isBlank($row[$headerByKey['password']])) {
                            $this->addErrors(($data->key() + 1).'行目のフリーコメント２が設定されていません。');
    
                            return $this->renderWithError($form, $headers);
                        } else {
                            $Customer->setPassword(StringUtil::trimAll($row[$headerByKey['password']]));
                        }

                        
                        $Customer->setAddr01(StringUtil::trimAll($row[$headerByKey['addr01']]));
                        $Customer->setAddr02(StringUtil::trimAll($row[$headerByKey['addr02']]));
                        $Customer->setKana01(StringUtil::trimAll($row[$headerByKey['kana01']]));
                        $Customer->setKana02(StringUtil::trimAll($row[$headerByKey['kana02']]));
                        $Customer->setAddr03(StringUtil::trimAll($row[$headerByKey['addr03']]));
                        $Customer->setCompanyName(StringUtil::trimAll($row[$headerByKey['company_name']]));
                        $Customer->setPostalCode(StringUtil::trimAll($row[$headerByKey['postal_code']]));
                        $Customer->setPhoneNumber(StringUtil::trimAll($row[$headerByKey['phone_number']]));
                        $Customer->setBirth(DateTime::createFromFormat('Y-m-d H:i:s', $row[$headerByKey['birth']]));
                        $Customer->setSalt(StringUtil::trimAll($row[$headerByKey['salt']]));
                        $Customer->setSecretKey(StringUtil::trimAll($row[$headerByKey['secret_key']]));
                        $Customer->setFirstBuyDate(DateTime::createFromFormat('Y-m-d H:i:s',$row[$headerByKey['first_buy_date']]));
                        $Customer->setLastBuyDate(DateTime::createFromFormat('Y-m-d H:i:s',$row[$headerByKey['last_buy_date']]));
                        $Customer->setBuyTimes(StringUtil::trimAll($row[$headerByKey['buy_times']]));
                        $Customer->setBuyTotal(StringUtil::trimAll($row[$headerByKey['buy_total']]));
                        $Customer->setNote(StringUtil::trimAll($row[$headerByKey['note']]));
                        // $Customer->setJob(StringUtil::trimAll($row[$headerByKey['Job']]));


                        $sex = StringUtil::trimAll($row[$headerByKey['Sex']]);
                        $Sex = null;
                        switch ($sex) {
                            case 'Male':
                                $Sex = $this->getDoctrine()->getRepository(\Eccube\Entity\Master\Sex::class)->findOneBy(['id' => 1]);
                                break;
                            case 'Female':
                                $Sex = $this->getDoctrine()->getRepository(\Eccube\Entity\Master\Sex::class)->findOneBy(['id' => 2]);
                                break;
                            default:
                                // Handle the case where the gender is not Male or Female.
                                // For example, you could throw an exception or set the gender to a default value.
                                break;
                        }
                        $Customer->setSex($Sex);
 

                        $countryName = StringUtil::trimAll($row[$headerByKey['Country']]);
                        $country = $this->getDoctrine()->getRepository(\Eccube\Entity\Master\Country::class)->findOneBy(['name' => $countryName]);
                        if (!$country) {
                            // Handle the case where the country cannot be found.
                            // For example, you could throw an exception or set the country to a default value.
                            $Customer->setCountry("Kenya");
                        }
                        $Customer->setCountry($country);
 

                        $jobId = StringUtil::trimAll($row[$headerByKey['Job']]);
                        $job = $this->getDoctrine()->getRepository(\Eccube\Entity\Master\Job::class)->findOneBy(['id' => $jobId]);
                        if (!$country) {
                            // Handle the case where the country cannot be found.
                            // For example, you could throw an exception or set the country to a default value.
                            // $Customer->setCountry("Kenya");
                        }
                        $Customer->setJob($job);

                        // $Customer->setCountry(StringUtil::trimAll($row[$headerByKey['Country']]));



                        $Customer->setResetKey(StringUtil::trimAll($row[$headerByKey['reset_key']]));
                        $Customer->setResetExpire(DateTime::createFromFormat('Y-m-d H:i:s',$row[$headerByKey['reset_expire']]));
                        $Customer->setPoint(StringUtil::trimAll($row[$headerByKey['point']]));
                        $Customer->setCreateDate(DateTime::createFromFormat('Y-m-d H:i:s',$row[$headerByKey['create_date']]));
                        $Customer->setUpdateDate(DateTime::createFromFormat('Y-m-d H:i:s',$row[$headerByKey['update_date']]));
                        // $Customer->addCustomerFavoriteProduct(StringUtil::trimAll($row[$headerByKey['CustomerFavoriteProducts']]));
                        // $Customer->addCustomerAddress(StringUtil::trimAll($row[$headerByKey['CustomerAddresses']]));
                        // $Customer->addOrder(StringUtil::trimAll($row[$headerByKey['Orders']]));
                       

                        // dd($Customer);
                        if ($this->hasErrors()) {
                            return $this->renderWithError($form, $headers);
                        }



                        function randomSymphonyChars($length) {
                            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                            $charactersLength = strlen($characters);
                            $randomString = '';
                            for ($i = 0; $i < $length; $i++) {
                              $randomString .= $characters[rand(0, $charactersLength - 1)];
                            }
                            return $randomString;
                          }
                        // TODO setup secret key 
                        // create random word

                        // set secret key
                        $Customer->setSecretKey(randomSymphonyChars(8));
                        
                        $this->entityManager->persist($Customer);
                        $this->entityManager->flush();
                        $this->customerRepository->save($Customer);
                    }
    
                    $this->entityManager->getConnection()->commit();
                    log_info('ブランドCSV登録完了');
                    $message = 'admin.common.csv_upload_complete';
                    $this->session->getFlashBag()->add('eccube.admin.success', $message);

                    $cacheUtil->clearDoctrineCache();
                }
            }
        }
    
        return $this->renderWithError($form, $headers);
    }

    /**
     * ブランドCSVヘッダー定義
     */
    protected function getCustomerCsvHeader()
    {
        return [
            trans('id') => [
                'id' => 'id',
                'description' => '',
                'required' => false,
            ],
            trans('name01') => [
                'id' => 'name01',
                'description' => '',
                'required' => true,
            ],
            trans('name02') => [
                'id' => 'name02',
                'description' => '',
                'required' => true,
            ],
            trans('kana01') => [
                'id' => 'kana01',
                'description' => '',
                'required' => false,
            ],
            trans('kana02') => [
                'id' => 'kana02',
                'description' => '',
                'required' => false,
            ],

            trans('company_name') => [
                'id' => 'company_name',
                'description' => '',
                'required' => false,
            ],
            trans('postal_code') => [
                'id' => 'postal_code',
                'description' => '',
                'required' => false,
            ],
            trans('addr01') => [
                'id' => 'addr01',
                'description' => '',
                'required' => false,
            ],
            trans('addr02') => [
                'id' => 'addr02',
                'description' => '',
                'required' => false,
            ],
            trans('addr03') => [
                'id' => 'addr03',
                'description' => '',
                'required' => false,
            ],
            trans('email') => [
                'id' => 'email',
                'description' => '',
                'required' => true,
            ],
            trans('phone_number') => [
                'id' => 'phone_number',
                'description' => '',
                'required' => false,
            ],
            trans('birth') => [
                'id' => 'birth',
                'description' => '',
                'required' => false,
            ],
            trans('password') => [
                'id' => 'password',
                'description' => '',
                'required' => true,
            ],
            trans('salt') => [
                'id' => 'salt',
                'description' => '',
                'required' => false,
            ],
            trans('secret_key') => [
                'id' => 'secret_key',
                'description' => '',
                'required' => false,
            ],
            trans('first_buy_date') => [
                'id' => 'first_buy_date',
                'description' => '',
                'required' => false,
            ],
            trans('last_buy_date') => [
                'id' => 'last_buy_date',
                'description' => '',
                'required' => false,
            ],
            trans('buy_times') => [
                'id' => 'buy_times',
                'description' => '',
                'required' => false,
            ],
            trans('buy_total') => [
                'id' => 'buy_total',
                'description' => '',
                'required' => false,
            ],
            trans('note') => [
                'id' => 'note',
                'description' => '',
                'required' => false,
            ],
            trans('reset_key') => [
                'id' => 'reset_key',
                'description' => '',
                'required' => false,
            ],
            trans('reset_expire') => [
                'id' => 'reset_expire',
                'description' => '',
                'required' => false,
            ],
            trans('point') => [
                'id' => 'point',
                'description' => '',
                'required' => false,
            ],
            trans('create_date') => [
                'id' => 'create_date',
                'description' => '',
                'required' => false,
            ],
            trans('update_date') => [
                'id' => 'update_date',
                'description' => '',
                'required' => false,
            ],
            trans('CustomerFavoriteProducts') => [
                'id' => 'CustomerFavoriteProducts',
                'description' => '',
                'required' => false,
            ],
            trans('CustomerAddresses') => [
                'id' => 'CustomerAddresses',
                'description' => '',
                'required' => false,
            ],
            trans('Orders') => [
                'id' => 'Orders',
                'description' => '',
                'required' => false,
            ],
            trans('Status') => [
                'id' => 'Status',
                'description' => '',
                'required' => false,
            ],
            trans('Sex') => [
                'id' => 'Sex',
                'description' => '',
                'required' => false,
            ],
            trans('Job') => [
                'id' => 'Job',
                'description' => '',
                'required' => false,
            ],
            trans('Country') => [
                'id' => 'Country',
                'description' => '',
                'required' => false,
            ],
            trans('Pref') => [
                'id' => 'Pref',
                'description' => '',
                'required' => false,
            ],
        ];
    }


    /**
     * アップロード用CSV雛形ファイルダウンロード
     *
     * @Route("/%eccube_admin_route%/product/customer_csv_template", name="admin_product_customer_csv_template", methods={"GET"})
     *
     * @param $type
     *
     * @return StreamedResponse
     */
    public function csvTemplate(Request $request)
    {
            $headers = $this->getCustomerCsvHeader();
            $filename = 'customer.csv';

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