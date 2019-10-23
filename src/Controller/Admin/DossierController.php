<?php

namespace App\Controller\Admin;

use App\Entity\Cloture;
use App\Entity\PjCloture;
use App\Form\PjDossierType;
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
use phpDocumentor\Reflection\Types\This;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

        $currentTab = $request->get('currentTab');
        $id = $request->get('id');
        $formDossier = $this->createForm(PjDossierType::class, new PjDossier(), array(
            'action' => $this->generateUrl('upload_file'),
            'method' => 'POST'
        ));
        if ($dossier) {
            $formDossier = $this->createForm(PjDossierType::class, new PjDossier(), array(
                'action' => $this->generateUrl('uploaddossier_file', array('id' => $id)),
                'method' => 'POST'
            ));

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
            'formPj' => $formDossier->createView(),
            'dossier' => $dossier,
            'currentTab' =>$currentTab,
        ));
    }

    /**
     *
     * @Route("/dossier/listdossier-pj/{id}", name="liste_pj_dossier",  options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function ajaxlistPj(Request $request)
    {
        $idDossier = $request->get('id');
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
        $listPjD = $this->getDoctrine()->getRepository(PjDossier::class)->listPjDossier($extraParams, $idDossier, false);
        $nbrRecords = $this->getDoctrine()->getRepository(PjDossier::class)->listPjDossier($extraParams, $idDossier, true);
        $response->setData(array(
            'draw' => (int)$draw,
            'recordsTotal' => (int)$nbrRecords[0]['record'],
            'recordsFiltered' => (int)$nbrRecords[0]['record'],
            'data' => $listPjD,
        ));
        return $response;
    }

    /**
     *  @Route("/filedossier/upload/", name="uploaddossier_filerender", options={"expose"=true}, methods={"POST"})
     * @Route("/filedossier/upload/", name="uploaddossier_file", options={"expose"=true}, methods={"POST"})
     */
    public function savepjinf(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $idDossier = $request->get('id');
        $infoPjId = $request->get('pj_dossier_infoPj');
        $infoPj = $em->getRepository(InformationPj::class)->find($infoPjId);
        $pjDossier = new PjDossier();
        $form = $this->createForm(PjDossierType::class, $pjDossier, array(
            'method' => 'POST',
            'action' => $this->generateUrl('uploaddossier_file')
        ))->handleRequest($request);
        if(!empty($_FILES)){
            $filename = $_FILES['file']['name'];
            $tmpFilename = $_FILES['file']['tmp_name'];
            if($this->get('uploaderfichier')->checkfileUpload($filename)){
                $filename = $this->get('uploaderfichier')->renamefileUpload($filename);
            }
            $this->get('uploaderfichier')->uploadSimpleFile($filename, $tmpFilename);
        }
        if ($form->isSubmitted() || $request->getMethod() == "POST") {
            $dossier = $em->getRepository(Dossier::class)->find($idDossier);
            $pjDossier->setDossier($dossier);
            if($infoPj){
                $pjDossier->setInformationPj($infoPj);
            }
            $pjDossier->setFilename($filename);
            $em->persist($pjDossier);
            $em->flush();
            return new JsonResponse(array(
                'message' => 'upload termineé',
                'statut' => 200
            ));
        }
        return $this->render('dossier/pjform.html.twig', array(
            'formPj' => $form->createView()
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
     * @Route("/dossier/sub-dossier/delete/{id}", name="delete_sub_dossier", options={"expose"=true})
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
     * @Route("/dossier/render/deletepj/{id}", name="delete_pj",  methods={"GET", "POST"}, options={"expose"=true})
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
            return $this->get('uploaderfichier')->downFilePjIntervenant($fileName);
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

        if ($form->isSubmitted()) {
            $countREf = $em->getRepository(Dossier::class)->getCountRefDossier($form['raisonSocial']->getData());
            $dossier->setReferenceDossier($countREf);
            $cloture = new  Cloture();
            $cloture->setDossier($dossier);
            $dateLitige = $request->get('dossier')['dateLitige'];
            $echeance = $request->get('dossier')['echeance'];
            $alert = $request->get('dossier')['alerteDate'];
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
                $this->savePersistObj($em, $dossier);
                foreach ($dossier->getSubDossiers() as $subDossier){
                    $subDossier->setDossier($dossier);
                    $em->persist($subDossier);
                }
                $em->persist($dossier);
                $em->persist($cloture);
                $em->flush();
                $this->session->getFlashBag()->add('success',$this->trans->trans('label.create.success'));
            }catch (DBALException $ex){
                $this->session->getFlashBag()->add('danger',$this->trans->trans('label.champs.obli'));
                return $this->redirectToRoute('render_create_dossier');
            }
            catch (\Exception $exception) {
                $this->session->getFlashBag()->add('danger',$this->trans->trans('label.create.error'));
                return $this->redirectToRoute('render_create_dossier');
            }
            return $this->redirectToRoute('render_edit_dossier', array('id' =>$dossier->getId(),'currentTab' => 'declaration'));
        }
        return $this->render('dossier/form.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * @Route("/fileiniupload", name="get_fileuploadMax", options={"expose"=true})
     */
    public function getIniUpload()
    {
        $uploadmaxsize = floatval(substr_replace(ini_get('upload_max_filesize') ,"", -1));
        return $this->json($uploadmaxsize);
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
                'action' => $this->generateUrl('edit_dossier', array('id'=>$id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                $em = $this->getDoctrine()->getManager();
                try {
                    foreach ($dossier->getSubDossiers() as $subDossier) {
                        $subDossier->setDossier($dossier);
                        $this->savePersistObj($em, $subDossier);
                    }
                    $em->flush();
                    $this->session->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));
                } catch (\Exception $exception) {
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

        $listDossier = $this->getDoctrine()->getRepository(Dossier::class)->ajaxlistDossier($dossier, $extraParams,false, $this->getUser());
        $nbrRecords = $this->getDoctrine()->getRepository(Dossier::class)->ajaxlistDossier($dossier, $extraParams, true , $this->getUser());
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
                $this->get('session')->getFlashBag()->add('success', $this->trans->trans('label.create.success'));
                return $this->redirectToRoute('list_fonction');
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->trans->trans('label.create.error'));
                return $this->redirectToRoute('list_fonction');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);
        }

        return $this->render('dossier/sub_dossier/form_sub_dossier.html.twig', array(
            'form' => $form->createView(),
            'title' => 'christian'
        ));
    }

    /**
     *
     * @Route("/new_subDossier/{id}", name="subDossier_new", methods={"GET","POST"}, options={"expose"=true})
     *
     * @Route("/new_subDossier/{id}", name="subDossier_post_new", methods={"POST"})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newSubDossier(Request $request, Dossier $dossier = null)
    {
        $id =$request->get('id');
        $subDossier = new SubDossier();
        $form = $this->createForm(SubDossierType::class, $subDossier, [
            'method' => 'POST',
            'action' => $this->generateUrl('subDossier_post_new', array('id' => $request->get('id')))
        ])->handleRequest($request);

        $response = new JsonResponse();
        $message = [
            'status' => 200,
            'message' => $this->trans->trans('label.create.success'),
            'type' => 'success'
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $subDossier->setDossier($dossier);
            try{
                $entityManager->persist($subDossier);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->trans->trans('label.create.success'));
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->trans->trans('label.error.create'));
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $this->redirectToRoute('render_edit_dossier', array('id' => $id,
                'dossier' => $dossier,
                'currentTab' => 'declaration'));
            return $response->setData($message);
        }

        if ($request->isXmlHttpRequest()) {
            return $this->render('dossier/sub_dossier/_form_create_sub.html.twig', array(
                'form' => $form->createView(),
            ));
        } else {
            // return new JsonResponse(array('status' => 'Error'),400);
            throw new NotFoundHttpException();
        }
    }
    /**
     * @Route("/dossier/sub-dossier/edit/{id}", name="edit_sub_dossierS", methods={"GET", "POST"}, options={"expose"=true})
     * @param Dossier $dossier
     * @return \Symfony\Component\HttpFoundation\Response

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
                return $this->redirectToRoute('render_edit_dossier', array('id' => $id,
                    'dossier' => $dossier,
                    'currentTab' => 'declaration'));
               /* return new JsonResponse(array(
                    'message' => $this->trans->trans('label.edit.success'),
                    'type' => 'success',
                    'statut' => '200'
                )) ;
            }

            if ($request->isXmlHttpRequest()) {
                return $this->render('dossier/sub_dossier/form_sub_dossier.html.twig', array(
                    'form_subdossier' => $form->createView(),
                    'title' => 'fetra',
                    'idSubDossier' => $dossier->getId()
                ));
            } else {
                // return new JsonResponse(array('status' => 'Error'),400);
                throw new NotFoundHttpException();
            }
        }
        $response->setData(array(
            'message' => $this->trans->trans('label.not.found'),
            'status' => 403,
            'type' => 'danger'
        ));
        return $response;
    }*/


    /**
     * @param Request $request
     * @Route("/sub-dossier/edit/{id}", name="edit_sub_dossier", requirements={"id"="\d+"}, options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("subdossier", class="App\Entity\SubDossier")
     * @param SubDossier|null $subDossier
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editSubD(Request $request, SubDossier $subDossier = null)
    {
        $id = $request->get('id');
        $idDossier = $this->session->get('id');
        $em = $this->getDoctrine()->getManager();

        $response = new JsonResponse();
        if ($subDossier) {
            $form = $this->createForm(SubDossierType::class, $subDossier, [
                'method' => 'POST',
                'action' => $this->generateUrl('edit_sub_dossier', ['id' => $id])
            ])->handleRequest($request);
            if ($form->isSubmitted())
            {
                try{
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->trans->trans('label.edit.success'));
                }catch (\Exception $exception)
                {
                    $this->$exception->getMessage();die();
                    $this->get('session')->getFlashBag()->add('danger', $this->trans->trans('label.edit.error'));
                }
                return $this->redirectToRoute('render_edit_dossier',array('id' => $idDossier, 'currentTab' => 'declaration'));
            }
            if($request->isXmlHttpRequest()){
                return $this->render('dossier/sub_dossier/_form_create_sub.html.twig', array(
                    'form' => $form->createView(),
                ));
            } else {
                // return new JsonResponse(array('status' => 'Error'),400);
                throw new NotFoundHttpException();
            }
        } else {
            $response->setData(array(
                'message' => $this->translator->trans('label.not.found'),
                'status' => 403,
                'type' => 'danger'
            ));
            return $response;
        }
    }

    protected  function savePersistObj($em, $obj){
        $em->persist($obj);
        return;
    }

}
