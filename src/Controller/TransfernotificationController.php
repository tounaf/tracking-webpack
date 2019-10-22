<?php

namespace App\Controller;
use App\Service\Servicetransfernotification;
use App\Entity\Transfertnotification;
use App\Form\TransfertnotificationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Validator\Constraints\DateTime;

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
        $data = $this->getDataTransfer($em);
        if($form->isSubmitted()){
            try{
                if($this->checkDataNotif($request, $em)){
                    return $this->render('transfernotification/index.html.twig', [
                        'form' => $form->createView(),
                        'dataTransfer' => $data,
                    ]);
                }else{
                    $em->persist($objTransfernotification);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                }
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));
            }
            return $this->redirectToRoute('transfernotification');
        }

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
        return $dataTransfer;
    }

    public function formatDatetransfer($datetransfer){
        return $datetransfer->format('d/m/y');
    }

    public function compareDatetime($dataTransfer, $dataRequest){
        $dateFin = strtotime($dataRequest['datefin']);
        $datefintransfert = date('d/m/y',$dateFin);
        if($dataTransfer->getDatefin()>= $datefintransfert){
            return true;
        }
        return false;
    }

    public function checkDataNotif($request, $em){
        $dataTransfer = $this->getDataTransfer($em);
        $dataRequest = $request->get('transfertnotification');
        if(!empty($dataTransfer) && is_array($dataRequest)){
            foreach ($dataTransfer as $dataTransfer){
                if($this->compareDatetime($dataTransfer, $dataRequest) && in_array($dataTransfer->getUsernotif()->getId(), $dataRequest)){
                    $this->get('session')->getFlashBag()->add('danger', 'Veuillez notifié cette personne à une date début après cette date ' .$this->formatDatetransfer($dataTransfer->getDatefin()) );
                    return true;
                }
                if(in_array($dataTransfer->getUsernotif()->getId(), $dataRequest)
                    || in_array($this->formatDatetransfer($dataTransfer->getDatedebut()), $dataRequest)
                    || in_array($this->formatDatetransfer($dataTransfer->getDatefin()), $dataRequest)
                )
                {
                    $this->get('session')->getFlashBag()->add('danger', 'cette personne a été déjà notifié à ces dates !');
                    return true;
                }
            }
            return;
        }
    }


}
