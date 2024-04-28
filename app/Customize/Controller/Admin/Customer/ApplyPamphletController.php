<?php

namespace Customize\Controller\Admin\Customer;

use Customize\Entity\Customer\ApplyPamphlet;
use Customize\Form\Type\Admin\Customer\ApplyPamphletType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchProductBrandType;
use Eccube\Repository\ApplyPamphletRepository;
use Eccube\Repository\Master\CsvTypeRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Service\CsvExportService;
use Eccube\Util\FormUtil;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplyPamphletController extends AbstractController
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
     * @var ApplyPamphletRepository
     */
    protected $applyPamphletRepository;
    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * ApplyPamphletController constructor.
     *
     * @param ApplyPamphletRepository $applyPamphletRepository
     * @param PageMaxRepository $pageMaxRepository
     * @param CsvExportService $csvExportService
     * @param CsvTypeRepository $csvTypeRepository
     *
     */
    public function __construct(
        CsvExportService $csvExportService,
        ApplyPamphletRepository $applyPamphletRepository,
        PageMaxRepository $pageMaxRepository,
        CsvTypeRepository $csvTypeRepository
    ) {
        $this->csvExportService = $csvExportService;
        $this->applyPamphletRepository = $applyPamphletRepository;
        $this->pageMaxRepository = $pageMaxRepository;
        $this->csvTypeRepository = $csvTypeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/apply/pamphlet", name="admin_apply_pamphlet", methods={"GET", "PUT"})
     * @Template("@admin/Customer/ApplyPamphlet/pamphlet.twig")
     */
    public function index(Request $request)
    {
        $applicants = $this->applyPamphletRepository->findBy([], ['sort_no' => 'DESC']);

        $builder = $this->formFactory->createBuilder(ApplyPamphletType::class);

        $form = $builder->getForm();

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

        return [
            'form' => $form->createView(),
            'Applicants' => $applicants,
            'pageMaxis' => $pageMaxis,
            'page_count' => $page_count,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/apply/pamphlet/new", name="admin_apply_pamphlet_new", methods={"GET", "POST"})
     * @Template("@admin/Customer/ApplyPamphlet/pamphlet_edit.twig")
     */
    public function create(Request $request)
    {
        $applicant = new ApplyPamphlet();
        $builder = $this->formFactory
            ->createBuilder(ApplyPamphletType::class, $applicant);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->applyPamphletRepository->save($applicant);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'applicant' => $applicant
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');
            return $this->redirectToRoute('admin_apply_pamphlet_edit', ['id' => $applicant->getId()]);
        }

        return [
            'form' => $form->createView(),
            'applicant' => $applicant,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/apply/pamphlet/{id}/edit", requirements={"id" = "\d+"}, name="admin_apply_pamphlet_edit", methods={"GET", "POST"})
     * @Template("@admin/Customer/ApplyPamphlet/pamphlet_edit.twig")
     */
    public function edit(Request $request, ApplyPamphlet $applicant)
    {
        $builder = $this->formFactory
            ->createBuilder(ApplyPamphletType::class, $applicant);

        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->applyPamphletRepository->save($applicant);
            $event = new EventArgs(
                [
                    'form' => $form,
                    'applicant' => $applicant,
                ],
                $request
            );

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_apply_pamphlet_edit', ['id' => $applicant->getId()]);
        }

        return [
            'form' => $form->createView(),
            'applicant' => $applicant,
        ];
    }

    /**
     * @Route("/%eccube_admin_route%/apply/pamphlet/{id}/up", requirements={"id" = "\d+"}, name="admin_apply_pamphlet_up", methods={"PUT"})
     */
    public function up(Request $request, ApplyPamphlet $applicant)
    {
        $this->isTokenValid();

        try {
            $this->applyPamphletRepository->up($applicant);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$applicant->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_apply_pamphlet');
    }

    /**
     * @Route("/%eccube_admin_route%/apply/pamphlet/{id}/down", requirements={"id" = "\d+"}, name="admin_apply_pamphlet_down", methods={"PUT"})
     */
    public function down(Request $request, ApplyPamphlet $applicant)
    {
        $this->isTokenValid();

        try {
            $this->applyPamphletRepository->down($applicant);

            $this->addSuccess('admin.common.move_complete', 'admin');
        } catch (\Exception $e) {
            log_error('メンバー表示順更新エラー', [$applicant->getId(), $e]);

            $this->addError('admin.common.move_error', 'admin');
        }

        return $this->redirectToRoute('admin_apply_pamphlet');
    }

    /**
     * @Route("/%eccube_admin_route%/apply/pamphlet/{id}/delete", requirements={"id" = "\d+"}, name="admin_apply_pamphlet_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ApplyPamphlet $applicant)
    {
        $this->isTokenValid();

        log_info('メンバー削除開始', [$applicant->getId()]);

        try {
            $this->applyPamphletRepository->delete($applicant);

            $event = new EventArgs(
                [
                    'applicant' => $applicant,
                ],
                $request
            );

            $this->addSuccess('admin.common.delete_complete', 'admin');

            log_info('メンバー削除完了', [$applicant->getId()]);
        } catch (ForeignKeyConstraintViolationException $e) {
            log_info('メンバー削除エラー', [$applicant->getId()]);

            $message = trans('admin.common.delete_error_foreign_key', ['%name%' => $applicant->getName01()]);
            $this->addError($message, 'admin');
        } catch (\Exception $e) {
            log_info('メンバー削除エラー', [$applicant->getId(), $e]);

            $message = trans('admin.common.delete_error');
            $this->addError($message, 'admin');
        }

        return $this->redirectToRoute('admin_apply_pamphlet');
    }

    /**
     * ブランドCSVの出力.
     *
     * @Route("/%eccube_admin_route%/product/brand/export", name="admin_product_brand_export", methods={"GET"})
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
            $this->csvExportService->initCsvType(CsvType::CSV_TYPE_BRAND);

            // ヘッダ行の出力.
            $this->csvExportService->exportHeader();

            // ブランドデータ検索用のクエリビルダを取得.
            $qb = $this->applyPamphletRepository
                ->createQueryBuilder('c')
                ->orderBy('c.sort_no', 'DESC');

            // データ行の出力.
            $this->csvExportService->setExportQueryBuilder($qb);

            $this->csvExportService->exportData(function ($entity, $csvService) use ($request) {
                $Csvs = $csvService->getCsvs();

                /** @var $applicant \Customize\Entity\Product\ProductBrand */
                $applicant = $entity;

                $ExportCsvRow = new \Eccube\Entity\ExportCsvRow();

                // CSV出力項目と合致するデータを取得.
                foreach ($Csvs as $Csv) {
                    // ブランドデータを検索.
                    $ExportCsvRow->setData($csvService->getData($Csv, $applicant));

                    $ExportCsvRow->pushData();
                }

                //$row[] = number_format(memory_get_usage(true));
                // 出力.
                $csvService->fputcsv($ExportCsvRow->getRow());
            });
        });

        $now = new \DateTime();
        $filename = 'brand_' . $now->format('YmdHis') . '.csv';
        $response->headers->set('Content-Type', 'application/octet-stream');
        $response->headers->set('Content-Disposition', 'attachment; filename=' . $filename);
        $response->send();

        log_info('ブランドCSV出力ファイル名', [$filename]);

        return $response;
    }
}