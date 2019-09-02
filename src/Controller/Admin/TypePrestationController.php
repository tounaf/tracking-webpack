<?php

namespace App\Controller\Admin;

use App\Entity\TypePrestation;
use App\Form\TypePrestationType;
use App\Repository\TypePrestationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("admin/type-prestation")
 */
class TypePrestationController extends Controller
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
     * @Route("/", name="type_prestation_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(TypePrestationRepository $typePrestationRepository): Response
    {
        $typePrestation= new TypePrestation();
        $form = $this->createForm(TypePrestationType::class, $typePrestation);
        return $this->render('Admin/type_prestation/index.html.twig', [
            'type_prestations' => $typePrestationRepository->findAll(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/new", name="type_prestation_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $typePrestation = new TypePrestation();
        $form = $this->createForm(TypePrestationType::class, $typePrestation, [
            'method' => 'POST',
            'action' => $this->generateUrl('type_prestation_new')
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
                $entityManager->persist($typePrestation);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('type_prestation_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('type_prestation_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('type_prestation_index');
        }

        return $this->render('Admin/type_prestation/_form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="type_prestation_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("typePrestation", class="App\Entity\TypePrestation")
     * @param TypePrestation|null $typePrestation
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, TypePrestation $typePrestation = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($typePrestation){

            $form = $this->createForm(TypePrestationType::class, $typePrestation, [
                'method' => 'POST',
                'action' => $this->generateUrl('type_prestation_edit', ['id' => $id])
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
                return $this->redirectToRoute('type_prestation_index');
            }
            return $this->render('Admin/type_prestation/_form.html.twig', [
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
     * @Route("/{id}/delete", name="type_prestation_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("typePrestation", class="App\Entity\TypePrestation")
     * @param TypePrestation|null $typePrestation
     */
    public function delete(Request $request, TypePrestation $typePrestation = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($typePrestation) {

            $form = $this->createForm(TypePrestationType::class, $typePrestation, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('type_prestation_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($typePrestation);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('type_prestation_index');
            }
            return $this->render('/Admin/type_prestation/_delete_form.html.twig', array(
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
