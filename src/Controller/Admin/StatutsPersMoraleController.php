<?php

namespace App\Controller\Admin;

use App\Entity\StatutsPersMorale;
use App\Form\StatutsPersMoraleType;
use App\Repository\StatutsPersMoraleRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("admin/statuts-persmorale")
 */
class StatutsPersMoraleController extends Controller
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
     * @Route("/", name="statuts_pers_morale_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(StatutsPersMoraleRepository $statutsPersMoraleRepository): Response
    {
        $statutsPersMorale= new StatutsPersMorale();
        $formPersMorale = $this->createForm(StatutsPersMoraleType::class, $statutsPersMorale);
        return $this->render('Admin/statuts_pers_morale/index.html.twig', [
            'statuts_pers_morales' => $statutsPersMoraleRepository->findAll(),
            'formPersMorale' => $formPersMorale->createView()
        ]);
    }

    /**
     * @Route("/new", name="statuts_pers_morale_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $statutsPersMorale = new StatutsPersMorale();
        $formPersMorale = $this->createForm(StatutsPersMoraleType::class, $statutsPersMorale, [
            'method' => 'POST',
            'action' => $this->generateUrl('statuts_pers_morale_new')
        ])->handleRequest($request);

        $response = new JsonResponse();
        $message = [
            'status' => 200,
            'message' => $this->translator->trans('label.create.success'),
            'type' => 'success'
        ];

        if ($formPersMorale->isSubmitted() && $formPersMorale->isValid()) {
            try{
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($statutsPersMorale);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('statuts_pers_morale_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('statuts_pers_morale_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('statuts_pers_morale_index');
        }

        return $this->render('Admin/_create_user.html.twig', [
            'formPersMorale' => $formPersMorale->createView(),
        ]);
    }


    /**
     * @param Request $request
     * @Route("/{id}/edit", name="statuts_pers_morale_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("statutsPersMorale", class="App\Entity\StatutsPersMorale")
     * @param StatutsPersMorale|null $statutsPersMorale
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, StatutsPersMorale $statutsPersMorale = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($statutsPersMorale){

            $form = $this->createForm(StatutsPersMoraleType::class, $statutsPersMorale, [
                    'method' => 'POST',
                    'action' => $this->generateUrl('statuts_pers_morale_edit', ['id' => $id])
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
                return $this->redirectToRoute('statuts_pers_morale_index');
            }
            return $this->render('Admin/_create_user.html.twig', [
                'statuts_pers_morale' => $statutsPersMorale,
                'formPersMorale' => $form->createView(),
            ]);
        }
        else {
            $response->setData(array(
                'message' => $this->translator->trans('label.not.found.user'),
                'status' => 403,
                'type' => 'danger'
            ));
            return $response;
        }
    }


    /**
     *
     * @Route("/{id}/delete", name="statuts_pers_morale_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @ParamConverter("statutsPersMorale", class="App\Entity\StatutsPersMorale")
     * @param Request $request
     *StatutsPersMorale|null $statutsPersMorale
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function deletePersMorale(Request $request, StatutsPersMorale $statutsPersMorale = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($statutsPersMorale) {

            $form = $this->createForm(StatutsPersMoraleType::class, $statutsPersMorale, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('statuts_pers_morale_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($statutsPersMorale);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('statuts_pers_morale_index');
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
