<?php

namespace App\Controller\Admin;

use App\Entity\Devise;
use App\Form\DeviseType;
use App\Repository\DeviseRepository;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;
/**
 * @Route("admin/devise")
 */
class DeviseController extends Controller
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
     * @Route("/", name="devise_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(DeviseRepository $deviseRepository): Response
    {
        $devise = new Devise();
        $form = $this->createForm(DeviseType::class, $devise);
        return $this->render('Admin/devise/index.html.twig', [
            'devises' => $deviseRepository->findAll(),
            'form' => $form->createView(),
            'title' =>'Gestion des devises',
        ]);
    }

    /**
     * @Route("/new", name="devise_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $devise = new Devise();
        $form = $this->createForm(DeviseType::class, $devise, [
            'method' => 'POST',
            'action' => $this->generateUrl('devise_new')
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
                $entityManager->persist($devise);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('devise_index');
            } catch (DBALException $ex){
                $this->get('session')->getFlashBag()->add('danger',$this->translator->trans('label.champs.obli'));
                return $this->redirectToRoute('devise_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('devise_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('devise_index');
        }

        return $this->render('Admin/_create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="devise_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("devise", class="App\Entity\Devise")
     * @param Devise|null $devise
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, Devise $devise = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($devise){

            $form = $this->createForm(DeviseType::class, $devise, [
                'method' => 'POST',
                'action' => $this->generateUrl('devise_edit', ['id' => $id])
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
                return $this->redirectToRoute('devise_index');
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
     * @Route("/{id}/delete", name="devise_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("devise", class="App\Entity\Devise")
     * @param Devise|null $devise
     */
    public function delete(Request $request, Devise $devise = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($devise) {

            $form = $this->createForm(DeviseType::class, $devise, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('devise_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($devise);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('devise_index');
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
