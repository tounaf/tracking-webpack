<?php

namespace App\Controller;

use App\Entity\Cloture;
use App\Form\ClotureType;
use App\Repository\ClotureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * UtilisateurController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }


    /**
     * @Route("/", name="cloture_index", methods={"GET"})
     */
    public function index(ClotureRepository $clotureRepository): Response
    {dump("index");
        return $this->render('cloture/index.html.twig', [
            'clotures' => $clotureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cloture_new", methods={"GET","POST"}, options={"expose"=true})
     */
    public function new(Request $request): Response
    {
        $cloture = new Cloture();
        $form = $this->createForm(ClotureType::class, $cloture, array(
            'action' =>$this->generateUrl('cloture_new')
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $datecloture = $request->get('cloture')['dateCloture'];
            $cloture->setDateCloture(new \DateTime($datecloture));
          try{
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($cloture);
              $entityManager->flush();
              $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
          } catch (Exception $exception){

              $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
          }
            return $this->redirectToRoute('core_litige');
        }

        return $this->render('cloture/_form.html.twig', [
            'cloture' => $cloture,
            'formCloture' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cloture_show", methods={"GET"})
     */
    public function show(Cloture $cloture): Response
    {
        return $this->render('cloture/show.html.twig', [
            'cloture' => $cloture,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cloture_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Cloture $cloture): Response
    {
        $form = $this->createForm(ClotureType::class, $cloture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cloture_index');
        }

        return $this->render('cloture/edit.html.twig', [
            'cloture' => $cloture,
            'formCloture' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cloture_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Cloture $cloture): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cloture->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cloture);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cloture_index');
    }
}
