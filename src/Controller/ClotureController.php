<?php

namespace App\Controller;

use App\Entity\Cloture;
use App\Entity\Dossier;
use App\Entity\PjCloture;
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
    private $entityManager;
    private $repository;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    private $session;
    private $id ;
    // Set up all necessary variable
    protected function initialise()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->repository = $this->entityManager->getRepository('App:PjCloture');
        $this->translator = $this->get('translator');
    }
    /**
     *
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
     * @Route("/new/{id}", name="cloture_new", methods={"GET","POST"}, options={"expose"=true})
     */
    public function new(Request $request, Dossier $dossier=null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dossier = $entityManager->getRepository(Dossier::class)->find($this->id);
        $dossierExist = $entityManager->getRepository(Cloture::class)->findOneBy([
                'dossier'=> $dossier->getId()
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
            $paht = $this->getParameter('upload_directory');

            $datecloture = $request->get('cloture')['dateCloture'];
            $date = new \DateTime($datecloture);
          try{
              $entityManager = $this->getDoctrine()->getManager();
              $cloture->setDateCloture($date);
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
     * @param Request $request
     * @param Cloture $cloture
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @Route("/pj-cloture-edit/{id}/edit", name="pj_cloture_edit")
     */
    public function editAction(Request $request, cloture $cloture)
    {
        // Set up required variables
        $this->initialise();

        // Build the form
        $form = $this->get('form.factory')->create(ClotureType::class, $cloture);

        if ($request->isMethod('POST'))
        {
            $form->handleRequest($request);
            // Check form data is valid
            if ($form->isValid())
            {
                // Save data to database
                $this->entityManager->persist($cloture);
                $this->entityManager->flush();

                // Inform user
                $flashBag = $this->translator->trans('folder_edit_success', array(), 'flash');
                $request->getSession()->getFlashBag()->add('notice', $flashBag);

                // Redirect to view page
                return $this->redirectToRoute('pj_cloture_view', array(
                    'id'	=>	$cloture->getId(),
                ));
            }
        }
        // If we are here it means that either
        //	- request is GET (user has just landed on the page and has not filled the form)
        //	- request is POST (form has invalid data)

        return $this->render(
            'pj_cloture/edit.html.twig',
            array (
                'form'		=>	$form->createView(),
                'cloture'	=>	$cloture
            )
        );
    }

    /**
     * @param Request $request
     * @param Cloture $cloture
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/pj-cloture/{id}/view", name="pj_cloture_view")
     */
    public function viewAction(Request $request, Cloture $cloture)
    {
        // Set up required variables
        $this->initialise();
        return $this->render(
            'pj_cloture/view.html.twig',
            array (
                'cloture'	=>	$cloture,
            )
        );
    }
}
