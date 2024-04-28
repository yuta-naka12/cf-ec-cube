<?php

namespace Customize\Controller\Front;

use Eccube\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Eccube\Repository\VoiceList\VoiceListRepository;
use Symfony\Component\HttpFoundation\Request;

class VoiceController extends AbstractController
{
    /**
     * @var VoiceListRepository
     */
    protected $voiceListRepository;

    /**
     * ContactController constructor.
     *
     * @param VoiceListGroupRepository $voiceListGroupRepository
     */
    public function __construct(
        VoiceListRepository $voiceListRepository
    )
    {
        $this->voiceListRepository = $voiceListRepository;
    }

    /**
     * @Method("GET")
     * @Route("/voice/{id}/detail",requirements={"id" = "\d+"}, name="voice-detail")
     * @Template("VoiceDetail/voice_detail.twig")
     */
    public function index(Request $request, $id)
    {
        $VoiceList = $this->voiceListRepository->find($id);

        $builder = $this->formFactory->createBuilder();

        $form = $builder->getForm();
        return [
            'form' => $form->createView(),
            'VoiceList' => $VoiceList,
        ];

    }
}