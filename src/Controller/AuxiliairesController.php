<?php

namespace App\Controller;

use App\Entity\Auxiliaires;
use App\Form\AuxiliairesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class AuxiliairesController extends Controller
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
     * @Route("/auxiliaires", name="auxiliaires")
     */
    public function index()
    {
        return $this->render('auxiliaires/index.html.twig', [
        ]);
    }
    /**
     * @Route("/getListAuxiliaire", name="liste_auxiliaire",  options={"expose"=true})
     */
    public function ajaxlistAuxiliaire(Request $request)
    {
        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;

        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];
        $auxis = $this->getDoctrine()->getRepository('App:Auxiliaires')->ajaxlistAuxiliaire(
            $filters, $start, $length
        );
        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('App:Auxiliaires')->ajaxlistAuxiliaire($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('App:Auxiliaires')->ajaxlistAuxiliaire(array(), 0, false))
        );
        foreach ($auxis as $auxi) {
            $output['data'][] = [
                'id'=>$auxi->getId(),
                'user' =>$auxi->getUser()?
                    $auxi->getUser()->getLastname().' '.
                    $auxi->getUser()->getName():''
                ,
                'convenu' => $auxi->getConvenu(),
                'payer' => $auxi->getPayer(),
                'reste_payer' => $auxi->getRestePayer(),
                'devise' => $auxi->getDevise()->getLibelle(),
                'prestation' => $auxi->getPrestation()->getLibelle(),
                'statuts' => $auxi->getStatutIntervenant(),
            ];
        }
        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
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
        $response = new JsonResponse();
        if ($auxiliaires) {

            $form = $this->createForm(AuxiliairesType::class, $auxiliaires, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('auxiliaires_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($auxiliaires);
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
