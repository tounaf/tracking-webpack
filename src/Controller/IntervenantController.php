<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Form\IntervenantType;
use App\Repository\IntervenantRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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

    /**
     * UtilisateurController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/", name="intervenant_index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('intervenant/index.html.twig', [
        ]);
    }

    /**
     * @Route("/getListAvocat", name="liste_avocat",  options={"expose"=true})
     */
    public function paginateAction(Request $request)
    {
        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;

        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];
        $avocats = $this->getDoctrine()->getRepository('App:Intervenant')->search(
            $filters, $start, $length
        );
        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('App:Intervenant')->search($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('App:Intervenant')->search(array(), 0, false))
        );
        foreach ($avocats as $avocat) {
            $output['data'][] = [
                'id'=>$avocat->getId(),
                'user' =>$avocat->getUser()?
                    $avocat->getUser()->getLastname().' '.
                    $avocat->getUser()->getName():''
                ,
                'convenu' => $avocat->getConvenu(),
                'payer' => $avocat->getPayer(),
                'reste_payer' => $avocat->getRestePayer(),
                'devise' => $avocat->getDevise()->getLibelle(),
                'prestation' => $avocat->getPrestation()->getLibelle(),
                'statuts' => $avocat->getStatutIntervenant(),
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
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
                return $this->redirectToRoute('core_litige');
            }
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
}
