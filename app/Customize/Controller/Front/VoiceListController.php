<?php

namespace Customize\Controller\Front;

use Customize\Entity\Voice\Voice;
use Eccube\Controller\AbstractController;
use Eccube\Event\EventArgs;
use Eccube\Repository\VoiceList\VoiceListRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class VoiceListController extends AbstractController
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
     * @Route("/voice-list", name="voice-list")
     * @Template("VoiceList/voice_list.twig")
     */
    public function index(Request $request)
    {
        $VoiceList = $this->voiceListRepository->findAll();

        $builder = $this->formFactory->createBuilder();

        $form = $builder->getForm();
        return [
            'form' => $form->createView(),
            'VoiceLists' => $VoiceList,
        ];
    }
}
