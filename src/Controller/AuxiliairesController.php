<?php

namespace App\Controller;

use App\Entity\Auxiliaires;
use App\Entity\Dossier;
use App\Entity\InformationPj;
use App\Entity\PjAuxiliaires;
use App\Form\AuxiliairesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class AuxiliairesController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;
    private $session;
    private $id;

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
     * @Route("/auxiliaires", name="auxiliaires")
     */
    public function index()
    {
        $id = $this->get('session')->get('id');
        return $this->render('auxiliaires/index.html.twig', [
            'dossier' => $id,
        ]);
    }

    /**
     *
     * @Route("/getListAuxiliaireActuel", name="liste_auxiliaireActuel",  options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function ajaxlistAuxiliaireActuel(Request $request)
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
        $listIntervenant = $this->getDoctrine()->getRepository(Auxiliaires::class)->getListAuxiliairesActuel($extraParams, $this->id, false);
        $nbrRecords = $this->getDoctrine()->getRepository(Auxiliaires::class)->getListAuxiliairesActuel($extraParams, $this->id, true);

        $response->setData(array(
            'draw' => (int)$draw,
            'recordsTotal' => isset($nbrRecords['record'])?(int)$nbrRecords['record']:(int)$nbrRecords,
            'recordsFiltered' => isset($nbrRecords['record'])?(int)$nbrRecords['record']:(int)$nbrRecords,
            'data' => $listIntervenant,
        ));
        return $response;
    }

    /**
     *
     * @Route("/getListAuxiliaire", name="liste_auxiliaire",  options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function ajaxlistAuxiliaire(Request $request)
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
        $listIntervenant = $this->getDoctrine()->getRepository(Auxiliaires::class)->getListAuxiliaires($extraParams, $this->id, false);
        $nbrRecords = $this->getDoctrine()->getRepository(Auxiliaires::class)->getListAuxiliaires($extraParams, $this->id, true);
        $response->setData(array(
            'draw' => (int)$draw,
            'recordsTotal' => (int)$nbrRecords[0]['record'],
            'recordsFiltered' => (int)$nbrRecords[0]['record'],
            'data' => $listIntervenant,
        ));
        return $response;
    }

    /**
     * @Route("/dossier/render/download/{id}", name="download_pjauxiliaire",  methods={"GET", "POST"}, options={"expose"=true})
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function downloadPjauxiliaire($id)
    {
        $em = $this->getDoctrine()->getManager();
        $oPjIntervenant = $em->getRepository(PjAuxiliaires::class)->findOneBy(array('auxiliaire' => $id));
        return $this->get('uploaderfichier')->downFilePjIntervenant($oPjIntervenant->getFilename());
    }

    /**
     *
     * @Route("/{id}/delete", name="auxiliaires_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("auxiliaires", class="App\Entity\Auxiliaires")
     * @param Auxiliaires|null $auxiliaires
     */
    public function delete(Request $request, Auxiliaires $auxiliaires = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $oPjauxiliaire = $em->getRepository(PjAuxiliaires::class)->findOneBy(array('auxiliaire' => $id));

        $response = new JsonResponse();
        if ($auxiliaires) {

            $form = $this->createForm(AuxiliairesType::class, $auxiliaires, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('auxiliaires_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $this->get('uploaderfichier')->deleteFile($this->get('uploaderfichier')->getTargetDirectory(),$oPjauxiliaire->getFilename());
                    $em->remove($oPjauxiliaire);
                    $em->remove($auxiliaires);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'auxiliaires'));
            }
            if ($request->isXmlHttpRequest()) {
                return $this->render('Admin/_delete_form_user.html.twig', array(
                    'form_delete' => $form->createView()
                ));
            } else {
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

    /**
     * @Route("/new_auxiliaires", name="auxiliaires_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, Dossier $dossier = null)
    {
        $em = $this->getDoctrine()->getManager();
        $dossier = $em->getRepository(Dossier::class)->find($this->id);
        $auxiliaires = new Auxiliaires();
        $auxiliaires->setDossier($dossier);
        $form = $this->createForm(AuxiliairesType::class, $auxiliaires, [
            'method' => 'POST',
            'action' => $this->generateUrl('auxiliaires_new')
        ])->handleRequest($request);
        $response = new JsonResponse();
        $message = [
            'status' => 200,
            'message' => $this->translator->trans('label.create.success'),
            'type' => 'success'
        ];
        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form['FileAux']->getData() ?? '';
            $entityObjSelected = $form->get('piecesJointesAux')->getData();
            $libelleSelected = $entityObjSelected->getLibelle() ?? '';
            $oPjauxiliaires = new PjAuxiliaires();
            if($libelleSelected){
                $oInfopj = $em->getRepository(InformationPj::class)->findOneBy(array('libelle' => $libelleSelected));
                $oPjauxiliaires->setInformationPj($oInfopj);
                $oPjauxiliaires->setDossier($dossier);
                $oPjauxiliaires->setAuxiliaire($auxiliaires);
            }
            if($file instanceof UploadedFile){
                $this->get('uploaderfichier')->upload($file);
                $oPjauxiliaires->setFilename($file->getClientOriginalName());
            }

            try {
                $em->persist($oPjauxiliaires);
                $em->persist($auxiliaires);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'auxiliaires'));
            return $response->setData($message);
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render('auxiliaires/_form.html.twig', array(
                'formAuxi' => $form->createView(),
            ));
        } else {
            // return new JsonResponse(array('status' => 'Error'),400);
            throw new NotFoundHttpException();
        }
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="auxiliaires_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("auxiliaires", class="App\Entity\Auxiliaires")
     * @param Auxiliaires|null $auxiliaires
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Auxiliaires $auxiliaires = null)
    {
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $oPjAuxiliaires = $em->getRepository(PjAuxiliaires::class)->findOneBy(array('auxiliaire'=>$id));
        $response = new JsonResponse();
        if ($auxiliaires) {
            $form = $this->createForm(AuxiliairesType::class, $auxiliaires, [
                'method' => 'POST',
                'action' => $this->generateUrl('auxiliaires_edit', ['id' => $id])
            ])->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                $files = $form['FileAux']->getData() ?? '';
                if($files instanceof UploadedFile){
                    $this->get('uploaderfichier')->upload($files);
                    $oPjAuxiliaires->setFilename($files->getClientOriginalName());
                }
                try {
                    $em->persist($oPjAuxiliaires);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));
                }
                return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'auxiliaires'));
            }
            if ($request->isXmlHttpRequest()) {
                return $this->render('auxiliaires/_form.html.twig', array(
                    'formAuxi' => $form->createView(),
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


}
