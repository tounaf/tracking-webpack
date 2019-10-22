<?php

namespace App\Controller\Admin;

use App\Entity\InformationPj;
use App\Form\InformationPjType;
use App\Repository\InformationPjRepository;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("admin/information-pj")
 */
class InformationPjController extends Controller
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
     * @Route("/", name="information_pj_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(InformationPjRepository $InformationPjRepository): Response
    {
        $information_pj = new InformationPj();
        $form = $this->createForm(InformationPjType::class, $information_pj);
        return $this->render('Admin/information_pj/index.html.twig', [
            'information_pjs' => $InformationPjRepository->findAll(),
            'form' => $form->createView(),
            'title' => 'Gestion des informations pièces jointes'
        ]);
    }

    /**
     * @Route("/new", name="information_pj_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $info = new InformationPj();
        $form = $this->createForm(InformationPjType::class, $info, [
            'method' => 'POST',
            'action' => $this->generateUrl('information_pj_new')
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
                $entityManager->persist($info);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('information_pj_index');
            }
            catch (DBALException $ex){
                $this->get('session')->getFlashBag()->add('danger',$this->translator->trans('label.champs.obli'));
                return $this->redirectToRoute('information_pj_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('information_pj_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('information_pj_index');
        }

        return $this->render('Admin/_create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="information_pj_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("info", class="App\Entity\InformationPj")
     * @param InformationPj|null $info
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, InformationPj $info = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($info){

            $form = $this->createForm(InformationPjType::class, $info, [
                'method' => 'POST',
                'action' => $this->generateUrl('information_pj_edit', ['id' => $id])
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
                return $this->redirectToRoute('information_pj_index');
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
     * @Route("/{id}/delete", name="information_pj_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("info", class="App\Entity\InformationPj")
     * @param InformationPj|null $info
     */
    public function delete(Request $request, InformationPj $info = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($info) {

            $form = $this->createForm(InformationPjType::class, $info, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('information_pj_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($info);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('information_pj_index');
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
