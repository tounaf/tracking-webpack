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
use App\Utils\Fonctions;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class UtilisateurController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class UtilisateurController extends Controller
{
    /**
     * @Route("/user/list", options={"expose"=true}, name="list_user")
     */
    public function listUser()
    {
        $em = $this->getDoctrine()->getManager();
        $user = new FosUser();
        $formUser = $this->createForm(FosUserType::class, $user);
        $listUser = $em->getRepository(FosUser::class)->findAll();
        return $this->render('user/list_user.html.twig',array(
            'users' => $listUser,
            'formUser' => $formUser->createView()
        ));
    }

    /**
     *
     * @param Request $request
     * @Route("/user/create", name="create_user", methods={"POST"}, options={"expose"=true})
     *
     */
    public function edit(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new FosUser();
        $form = $this->createForm(FosUserType::class, $user)->handleRequest($request);
        $response = new JsonResponse();
        $message = array(
            'status'=>200,
            'message' =>"Creation ok",
            'type' => 'success'
        );
        if ($form->isSubmitted()) {
            $newPassword = Fonctions::generatePassword();
            try {

                $user->setPlainPassword($newPassword);
                $user->setUsername($user->getEmail());
                $em->persist($user);
                $em->flush();
            } catch (\Exception $exception) {
                $message['message'] = $exception->getMessage();
                $message['status'] = 500;
                $message['type'] = 'danger';
            }
        }

        return $response->setData($message);
    }

    /**
     * @param Request $request
     * @Route("/user/verify-email/{email}", name="verify_email", methods={"GET"}, options={"expose": true})
     * @return JsonResponse
     */
    public function emailIsExist(Request $request)
    {
        $email = $request->get('email');
        $user = $this->getDoctrine()->getManager()->getRepository(FosUser::class)->findBy(array('email'=> $email ));
        $data = array(
            'message' => '',
            'status' => '',
            'type' => ''
        );
        if ($user) {
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