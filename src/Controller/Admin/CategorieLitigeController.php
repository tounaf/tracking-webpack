<?php

namespace App\Controller\Admin;

use App\Entity\CategorieLitige;
use App\Form\CategorieLitigeType;
use App\Repository\CategorieLitigeRepository;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/admin/categorie-litige")
 */
class CategorieLitigeController extends Controller
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
     * @Route("/", name="categorie_litige_index", methods={"GET"}, options={"expose"=true})
     */
    public function index(CategorieLitigeRepository $categorieLitigeRepository): Response
    {
        $catLitige= new CategorieLitige();
        $form = $this->createForm(CategorieLitigeType::class, $catLitige);
        return $this->render('Admin/categorie_litige/index.html.twig', [
            'categorie_litiges' => $categorieLitigeRepository->findAll(),
            'form' => $form->createView(),
            'title' => 'Gestion des catégories litige'
        ]);
    }

    /**
     * @Route("/new", name="categorie_litige_new", methods={"GET","POST"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function new(Request $request)
    {
        $categorieLitige = new CategorieLitige();
        $form = $this->createForm(CategorieLitigeType::class, $categorieLitige, [
            'method' => 'POST',
            'action' => $this->generateUrl('categorie_litige_new')
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
                $entityManager->persist($categorieLitige);
                $entityManager->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('categorie_litige_index');
            }
            catch (DBALException $ex){
                $this->get('session')->getFlashBag()->add('danger',$this->translator->trans('label.champs.obli'));
                return $this->redirectToRoute('categorie_litige_index');
            }
            catch (\Exception $exception){
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.error.create'));
                return $this->redirectToRoute('categorie_litige_index');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);

            return $this->redirectToRoute('categorie_litige_index');
        }

        return $this->render('Admin/_create_user.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @param Request $request
     * @Route("/{id}/edit", name="categorie_litige_edit", options={"expose"=true}, methods={"GET","POST"})
     * @ParamConverter("categorieLitige", class="App\Entity\CategorieLitige")
     * @param CategorieLitige|null $categorieLitige
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, CategorieLitige $categorieLitige = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if($categorieLitige){

            $form = $this->createForm(CategorieLitigeType::class, $categorieLitige, [
                'method' => 'POST',
                'action' => $this->generateUrl('categorie_litige_edit', ['id' => $id])
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
                return $this->redirectToRoute('categorie_litige_index');
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
     * @Route("/{id}/delete", name="categorie_litige_delete", methods={"DELETE","GET"}, options={"expose"=true})
     * @param Request $request
     * @ParamConverter("categorieLitige", class="App\Entity\CategorieLitige")
     * @param CategorieLitige|null $categorieLitige
     */
    public function delete(Request $request, CategorieLitige $categorieLitige = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($categorieLitige) {

            $form = $this->createForm(CategorieLitigeType::class, $categorieLitige, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('categorie_litige_delete', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($categorieLitige);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('categorie_litige_index');
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
