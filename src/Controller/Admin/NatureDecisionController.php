<?php

namespace App\Controller\Admin;

use App\Entity\NatureDecision;
use App\Form\NatureDecisionType;
use App\Repository\NatureDecisionRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("admin/nature-decision")
 */
class NatureDecisionController extends Controller
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
     * @Route("/", name="nature_decision_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(NatureDecisionRepository $NatureDecisionRepository): Response
    {
        $nature_decision = new NatureDecision();
        $form = $this->createForm(NatureDecisionType::class, $nature_decision);
        return $this->render('Admin/nature_decision/index.html.twig', [
            'nature_decisions' => $NatureDecisionRepository->findAll(),
            'form' => $form->createView(),
            'title' => 'Gestion de la nature de décision de justice',
        ]);
    }

    /**
     * @Route("/new", name="nature_decision_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $nature = new NatureDecision();
        $form = $this->createForm(NatureDecisionType::class, $nature, [
            'method' => 'POST',
            'action' => $this->generateUrl('nature_decision_new')
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
                $entityManager->persist($nature);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('nature_decision_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('nature_decision_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('nature_decision_index');
        }

        return $this->render('Admin/_create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="nature_decision_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("nature", class="App\Entity\NatureDecision")
     * @param NatureDecision|null $nature
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, NatureDecision $nature = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($nature){

            $form = $this->createForm(NatureDecisionType::class, $nature, [
                'method' => 'POST',
                'action' => $this->generateUrl('nature_decision_edit', ['id' => $id])
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
                return $this->redirectToRoute('nature_decision_index');
            }
            return $this->render('Admin/_create_user.html.twig', [
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
     * @Route("/{id}/delete", name="nature_decision_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("nature", class="App\Entity\NatureDecision")
     * @param NatureDecision|null $nature
     */
    public function delete(Request $request, NatureDecision $nature = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($nature) {

            $form = $this->createForm(NatureDecisionType::class, $nature, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('nature_decision_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($nature);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('nature_decision_index');
            }
            return $this->render('Admin/_delete_form_user.html.twig', array(
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
