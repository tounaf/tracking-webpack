<?php

namespace App\Controller\Admin;

use App\Entity\NiveauDecision;
use App\Form\NiveauDecisionType;
use App\Repository\NiveauDecisionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("admin/niveau-decision")
 */
class NiveauDecisionController extends Controller
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
     * @Route("/", name="niveau_decision_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(NiveauDecisionRepository $NiveauDecisionRepository): Response
    {
        $niveau_decision = new NiveauDecision();
        $form = $this->createForm(NiveauDecisionType::class, $niveau_decision);
        return $this->render('Admin/niveau_decision/index.html.twig', [
            'niveau_decisions' => $NiveauDecisionRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="niveau_decision_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $niveau = new NiveauDecision();
        $form = $this->createForm(NiveauDecisionType::class, $niveau, [
            'method' => 'POST',
            'action' => $this->generateUrl('niveau_decision_new')
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
                $entityManager->persist($niveau);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('niveau_decision_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('niveau_decision_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('niveau_decision_index');
        }

        return $this->render('Admin/niveau_decision/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="niveau_decision_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("niveau", class="App\Entity\NiveauDecision")
     * @param NiveauDecision|null $niveau
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, NiveauDecision $niveau = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($niveau){

            $form = $this->createForm(NiveauDecisionType::class, $niveau, [
                'method' => 'POST',
                'action' => $this->generateUrl('niveau_decision_edit', ['id' => $id])
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
                return $this->redirectToRoute('niveau_decision_index');
            }
            return $this->render('Admin/niveau_decision/_form.html.twig', [
                'form' => $form->createView(),
            ]);
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

    /**
     *
     * @Route("/{id}/delete", name="niveau_decision_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("niveau", class="App\Entity\NiveauDecision")
     * @param NiveauDecision|null $niveau
     */
    public function delete(Request $request, NiveauDecision $niveau = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($niveau) {

            $form = $this->createForm(NiveauDecisionType::class, $niveau, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('niveau_decision_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($niveau);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('niveau_decision_index');
            }
            return $this->render('/Admin/niveau_decision/_delete_form.html.twig', array(
                'form_delete' => $form->createView()
            ));
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
