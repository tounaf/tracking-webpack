<?php

namespace App\Controller\Admin;

use App\Entity\Fonction;
use App\Form\FonctionType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class FonctionController
 * @package App\Controller
 * @Route("/admin")
 */
class FonctionController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * FonctionController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/fonction/list", name="list_fonction")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function liste()
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new Fonction();
        $formFonction = $this->createForm(FonctionType::class, $entite);
        $listFonctions = $em->getRepository(Fonction::class)->findAll();
        return $this->render('fonction/list_fonction.html.twig', array(
            'fonctions' => $listFonctions,
            'formFonction' => $formFonction->createView(),
            'isFonction' => true
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/fonction/create", name="create_fonction", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new Fonction();
        $form = $this->createForm(FonctionType::class, $entite, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_fonction')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            try {

                $em->persist($entite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('list_fonction');
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
                return $this->redirectToRoute('list_fonction');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);
        }

        return $this->render('user/create_user.html.twig', array(
            'form' => $form->createView(),
            'title' => 'fetra'
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/fonction/{id}/edit", name="edit_fonction", options={"expose"=true}, methods={"POST","GET"})
     * @ParamConverter("fonction", class="App\Entity\Fonction")
     * @param Fonction|null $fonction
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, Fonction $fonction = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($fonction) {
            $form = $this->createForm(FonctionType::class, $fonction, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_fonction', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {

                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));

                }
                return $this->redirectToRoute('list_fonction');
            }
            return $this->render('user/create_user.html.twig', array(
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
     * @Route("/fonction/{id}/delete", name="delete_fonction", methods={"DELETE","GET"}, options={"expose"=true})
     * @ParamConverter("fonction", class="App\Entity\Fonction")
     * @param Request $request
     * @param Fonction|null $fonction
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function deleteUser(Request $request, Fonction $fonction = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($fonction) {

            $form = $this->createForm(FonctionType::class, $fonction, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('delete_fonction', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($fonction);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('list_fonction');
            }
            return $this->render('user/delete_form_user.html.twig', array(
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
