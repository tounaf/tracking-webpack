<?php

namespace App\Controller;

use App\Entity\Transfertnotification;
use App\Form\TransfertnotificationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Class TransfernotificationController
 * @package App\Controller
 * @Route("/admin")
 */
class TransfernotificationController extends Controller
{

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * TransfernotificationController constructor.
     * @param TranslatorInterface $translator
     * @param SessionInterface $sessionBag
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $sessionBag)
    {
        $this->translator = $translator;
        $this->session = $sessionBag;
    }


    /**
     * @Route("/transfernotification", name="transfernotification", options={"expose"=true})
     */
    public function index(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $objTransfernotification = new Transfertnotification();
        $form = $this->createForm(TransfertnotificationType::class, $objTransfernotification)
            ->handleRequest($request);
        if($form->isSubmitted()){
            try{
                $em->persist($objTransfernotification);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));
            }
            return $this->redirectToRoute('transfernotification');
        }
        $data = $this->getDataTransfer($em);
        return $this->render('transfernotification/index.html.twig', [
            'form' => $form->createView(),
            'dataTransfer' => $data,
        ]);
    }

    public function getDataTransfer($em){
        $dataTransfer = $em->getRepository(Transfertnotification::class)->findAll();
        if(!empty($dataTransfer) && is_array($dataTransfer)){
            return $dataTransfer;
        }

    }
}
