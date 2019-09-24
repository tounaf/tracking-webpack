<?php

namespace App\Controller;

use App\Entity\Cloture;
use App\Entity\Dossier;
use App\Form\ClotureType;
use App\Repository\ClotureRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/cloture")
 */
class ClotureController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    private $session;
    private $id ;
    /**
     * UtilisateurController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $session)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->id = $this->session->get('id');
    }


    /**
     * @Route("/", name="cloture_index", methods={"GET"})
     */
    public function index(ClotureRepository $clotureRepository): Response
    {
        return $this->render('cloture/index.html.twig', [
            'clotures' => $clotureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new/{id}", name="cloture_new", methods={"GET","POST"}, options={"expose"=true})
     */
    public function new(Request $request, Dossier $dossier=null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dossier = $entityManager->getRepository(Dossier::class)->find($this->id);
        $dossierExist = $entityManager->getRepository(Cloture::class)->findOneBy([
                'dossier'=> $dossier
            ]
        );
        if ($dossier->getCloture()){
            $cloture = $dossier->getCloture();
        } else {

            $cloture = new Cloture();
        }

        $form = $this->createForm(ClotureType::class, $cloture, array(
            'action' =>$this->generateUrl('cloture_new', array('id' => $request->get('id')))
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
          //  $datecloture = $request->get('cloture')['dateCloture'];
            //$date = new \DateTime($datecloture);
        // $dtC =   $date->format('Y-m-d');
           // $cloture->setDateCloture($dtC);
          try{
              $entityManager = $this->getDoctrine()->getManager();
              $cloture->setDossier($dossier);
              if (!$dossierExist){
                  $entityManager->persist($cloture);
                  $entityManager->flush();
              }
              else{
                  $entityManager->flush();
              }
              $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
          } catch (Exception $exception){
              $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
          }
            return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'cloture'));
        }
        return $this->render('cloture/_form.html.twig', [
            'cloture' => $cloture,
            'formCloture' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cloture_edit", methods={"GET","POST"})
     * @ParamConverter("cloture", class="App\Entity\Cloture")
     */
    public function edit(Request $request, Cloture $cloture): Response
    {
        $form = $this->createForm(ClotureType::class, $cloture);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('cloture_index');
        }
        return $this->render('cloture/_form.html.twig', [
            'cloture' => $cloture,
            'formCloture' => $form->createView(),
        ]);
    }
}
