<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 29/08/2019
 * Time: 16:18
 */

namespace App\Controller\Admin;


use App\Entity\Societe;
use App\Form\SocieteType;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SocieteController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class SocieteController extends Controller
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
     * @Route("/societe/list", name="list_societe")
     */
    public function liste()
    {
        $em = $this->getDoctrine()->getManager();
        $societe = new Societe();
        $formSociete = $this->createForm(SocieteType::class, $societe);
        $listSociete = $em->getRepository(Societe::class)->findAll();
        return $this->render('societe/list_societe.html.twig', array(
            'societes' => $listSociete,
            'formSociete' => $formSociete->createView(),
            'isSociete' => true,
            'title' => 'Gestion de sociétés/entités',
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/societe/create", name="create_societe", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new Societe();
        $form = $this->createForm(SocieteType::class, $entite, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_societe')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            try {

                $em->persist($entite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('list_societe');
            }
            catch (DBALException $ex){
                $this->get('session')->getFlashBag()->add('danger',$this->translator->trans('label.champs.obli'));
                return $this->redirectToRoute('list_societe');
            }
            catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
                return $this->redirectToRoute('list_societe');
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
     * @Route("/societe/{id}/edit", name="edit_societe", options={"expose"=true}, methods={"POST","GET"})
     * @ParamConverter("societe", class="App\Entity\Societe")
     * @param Societe|null $societe
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, Societe $societe = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($societe) {
            $form = $this->createForm(SocieteType::class, $societe, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_societe', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {

                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));

                }
                return $this->redirectToRoute('list_societe');
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
     * @Route("/societe/{id}/delete", name="delete_societe", methods={"DELETE","GET"}, options={"expose"=true})
     * @ParamConverter("user", class="App\Entity\Societe")
     * @param Request $request
     * @param Societe $societe
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function deleteUser(Request $request, Societe $societe = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($societe) {

            $form = $this->createForm(SocieteType::class, $societe, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('delete_societe', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($societe);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('list_societe');
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