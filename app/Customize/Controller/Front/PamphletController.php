<?php

namespace Customize\Controller\Front;

use Customize\Entity\Customer\ApplyPamphlet;
use Customize\Form\Type\PamphletType;
use Eccube\Controller\AbstractController;
use Eccube\Repository\ApplyPamphletRepository;
use Eccube\Repository\Master\PrefRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class PamphletController extends AbstractController
{
    /**
     * @var PrefRepository
     */
    protected $prefRepository;

    /**
     * @var ApplyPamphletRepository
     */
    protected $applyPamphletRepository;

    /**
     * CustomerController constructor.
     *
     * @param PrefRepository $prefRepository
     * @param ApplyPamphletRepository $applyPamphletRepository
     *
     */
    public function __construct(
        PrefRepository $prefRepository,
        ApplyPamphletRepository $applyPamphletRepository
    ) {
        $this->prefRepository = $prefRepository;
        $this->applyPamphletRepository = $applyPamphletRepository;
    }
    /**
     * @Method("GET","POST")
     * @Route("/apply/pamphlet", name="apply_pamphlet")
     * @Template("Pamphlet/pamphlet.twig")
     */
    public function index(Request $request)
    {
        $applicant = new ApplyPamphlet();
        $builder = $this->formFactory->createBuilder(PamphletType::class, $applicant);
        $form = $builder->getForm();
        $form->handleRequest($request);

        $prefectures = $this->prefRepository->findAll();

        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('apply_pamphlet_confirm',$request->request->get('pamphlet'),307);
        }

        return [
            'form' => $form->createView(),
            'prefectures' => $prefectures,
        ];
    }

    /**
     * @Method("POST")
     * @Route("/apply/pamphlet/confirm", name="apply_pamphlet_confirm")
     * @Template("Pamphlet/pamphlet_confirm.twig")
     */
    public function confirm(Request $request)
    {
        $applicant = $request->request->get('pamphlet');
        $prefecture =  $this->prefRepository->find($applicant['address']['pref']);

        return [
            'applicant' => $applicant,
            'prefecture' => $prefecture
        ];
    }

    /**
     * @Method("POST")
     * @Route("/apply/pamphlet/complete", name="apply_pamphlet_complete")
     * @Template("Pamphlet/pamphlet_complete.twig")
     */
    public function create(Request $request)
    {
        $pamphlet = $request->request->get('pamphlet');
        $prefecture =  $this->prefRepository->find($pamphlet['address']['pref']);
        $isApplicant = $this->applyPamphletRepository->findBy(['email' => $request->get('email')]);
        if (empty($isApplicant)) {
            $applicant = new ApplyPamphlet();
            $applicant->setName01($pamphlet['name01']);
            $applicant->setName02($pamphlet['name02']);
            $applicant->setKana01($pamphlet['kana01']);
            $applicant->setKana02($pamphlet['kana02']);
            $applicant->setPostalCode($pamphlet['postal_code']);
            $applicant->setPref($prefecture);
            $applicant->setAddr01($pamphlet['address']['addr01']);
            $applicant->setAddr02($pamphlet['address']['addr02']);
            $applicant->setPhoneNumber($pamphlet['phone_number']);
            $applicant->setEmail($pamphlet['email']);
            $applicant->setMemo($pamphlet['memo']);
            $this->entityManager->persist($applicant);
            $this->entityManager->flush();
        }
    }
}
