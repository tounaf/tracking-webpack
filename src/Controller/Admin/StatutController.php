<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 02/09/2019
 * Time: 09:21
 */

namespace App\Controller\Admin;


use App\Entity\Statut;
use App\Form\StatutType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class StatutController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class StatutController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * SocieteController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/statut/list", name="list_statut")
     */
    public function liste()
    {
        $em = $this->getDoctrine()->getManager();
        $statut = new Statut();
        $formStatut = $this->createForm(StatutType::class, $statut);
        $listStatut = $em->getRepository(Statut::class)->findAll();
        return $this->render('statut/list_statut.html.twig', array(
            'statuts' => $listStatut,
            'formStatut' => $formStatut->createView(),
            'isStatut' => true
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/statut/create", name="create_statut", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new Statut();
        $form = $this->createForm(StatutType::class, $entite, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_statut')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            try {

                $em->persist($entite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('list_statut');
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
                return $this->redirectToRoute('list_statut');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);
        }

        return $this->render('Admin/_create_user.html.twig', array(
            'form' => $form->createView(),
            'title' => 'fetra'
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/statut/{id}/edit", name="edit_statut", options={"expose"=true}, methods={"POST","GET"})
     * @ParamConverter("statut", class="App\Entity\Statut")
     * @param Statut|null $statut
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, Statut $statut = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($statut) {
            $form = $this->createForm(StatutType::class, $statut, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_statut', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {

                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));

                }
                return $this->redirectToRoute('list_statut');
            }
            return $this->render('Admin/_create_user.html.twig', array(
                'form' => $form->createView()
            ));
        } else {
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
     * @Route("/statut/{id}/delete", name="delete_statut", methods={"DELETE","GET"}, options={"expose"=true})
     * @ParamConverter("statut", class="App\Entity\Statut")
     * @param Request $request
     * @param Statut $statut
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function deleteUser(Request $request, Statut $statut = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($statut) {

            $form = $this->createForm(StatutType::class, $statut, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('delete_statut', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($statut);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('list_statut');
            }
            return $this->render('Admin/_delete_form_user.html.twig', array(
                'form_delete' => $form->createView()
            ));
        } else {
            $response->setData(array(
                'message' => $this->translator->trans('label.not.found.user'),
                'status' => 403,
                'type' => 'danger'
            ));
            return $response;
        }
    }
}