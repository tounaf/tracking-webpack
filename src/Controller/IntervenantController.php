<?php

namespace App\Controller;

use App\Entity\Dossier;
use App\Entity\Intervenant;
use App\Form\IntervenantType;
use App\Repository\IntervenantRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/intervenant")
 */
class IntervenantController extends Controller
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
     * @Route("/{id}", name="intervenant_index", methods={"GET"})
     */
    public function index(Request $request, Dossier $dossier = null)
    {
//        $idDossier = $this->get('session')->get('id');
        $intervenantLatest = $this->getDoctrine()->getRepository(Intervenant::class)->findLatestIntervenant($dossier->getId());
        if (count($intervenantLatest) > 0) {
            $intervenant = $this->getDoctrine()->getRepository(Intervenant::class)->find($intervenantLatest[0]['id']);
            //dump($intervenant);die();
        } else {
            $intervenant = new Intervenant();
        }
        $form = $this->createForm(IntervenantType::class, $intervenant);
        return $this->render('intervenant/index.html.twig', [
            'intervenant' => $intervenant,
            'form_intervenant' => $form->createView(),
            'dossier' => $dossier
        ]);
    }

    /**
     *
     * @Route("/getListAvocat", name="liste_avocat",  options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function ajaxLoadListe(Request $request)
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
        $listIntervenant = $this->getDoctrine()->getRepository(Intervenant::class)->getListIntervenant($extraParams,$this->id, false);
        $nbrRecords = $this->getDoctrine()->getRepository(Intervenant::class)->getListIntervenant($extraParams,$this->id, true);
        $response->setData(array(
            'draw' => (int)$draw,
            'recordsTotal' => (int)$nbrRecords[0]['record'],
            'recordsFiltered' => (int)$nbrRecords[0]['record'],
            'data' => $listIntervenant,
        ));
        return $response;
    }

    /**
     *
     * @Route("/{id}/delete", name="intervenant_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("intervenant", class="App\Entity\Intervenant")
     * @param Intervenant|null $intervenant
     */
    public function delete(Request $request, Intervenant $intervenant = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($intervenant) {

            $form = $this->createForm(IntervenantType::class, $intervenant, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('intervenant_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($intervenant);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'intervenant'));            }
            if($request->isXmlHttpRequest()){
            return $this->render('Admin/_delete_form_user.html.twig', array(
                'form_delete' => $form->createView()
            ));}
            else{ throw new NotFoundHttpException();}
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
     * @Route("/new_intervenant/{id}", name="intervenant_new", methods={"GET","POST"}, options={"expose"=true})
     *
     * @Route("/new_intervenant/{id}", name="intervenant_post_new", methods={"POST"})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request, Dossier $dossier = null)
    {
        $intervenant = new Intervenant();
        $form = $this->createForm(IntervenantType::class, $intervenant, [
            'method' => 'POST',
            'action' => $this->generateUrl('intervenant_post_new', array('id' => $request->get('id')))
        ])->handleRequest($request);

        $response = new JsonResponse();
        $message = [
            'status' => 200,
            'message' => $this->translator->trans('label.create.success'),
            'type' => 'success'
        ];

        if ($form->isSubmitted() && $form->isValid()) {
            try{
                $entityManager = $this->getDoctrine()->getManager();
                $intervenant->setDossier($dossier);
                $entityManager->persist($intervenant);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'intervenant'));
            return $response->setData($message);
        }

        if($request->isXmlHttpRequest()){
            return $this->render('intervenant/_form.html.twig', array(
                'form' => $form->createView(),
            ));
        }else{
            // return new JsonResponse(array('status' => 'Error'),400);
            throw new NotFoundHttpException();
        }
    }
    /**
     * @param Request $request
     * @Route("/{id}/edit", name="intervenant_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("nature", class="App\Entity\Intervenant")
     * @param Intervenant|null $nature
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Intervenant $intervenant = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($intervenant){

            $form = $this->createForm(IntervenantType::class, $intervenant, [
                'method' => 'POST',
                'action' => $this->generateUrl('intervenant_edit', ['id' => $id])
            ])->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid())
            {
                try{
                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                }catch (\Exception $exception)
                {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));
                }
                return $this->redirectToRoute('render_edit_dossier',array('id' => $this->id, 'currentTab' => 'intervenant'));
            }
            if($request->isXmlHttpRequest()){
                return $this->render('intervenant/_form.html.twig', array(
                    'form' => $form->createView(),
                ));
            }else{
                // return new JsonResponse(array('status' => 'Error'),400);
                throw new NotFoundHttpException();
            }
        }
        else {
            $response->setData(array(
                'message' => $this->translator->trans('label.not.found'),
                'status' => 403,
                'type' => 'danger'
            ));
            return $response;
        }
    }
}
