<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use App\Form\DossierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DossierController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class DossierController extends Controller
{

    /**
     * @var TranslatorInterface
     */
    private $trans;

    /**
     * @var Session|SessionInterface
     */
    private $session;

    /**
     * DossierController constructor.
     * @param TranslatorInterface $translator
     * @param SessionInterface $sessionBag
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $sessionBag)
    {
        $this->trans = $translator;
        $this->session = $sessionBag;
    }

    /**
     * @Route("/dossier/render/create",name="render_create_dossier")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderCreateDossier()
    {
        return $this->render('dossier/dossier.html.twig');
    }

    /**
     *
     * @Route("/dossier/render/edit/{id}", name="render_edit_dossier")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderEditDossier(Dossier $dossier = null, Request $request)
    {
        $id = $request->get('id');
        if ($dossier) {
            $form = $this->createForm(DossierType::class, $dossier, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_dossier', array(
                    'id' => $id
                ))
            ));
        }
        return $this->render('dossier/dossier.html.twig', array(
            'form' => $form->createView()
        ));
    }
    /**
     *
     * @Route("/dossier/create", name="create_dossier", methods={"GET","POST"})
     * @param Request $request
     * @param Dossier|null $dossier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createDossier(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dossier = new Dossier();

        $form = $this->createForm(DossierType::class, $dossier, array(
            'action' => $this->generateUrl('create_dossier')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            $dateLitige = $request->get('dossier')['dateLitige'];
            $echeance = $request->get('dossier')['echeance'];
            $alert = $request->get('dossier')['alerteDate'];
            $dossier->setAlerteDate(new \DateTime($alert))->setDateLitige(new \DateTime($dateLitige))->setEcheance(new \DateTime($echeance));
            try {
                $em->persist($dossier);
                $em->flush();
                $this->session->getFlashBag()->add('success',$this->trans->trans('label.create.success'));
            } catch (\Exception $exception) {
                $this->session->getFlashBag()->add('danger',$this->trans->trans('label.create.error'));
            }

            return $this->redirectToRoute('render_edit_dossier', array('id' =>$dossier->getId()));
        }

      // return $this->redirectToRoute('render_create_dossier');
        return $this->render('dossier/form.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/dossier/edit/{id}", name="edit_dossier", methods={"GET", "POST"})
     * @param Dossier $dossier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editDossier(Dossier $dossier = null, Request $request)
    {
        $id = $request->get('id');
        if ($dossier instanceof Dossier) {
            $form = $this->createForm(DossierType::class, $dossier, array(
                'method' => 'POST',
                'action' => 'edit_dossier'
            ))->handleRequest($request)
            ;
            $response = new JsonResponse();
            if ($form->isSubmitted()) {
                try {

                    $this->getDoctrine()->getManager()->flush();
                    $this->session->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));

                } catch (\Exception $exception) {
                    $this->session->getFlashBag()->add('danger', $this->trans->trans('label.edit.error'));
                }
            }
        }
        return $this->redirectToRoute('render_edit_dossier', array('id' => $id));
    }
}
