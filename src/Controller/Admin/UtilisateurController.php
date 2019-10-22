<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 27/08/2019
 * Time: 17:52
 */

namespace App\Controller\Admin;

use App\Entity\FosUser;
use App\Form\FosUserType;
use App\Service\MailerService;
use App\Utils\Fonctions;
use Doctrine\DBAL\DBALException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;


/**
 * Class UtilisateurController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class UtilisateurController extends Controller
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
     * @Route("/user/list", options={"expose"=true}, name="list_user")
     */
    public function listUser()
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $this->getUser();
        $isSuperUser = $currentUser->hasRole('ROLE_SUPERADMIN')? true: false;
        $user = new FosUser();
        $formUser = $this->createForm(FosUserType::class, $user);
        $listUser = $em->getRepository(FosUser::class)->listUserBySociete($currentUser, $isSuperUser);
        return $this->render('user/list_user.html.twig', array(
            'users' => $listUser,
            'formUser' => $formUser->createView(),
            'title' => 'Gestion des utilisateurs'
        ));
    }

    /**
     * @param Request $request
     * @param MailerService $mailerService
     * @Route("/user/create", name="create_user", methods={"POST","GET"}, options={"expose"=true})
     * @return JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, MailerService $mailerService)
    {

        $em = $this->getDoctrine()->getManager();
        $user = new FosUser();
        $subject = $this->translator->trans('label.new.account');
        $form = $this->createForm(FosUserType::class, $user, array(
            'method' => 'POST',
            'action' => $this->generateUrl('create_user')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            $newPassword = Fonctions::generatePassword();
            try {

                $user->setPlainPassword($newPassword);
                $user->setUsername($user->getEmail());
                $em->persist($user);
                $em->flush();
                $mailerService->sendMail($user->getEmail(), $newPassword, $user->getName(), $subject, true);
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
                return $this->redirectToRoute('list_user');
            }catch (DBALException $ex){
                $this->get('session')->getFlashBag()->add('danger',$this->translator->trans('label.champs.obli'));
                return $this->redirectToRoute('list_user');
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
                return $this->redirectToRoute('list_user');
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
            return $response->setData($message);
        }
        if($request->isXmlHttpRequest()){
//        return $this->render('Admin/_create_user.html.twig', array(
        return $this->render('user/create_user.html.twig', array(
            'form' => $form->createView(),
            'title' => 'fetra'
        ));
        }else{
           // return new JsonResponse(array('status' => 'Error'),400);
           throw new NotFoundHttpException();
        }
    }

    /**
     * @param Request $request
     *
     * @Route("/user/{id}/edit", name="edit_user", options={"expose"=true}, methods={"POST","GET"})
     * @ParamConverter("user", class="App\Entity\FosUser")
     * @param FosUser|null $fosUser
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editUser(Request $request, FosUser $user = null)
    {
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($user) {
            $form = $this->createForm(FosUserType::class, $user, array(
                'method' => 'POST',
                'action' => $this->generateUrl('edit_user', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {

                    $this->getDoctrine()->getManager()->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.edit.error'));

                }
                return $this->redirectToRoute('list_user');
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
     * @Route("/user/{id}/delete", name="delete_user", methods={"DELETE","GET"}, options={"expose"=true})
     * @ParamConverter("user", class="App\Entity\FosUser")
     * @param Request $request
     * @param FosUser $user
     * @return \Symfony\Component\HttpFoundation\Response|JsonResponse
     */
    public function deleteUser(Request $request, FosUser $user = null)
    {
        $em = $this->getDoctrine()->getManager();
        $id = $request->get('id');
        $response = new JsonResponse();
        if ($user) {

            $form = $this->createForm(FosUserType::class, $user, array(
                "remove_field" => true,
                "method" => "DELETE",
                "action" => $this->generateUrl('delete_user', array('id' => $id))
            ))->handleRequest($request);
            if ($form->isSubmitted()) {
                try {
                    $em->remove($user);
                    $em->flush();
                    $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
                } catch (\Exception $exception) {
                    $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
                }
                return $this->redirectToRoute('list_user');
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

    /**
     * @param Request $request
     * @Route("/user/verify-email/{email}", name="verify_email", methods={"GET"}, options={"expose": true})
     * @return JsonResponse
     */
    public function emailIsExist(Request $request)
    {
        $email = $request->get('email');
        //utiliser pour edit
        $oldEmail = $request->query->get('oldEmail');


        $user = $this->getDoctrine()->getManager()->getRepository(FosUser::class)->findBy(array('email' => $email));
        $data = array(
            'message' => '',
            'status' => '',
            'type' => ''
        );
        if ($oldEmail == $email) {
            $data['message'] = false;
            $data['status'] = 200;
            $data['type'] = 'success';
        } elseif ($user) {
            $data['message'] = true;
            $data['status'] = 403;
            $data['type'] = 'danger';
        } else {
            $data['message'] = false;
            $data['status'] = 200;
            $data['type'] = 'success';
        }
        return new JsonResponse($data);
    }
}