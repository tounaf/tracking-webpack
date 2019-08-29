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
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class SocieteController
 * @package App\Controller\Admin
 * @Route("/societe")
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
     * @Route("/list", name="list_societe")
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
            'isSociete' => true
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/user/create", name="create_societe", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new Societe();
        $form = $this->createForm(SocieteType::class, $entite, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_user')
        ))->handleRequest($request);
        $response = new JsonResponse();
        $message = array(
            'status' => 200,
            'message' => $this->translator->trans('label.create.success.user'),
            'type' => 'success'
        );
        if ($form->isSubmitted()) {
            try {

                $em->persist($entite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success.user'));
                return $this->redirectToRoute('list_societe');
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create.user'));
                return $this->redirectToRoute('list_societe');
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
}