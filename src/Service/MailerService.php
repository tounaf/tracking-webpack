<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 30/08/2019
 * Time: 15:22
 */

namespace App\Service;

use Hoa\Exception\Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MailerService
{

    private $mailer;
    private $template;
    private $container;
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->mailer = $container->get('mailer');
        $this->template = $container->get('templating');

    }

    /**
     * @param $email
     * @param $newPassword
     * @param $name
     * @param null $subject
     * @param $isCreation
     * @return int
     * @throws \Twig\Error\Error
     */
    public function sendMail($email, $newPassword, $name, $subject = null, $isCreation = false)
    {
        $mailer = $this->mailer;
        $fromEmail = $this->container->getParameter('fos_user.registration.confirmation.from_email');
        $body = $this->template->render('user/mailer_resetting_password.html.twig', array(
            'name' => $name,
            'newPassword' => $newPassword,
            'application_name' => 'LITIGE',
            'creationCompte' => $isCreation
            ));
        $message = new \Swift_Message('Réinitialisation mot de passe');
        $message
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setBody($body, 'text/html')
            ->setTo($email)
            ->setReplyTo($fromEmail);

        return  $mailer->send($message);

    }

    /**
     * @param $subject
     * @param $email
     * @param $template
     * @return int
     */
    public function sendNotification($subject, $email, $template)
    {
        $fromEmail = $this->container->getParameter('fos_user.registration.confirmation.from_email');
        $message = new \Swift_Message('Notification');
        $mailer = new \Swift_Mailer(new \Swift_SmtpTransport($this->container->getParameter('host_mailer')));
        $message
            ->setSubject($subject)
            ->setFrom($fromEmail)
            ->setBody($template, 'text/html')
            ->setTo($email)
            ->setReplyTo($fromEmail);
        $mailer->send($message);

    }
}