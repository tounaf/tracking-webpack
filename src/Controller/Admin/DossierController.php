<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use App\Entity\DossierSearch;
use App\Entity\SubDossier;
use App\Form\DossierSearchType;
use App\Form\DossierType;
use App\Form\SubDossierType;
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
            $this->get('session')->set('id', $id);
        }
        return $this->render('dossier/dossier.html.twig', array(
            'form' => $form->createView(),
            'dossier' => $dossier
        ));
    }

    private function createDeleteForm($subDossier)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('delete_sub_dossier', array('id' => $subDossier->getId())))
            ->setMethod('DELETE')
            ->getForm()
            ;
    }

    /**
     * @Route("/sub-dossier/delete/{id}", name="delete_sub_dossier", options={"expose"=true})
     * @param Request $request
     * @param SubDossier|null $subDossier
     */
    public function deleteSubDossier(Request $request, SubDossier $subDossier = null)
    {
        $form = $this->createDeleteForm($subDossier);
        $form->handleRequest($request);
        $response = new JsonResponse();
        if ($subDossier) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($subDossier);
            $em->flush();
            $response->setData(['message' => 'suppression ok','status' => 201, 'type' => 'sucess']);
            return $response;
        }
        $response->setData(array(
            'message' => $this->translator->trans('label.not.found.user'),
            'status' => 403,
            'type' => 'danger'
        ));
        return $response;
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
                foreach ($dossier->getSubDossiers() as $subDossier){
                    $subDossier->setDossier($dossier);
                    $em->persist($subDossier);
                }
                $em->flush();
                $this->session->getFlashBag()->add('success',$this->trans->trans('label.create.success'));
            } catch (\Exception $exception) {
                $this->session->getFlashBag()->add('danger',$this->trans->trans('label.create.error'));
            }

            return $this->redirectToRoute('render_edit_dossier', array('id' =>$dossier->getId()));
        }

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

                    foreach ($dossier->getSubDossiers() as $subDossier){
                        $subDossier->setDossier($dossier);
                        $this->getDoctrine()->getManager()->persist($subDossier);
                    }
                    $this->getDoctrine()->getManager()->flush();
                    $this->session->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));

                } catch (\Exception $exception) {
                    $this->session->getFlashBag()->add('danger', $this->trans->trans('label.edit.error'));
                }
            }
        }
        return $this->redirectToRoute('render_edit_dossier', array('id' => $id, 'dossier' => $dossier));
    }

    /**
     * @Route("/dossier/list", name="dossier_list")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function listeDossier(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dossier = new DossierSearch();

        $form = $this->createForm(DossierSearchType::class, $dossier);

        $dossier = $em->getRepository(Dossier::class)->findAll();
        return $this->render('dossier/liste_dossier.html.twig', array(
            'dosser' => $dossier,
            'form' => $form->createView(),
            'title' => 'Dossier'
        ));
    }

    /**
     * @Route("/dossier/ajax/list", name="ajax_list_dossier", options={"expose"=true}, methods={"POST", "GET"})
     * @param Request $request
     * @return Response
     */
    public function ajaxLoadListeDossier(Request $request)
    {
        $response = new JsonResponse();
        $draw = $request->get('draw');
        $length = $request->get('length');
        $start = $request->get('start');
        $search = $request->get('search');
        $filters = [
            'query' => $search['value'],
        ];
        $vOrder = $request->get('order');
        $orderBy = $vOrder[0]['column'];
        $order = $vOrder[0]['dir'];
        $extraParams = array(
            'filters' => $filters,
            'start' => $start,
            'length' => $length,
            'orderBy' => $orderBy,
            'order' => $order,
        );
        if ($this->get('session')->has('dossierSearch')) {
            $dossier = $this->get('serializer')->deserialize(
                $this->get('session')->get('dossierSearch'),
                DossierSearch::class,
                'json'
            );
        } else {

            $dossier = new DossierSearch();
        }
        $listDossier = $this->getDoctrine()->getRepository(Dossier::class)->ajaxlistDossier($dossier, $extraParams,false, true);
        $nbrRecords = $this->getDoctrine()->getRepository(Dossier::class)->ajaxlistDossier($dossier, $extraParams, true);
        $response->setData(array(
            'draw' => (int)$draw,
            'recordsTotal' => (int)$nbrRecords[0]['record'],
            'recordsFiltered' => (int)$nbrRecords[0]['record'],
            'data' => $listDossier,
        ));
        return $response;
    }

    /**
     * @Route("/dossier/search", name="search_dossier", options={"expose"=true}, methods={"POST", "GET"})
     */
    public function searchDossier(Request $request)
    {
        $dossier = new DossierSearch();
        $formSearch = $this->createForm(DossierSearchType::class, $dossier);
        $formSearch->handleRequest($request);
        if ($formSearch->isSubmitted() && $request->isXmlHttpRequest()) {
            $this->get('session')->set('dossierSearch', $this->get('serializer')->serialize($dossier, 'json'));
        }
        return new JsonResponse(array(
            'message' => 'Recherche en cours ...'
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/dossier/sub-dossier/create", name="create_subdossier", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createSubDossier(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new SubDossier();
        $form = $this->createForm(SubDossierType::class, $entite, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_subdossier')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            try {

                $em->persist($entite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('list_fonction');
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
                return $this->redirectToRoute('list_fonction');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);
        }

        return $this->render('dossier/sub_dossier/form_sub_dossier.html.twig', array(
            'form_subdossier' => $form->createView(),
            'title' => 'fetra'
        ));
    }

    /**
     * @Route("/dossier/sub-dossier/edit/{id}", name="edit_sub_dossier", methods={"GET", "POST"}, options={"expose"=true})
     * @param Dossier $dossier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editSubDossier(SubDossier $dossier = null, Request $request)
    {
        $id =$request->get('id');

        $response = new JsonResponse();
        if ($dossier instanceof SubDossier && $request->isXmlHttpRequest()) {
            $form = $this->createForm(SubDossierType::class, $dossier, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_sub_dossier', array('id' => $dossier->getId()))
            ))->handleRequest($request)
            ;
            if ($request->getMethod() == 'POST' && $request->isXmlHttpRequest()) {
                try {
                    $libelle = $request->get('libelle');
                    $numeroSubDossier = $request->get('numero');
                    $em = $this->getDoctrine()->getManager();
                    $subDossier = $em->getRepository(SubDossier::class)->find($request->get('id'));
                    $subDossier->setNumeroSubDossier($numeroSubDossier)->setLibelle($libelle);
                    $em->persist($subDossier);
                    $em->flush();
                    $this->session->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));

                } catch (\Exception $exception) {
                    $this->session->getFlashBag()->add('danger', $this->trans->trans('label.edit.error'));
                }
                return new JsonResponse(array(
                    'message' => $this->trans->trans('label.edit.success'),
                    'type' => 'success',
                    'statut' => '200'
                )) ;
//                return $this->redirectToRoute('render_edit_dossier', array('id' => $id, 'dossier' => $dossier->getDossier()->getId()));
            }
            return $this->render('dossier/sub_dossier/form_sub_dossier.html.twig', array(
                'form_subdossier' => $form->createView(),
                'title' => 'fetra',
                'idSubDossier' => $dossier->getId()
            ));
        }
        $response->setData(array(
            'message' => $this->trans->trans('label.not.found'),
            'status' => 403,
            'type' => 'danger'
        ));
        return $response;
    }
}
