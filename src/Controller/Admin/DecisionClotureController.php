<?php

namespace App\Controller\Admin;

use App\Entity\DecisionCloture;
use App\Form\DecisionClotureType;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DecisionClotureController
 * @package App\Admin\Controller
 * @Route("/admin")
 */
class DecisionClotureController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * DecisionClotureController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/decision-cloture/list", name="list_decision_cloture")
     */
    public function liste()
    {
        $em = $this->getDoctrine()->getManager();
        $decisionCloture = new DecisionCloture();
        $formDecisionCloture = $this->createForm(DecisionClotureType::class, $decisionCloture);
        $listDecisionCloture = $em->getRepository(DecisionCloture::class)->findAll();
        return $this->render('decision_cloture/list_decision_cloture.html.twig', array(
            'decisionClotures' => $listDecisionCloture,
            'formDecisionCloture' => $formDecisionCloture->createView(),
            'isDecisionCloture' => true,
            'title'  => 'Gestion des décisions de clôture d’un litige',
        ));
    }

    /**
     * @param Request $request
     *
     * @Route("/decision-cloture/create", name="create_decision_cloture", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $entite = new DecisionCloture();
        $form = $this->createForm(DecisionClotureType::class, $entite, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_decision_cloture')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            try {

                $em->persist($entite);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('list_decision_cloture');
            }
            catch (DBALException $ex){
                $this->get('session')->getFlashBag()->add('danger',$this->translator->trans('label.champs.obli'));
                return $this->redirectToRoute('list_decision_cloture');
            }

            catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
                return $this->redirectToRoute('list_decision_cloture');
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
     * @Route("/decision-cloture/{id}/edit", name="edit_decision_cloture", options={"expose"=true}, methods={"POST","GET"})
     * @ParamConverter("dCloture", class="App\Entity\DecisionCloture")
     * @param DecisionCloture|null $dCloture
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function edit(Request $request, DecisionCloture $dCloture = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($dCloture) {
            $form = $this->createForm(DecisionClotureType::class, $dCloture, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_decision_cloture', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {

                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));

                }
                return $this->redirectToRoute('list_decision_cloture');
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
     * @Route("/decision-cloture/{id}/delete", name="delete_decision_cloture", methods={"DELETE","GET"}, options={"expose"=true})
     * @ParamConverter("dCloture", class="App\Entity\DecisionCloture")
     * @param Request $request
     * @param DecisionCloture $dCloture
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function delete(Request $request, DecisionCloture $dCloture = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($dCloture) {

            $form = $this->createForm(DecisionClotureType::class, $dCloture, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('delete_decision_cloture', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($dCloture);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('list_decision_cloture');
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
