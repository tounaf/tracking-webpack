<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 26/08/2019
 * Time: 14:45
 */

namespace App\Controller;

use App\Entity\FosUser;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;
use App\Utils\Fonctions;

/**
 * Class UserController
 * @package App\Controller
 * @Route("/user")
 */
class UserController extends Controller
{
    /**
     * @Route("/reset-password", options={"expose"=true}, name="resetting_password_user", methods={"POST"})
     */
    public function resettingPassword(Request $request, TranslatorInterface $translator)
    {
        $response = new JsonResponse();
        $em = $this->getDoctrine()->getManager();
        $email = $request->get('email');
        $data = array(
            'status' =>'500',
            'message' => 'Une erreur est survenu.Veuillez contacter votre administrateur',
            'type' =>'danger'
        );
        $user = $em->getRepository(FosUser::class)->findOneBy(['email' => $email]);
        if ($user && $user->isEnabled()){
            $newPassword = Fonctions::generatePassword();
            $encoder = $this->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $newPassword);
            $user->setPassword($encoded);
            $this->container->get('fos_user.user_manager')->updateUser($user, true);
            $this->sendMail($email, $newPassword, $user->getName());
            $data['message'] = $translator->trans('label.resetting.password.success');
            $data['status'] = 200;
            $data['type'] = 'success';
        } else {
            $data['message'] = $translator->trans('label.resetting.password.error');
            $data['status'] = 401;
            $data['type'] = 'danger';
        }
        return $response->setData($data);
    }

    private function sendMail($email, $newPassword, $name)
    {
        $mailer = $this->get('mailer');
        $subject = 'Mot de passe oublié !';
        $fromEmail = $this->getParameter('fos_user.registration.confirmation.from_email');
        $body = $this->render('user/mailer_resetting_password.html.twig', array('name' => $name, 'newPassword' => $newPassword, 'application_name' => 'LITIGE'));
        $message = new \Swift_Message('Réinitialisation mot de passe');
        $message
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setBody($body)
            ->setTo($email)
            ->setReplyTo($fromEmail);
        $mailer->send($message);

    }
}