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

namespace Eccube\Controller\Admin\Customer;

use Customize\Entity\Master\CourseMaster;
use Customize\Repository\Admin\Master\MtbAddressRepository;
use Customize\Repository\Master\CourseMasterRepository;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CustomerStatus;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\CustomerType;
use Customize\Form\Type\Admin\Customer\CourseMasterType;
use Eccube\Form\Type\Admin\OrderType;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\MemberRepository;
use Eccube\Util\StringUtil;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Annotation\Groups;

class CustomerEditController extends AbstractController
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @var MtbAddressRepository
     */
    protected $mtbAddressRepository;

    /**
     * @var CourseMasterRepository
     */
    protected $courseMasterRepository;

    /**
     * @var MemberRepository
     */
    protected $memberRepository;

    /**
     * @var EncoderFactoryInterface
     */
    protected $encoderFactory;

    /**
     * @var Serializer
     */
    protected $serializer;

    public function __construct(
        CustomerRepository $customerRepository,
        EncoderFactoryInterface $encoderFactory,
        MtbAddressRepository $mtbAddressRepository,
        MemberRepository $memberRepository,
        SerializerInterface $serializer,
        CourseMasterRepository $courseMasterRepository
    ) {
        $this->customerRepository = $customerRepository;
        $this->encoderFactory = $encoderFactory;
        $this->mtbAddressRepository = $mtbAddressRepository;
        $this->memberRepository = $memberRepository;
        $this->serializer = $serializer;
        $this->courseMasterRepository = $courseMasterRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/customer/new", name="admin_customer_new", methods={"GET", "POST"})
     * @Route("/%eccube_admin_route%/customer/{id}/edit", requirements={"id" = "\d+"}, name="admin_customer_edit", methods={"GET", "POST"})
     * @Template("@admin/Customer/edit.twig")
     */
    public function index(Request $request, $id = null)
    {
        $this->entityManager->getFilters()->enable('incomplete_order_status_hidden');
        // 編集
        if ($id) {
            $Customer = $this->customerRepository
                ->find($id);

            if (is_null($Customer)) {
                throw new NotFoundHttpException();
            }

            $oldStatusId = $Customer->getStatus()->getId();
            // 編集用にデフォルトパスワードをセット
            $previous_password = $Customer->getPassword();
            $Customer->setPassword($this->eccubeConfig['eccube_default_password']);
            // 新規登録
        } else {
            $Customer = $this->customerRepository->newCustomer();
            $oldStatusId = null;
            $previous_password = null;
        }

        // 会員登録フォーム
        $builder = $this->formFactory
            ->createBuilder(CustomerType::class, $Customer);

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Customer' => $Customer,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CUSTOMER_EDIT_INDEX_INITIALIZE, $event);

        $form = $builder->getForm();

        // SAコードの設定を変える
        $data = $request->request->get('admin_customer');
        if (isset($data['Member']) && !empty($data['Member'])) {
            $MemberId = $data['Member'];
            $Member = $this->memberRepository->find($MemberId);
            $data['Member'] = $Member;
            $request->request->replace($data);
        }
        // $orderType = $request->request->get('order_type_id');

        // if (isset($data['order_type_id']) && !empty($data['order_type_id'])) {
        //     $orderTypeId = $data['order_type_id'];
        //     dd($orderTypeId);
        //     $Member = $this->memberRepository->find($MemberId);
        //     $data['Member'] = $Member;
        //     $request->request->replace($data);
        // }


        // 住所マスター取得
        $queryBuilder = $this->mtbAddressRepository->createQueryBuilder('ma');
        $query = $queryBuilder->getQuery();
        $mtbAddresses = $query->getArrayResult();

        // コースマスタ取得
        $courseMasterQueryBuilder = $this->courseMasterRepository->createQueryBuilder('cm');
        $courseMasterQuery = $courseMasterQueryBuilder->getQuery();
        $courseMasters = $courseMasterQuery->getArrayResult();

        // // 電話番号の重複チェック
        // if ($form->isSubmitted() && $form->isValid()) {
        //     $tel = $Customer->getTel();
        //     $this->customerRepository->checkTel($tel, $id);
        //     dump($this->customerRepository->checkTel($tel, $id));
        //     die();
        // }

        $form->handleRequest($request);
        //TODO フォームを送信時にisSubmitted()がtrueにならない
        // dump($form->isSubmitted());
        // die();

        // if ($form->isSubmitted() && !$form->isValid()) {
        //     $errors = [];
        //     foreach ($form->getErrors(true, false) as $error) {
        //         $errors[] = $error;
        //     }
        //     dump($errors);
        //     die();
        // }
        if ($form->isSubmitted() && $form->isValid()) {
            dump('test');
            die();
            log_info('会員登録開始', [$Customer->getId()]);


            $phone_number = $form->get('phone_number')->getData();
            $isExistPhoneNumber = $this->customerRepository->isExistPhoneNumber($phone_number, $Customer->getId());

            $isDuplicatePhoneNumber = $form->get('is_duplicate_phone_number')->getData();

            if ($isExistPhoneNumber && !$isDuplicatePhoneNumber) {
                return [
                    'isExistPhoneNumber' => $isExistPhoneNumber,
                    'form' => $form->createView(),
                    'Customer' => $Customer,
                    'MtbAddresses' => $this->serializer->serialize($mtbAddresses, 'json', ['groups' => ['mtb_address']]),
                    'CourseMasters' => $this->serializer->serialize($courseMasters, 'json', ['groups' => ['course_mater']]),
                ];
            }

            $encoder = $this->encoderFactory->getEncoder($Customer);
            if ($Customer->getPassword() === $this->eccubeConfig['eccube_default_password']) {
                $Customer->setPassword($previous_password);
            } else {
                if ($Customer->getSalt() === null) {
                    $Customer->setSalt($encoder->createSalt());
                    $Customer->setSecretKey($this->customerRepository->getUniqueSecretKey());
                }
                $Customer->setPassword($encoder->encodePassword($Customer->getPassword(), $Customer->getSalt()));
            }

            // 退会ステータスに更新の場合、ダミーのアドレスに更新
            $newStatusId = $Customer->getStatus()->getId();
            if ($oldStatusId != $newStatusId && $newStatusId == CustomerStatus::WITHDRAWING) {
                $Customer->setEmail(StringUtil::random(60) . '@dummy.dummy');
            }
            // dd($Customer);

            $this->entityManager->persist($Customer);
            $this->entityManager->flush();

            $Customer->setCustomerId($Customer->getId() . '@captainfood.co.jp');
            $this->entityManager->persist($Customer);
            $this->entityManager->flush();

            log_info('会員登録完了', [$Customer->getId()]);

            $event = new EventArgs(
                [
                    'form' => $form,
                    'Customer' => $Customer,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CUSTOMER_EDIT_INDEX_COMPLETE, $event);

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_customer_edit', [
                'id' => $Customer->getId(),
            ]);
        }

        return [
            'form' => $form->createView(),
            'Customer' => $Customer,
            'MtbAddresses' => $this->serializer->serialize($mtbAddresses, 'json', ['groups' => ['mtb_address']]),
            'CourseMasters' => $this->serializer->serialize($courseMasters, 'json', ['groups' => ['course_mater']]),
        ];
    }
}
