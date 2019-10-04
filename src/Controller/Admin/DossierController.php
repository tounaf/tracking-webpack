<?php

namespace App\Controller\Admin;

use App\Entity\PjCloture;
use App\Service\FileUploader;
use App\Entity\Dossier;
use App\Entity\DossierSearch;
use App\Entity\SubDossier;
use App\Entity\InformationPj;
use App\Entity\PjDossier;
use App\Form\DossierSearchType;
use App\Form\DossierType;
use App\Form\SubDossierType;
use App\Repository\DossierRepository;
use mysql_xdevapi\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        return $this->render('dossier/dossier.html.twig',array(
            'currentTab' => 'declaration'
        ));
    }



    /**
     *
     * @Route("/dossier/render/edit/{id}/{currentTab}", name="render_edit_dossier", options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function renderEditDossier(Dossier $dossier = null, Request $request)
    {
        $id = $request->get('id');
        $currentTab = $request->get('currentTab');
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
            'dossier' => $dossier,
            'currentTab' =>$currentTab,
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
     * @Route("/dossier/render/deletepj/{id}", name="delete_pj",  methods={"GET", "POST"})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function deletepj($id){
        $em = $this->getDoctrine()->getManager();
        $oPjDossier = $em->getRepository(PjDossier::class)->find($id);
        $idDossier = $oPjDossier->getDossier()->getId();
        if($this->get('uploaderfichier')->getTargetDirectory()){
            if(!empty($oPjDossier->getFilename())){
                $this->get('uploaderfichier')->deleteFile($this->get('uploaderfichier')->getTargetDirectory(), $oPjDossier->getFilename());
            }
        }
        try{
            $em->remove($oPjDossier);
            $em->flush();
            $this->session->getFlashBag()->add('success',$this->trans->trans('label.delete.success'));
            return $this->redirectToRoute('edit_dossier', array(
                'id' => $idDossier));
        }
        catch (\Exception $exception){
            $this->session->getFlashBag()->add('danger', $this->trans->trans('label.delete.error'));
        }
        return $this->redirectToRoute('edit_dossier', array(
            'id' => $idDossier));
    }


    /**
     * @Route("/dossier/render/download/{id}", name="download_dossier",  methods={"GET", "POST"}, options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadDossier($id) {
        $em = $this->getDoctrine()->getManager();
        $PjDossier = $em->getRepository(PjDossier::class)->find($id);
        if ($PjDossier) {
            $fileName = $PjDossier->getFilename() ;
        } else {
            $PjDossier = $em->getRepository(PjCloture::class)->find($id);
            $fileName = $PjDossier->getName() ;
        }
        if($fileName){
            $response = new Response();
            $response->headers->set('Content-type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $fileName ));
            $response->setContent(file_get_contents($this->get('uploaderfichier')->getTargetDirectory().$fileName));
            $response->setStatusCode(200);
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            return $response;
        }
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
        $currentUser = $this->getUser();
        $curr = $this->getUser()->getSociete();
        $dossier = new Dossier($currentUser,$curr);
        $PjDossier = new PjDossier();
        $form = $this->createForm(DossierType::class, $dossier, array(
            'action' => $this->generateUrl('create_dossier')
        ))->handleRequest($request);
        $directory = $this->get('kernel')->getProjectDir() . $dossier->getPathUpload();

        if ($form->isSubmitted()) {
            $entityObjSelected = $form->get('piecesJointes')->getData();
            $libelleSelected = $entityObjSelected->getLibelle() ?? '';
            $dossier->setLibelle($libelleSelected);
            $dateLitige = $request->get('dossier')['dateLitige'];
            $echeance = $request->get('dossier')['echeance'];
            $alert = $request->get('dossier')['alerteDate'];
            $dossier->setAlerteDate(new \DateTime($alert))->setDateLitige(new \DateTime($dateLitige))->setEcheance(new \DateTime($echeance));
            $file = $form['File']->getData() ?? '';
            $oInfoPj = $em->getRepository(InformationPj::class)->findOneBy(array('libelle'=>$libelleSelected));

            $dossier->setDirectory($directory);
            if ($file instanceof UploadedFile){
                $this->get('uploaderfichier')->upload($file);
                $PjDossier->setInformationPj($oInfoPj);
                $PjDossier->setFilename($file->getClientOriginalName());
                $this->savePersistObj($em, $PjDossier);
            }else{
                $PjDossier->setInformationPj($oInfoPj);
                $this->savePersistObj($em, $PjDossier);
            }
            try {
                $this->savePersistObj($em, $dossier);
                foreach ($dossier->getSubDossiers() as $subDossier){
                    $subDossier->setDossier($dossier);
                    $em->persist($subDossier);
                }
                $em->flush();
                $this->session->getFlashBag()->add('success',$this->trans->trans('label.create.success'));
            } catch (\Exception $exception) {
                $this->session->getFlashBag()->add('danger',$this->trans->trans('label.create.error'));
            }

            return $this->redirectToRoute('render_edit_dossier', array('id' =>$dossier->getId(),'currentTab' => 'declaration'));
        }
        return $this->render('dossier/form.html.twig', array(
            'form' => $form->createView(),
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

            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                $dossier->setDirectory($dossier->getPathUpload());
                $entityObjSelected = $form->get('piecesJointes')->getData();
                $file = $form['File']->getData() ?? '';
                if($entityObjSelected != null){
                    $objPjDossier = new PjDossier();
                    $libelleSelected = $entityObjSelected->getLibelle();
                    $dossier->setLibelle($libelleSelected);

                    $dataInfoPj = $em->getRepository(InformationPj::class)->findOneBy(array('libelle' => $libelleSelected));
                    $objPjDossier->setInformationPj($dataInfoPj);
                    if ($file instanceof UploadedFile) {
                        $this->get('uploaderfichier')->upload($file);
                        $objPjDossier->setFilename($file->getClientOriginalName());
                    }
                    $objPjDossier->setDossier($dossier);
                    $this->savePersistObj($em, $objPjDossier);
                }
                try {
                    foreach ($dossier->getSubDossiers() as $subDossier){
                        $subDossier->setDossier($dossier);
                        $this->savePersistObj($em, $subDossier);
                    }
                    $em->flush();
                    $this->session->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    dump($exception->getMessage());die;
                    $this->session->getFlashBag()->add('danger', $this->trans->trans('label.edit.error'));
                }
            }
        }


        return $this->redirectToRoute('render_edit_dossier', array('id' => $id,
            'dossier' => $dossier,
            'currentTab' => 'declaration'));
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

    protected  function savePersistObj($em, $obj){
        $em->persist($obj);
        return;
    }

}
