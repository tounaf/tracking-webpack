<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use App\Entity\DossierSearch;
use App\Entity\InformationPj;
use App\Form\DossierSearchType;
use App\Form\DossierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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
    public function renderEditDossier(Dossier $dossier = null,InformationPj $informationPj = null, Request $request)
    {
        $id = $request->get('id');
        $directory = $this->get('kernel')->getProjectDir();

        if ($dossier) {

            $form = $this->createForm(DossierType::class, $dossier, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_dossier', array(
                    'id' => $id
                ))
            ));
        }

        return $this->render('dossier/dossier.html.twig', array(
            'form' => $form->createView(),
            'dossier' => $dossier,
            'infoPj' => $informationPj,
            'directory' => $directory
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
            $file = $form['File']->getData();
            $objInfoPj = new InformationPj();
            $dossier->setDirectory($dossier->getPathUpload());
            $directory = $this->get('kernel')->getProjectDir() . $dossier->getPathUpload();
            if ($file instanceof UploadedFile && is_dir($directory)){
                $file->move($directory, $file->getClientOriginalName());
                if($file->getClientOriginalName()  ){
                    $withoutExt = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file->getClientOriginalName());
                    $objInfoPj->setFilename($file->getClientOriginalName());
                    $objInfoPj->setLibelle($withoutExt);
                    $objInfoPj->setIsActif(true);
                    $objInfoPj->addDossier($dossier);
                    if(is_object($objInfoPj)){
                        $dossier->addPiecesJointe($objInfoPj);
                    }
                    $em->persist($objInfoPj);
                }
            }
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
                    $file = $form['File']->getData();
                    $dossier->setDirectory($dossier->getPathUpload());
                    $directory = $this->get('kernel')->getProjectDir() . $dossier->getPathUpload();
                     if ($file instanceof UploadedFile && is_dir($directory)){
                         $file->move($directory, $file->getClientOriginalName());
                     }
                    $this->getDoctrine()->getManager()->flush();
                    $this->session->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));

                } catch (\Exception $exception) {
                    $this->session->getFlashBag()->add('danger', $this->trans->trans('label.edit.error'));
                }
            }
        }
        return $this->redirectToRoute('render_edit_dossier', array('id' => $id));
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
            'form' => $form->createView()
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
}
