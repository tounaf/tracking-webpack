<?php

namespace App\Controller\Admin;

use App\Entity\EtapeSuivante;
use App\Form\EtapeSuivanteType;
use App\Repository\EtapeSuivanteRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("admin/etape-suivante")
 */
class EtapeSuivanteController extends Controller
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
     * @Route("/", name="etape_suivante_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(EtapeSuivanteRepository $etapeSuivanteRepository): Response
    {
        $etape_suivante = new EtapeSuivante();
        $form = $this->createForm(EtapeSuivanteType::class, $etape_suivante);
        return $this->render('Admin/etape_suivante/index.html.twig', [
            'etape_suivantes' => $etapeSuivanteRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="etape_suivante_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $etape = new EtapeSuivante();
        $form = $this->createForm(EtapeSuivanteType::class, $etape, [
            'method' => 'POST',
            'action' => $this->generateUrl('etape_suivante_new')
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
                $entityManager->persist($etape);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('etape_suivante_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('etape_suivante_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('etape_suivante_index');
        }

        return $this->render('Admin/_create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="etape_suivante_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("etape", class="App\Entity\EtapeSuivante")
     * @param EtapeSuivante|null $etape
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, EtapeSuivante $etape = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($etape){

            $form = $this->createForm(EtapeSuivanteType::class, $etape, [
                'method' => 'POST',
                'action' => $this->generateUrl('etape_suivante_edit', ['id' => $id])
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
                return $this->redirectToRoute('etape_suivante_index');
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
     * @Route("/{id}/delete", name="etape_suivante_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("etape", class="App\Entity\EtapeSuivante")
     * @param EtapeSuivante|null $etape
     */
    public function delete(Request $request, EtapeSuivante $etape = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($etape) {

            $form = $this->createForm(EtapeSuivanteType::class, $etape, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('etape_suivante_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($etape);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('etape_suivante_index');
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
