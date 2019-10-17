<?php

namespace App\Command;

use App\Entity\Dossier;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Hoa\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SendNotificationCommand extends Command
{
    protected static $defaultName = 'send:notification';
    private $em;
    private $container;
    private $serviceMailer;
    public function __construct(string $name = null, EntityManagerInterface $entityManager, ContainerInterface $container, MailerService $mailerService)
    {
        $this->em = $entityManager;
        $this->container = $container;
        $this->serviceMailer = $mailerService;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this
            ->setName('send:notification')
            ->setDescription('Envoi notification à un utilisateur en charge du dossier')
            ->setHelp('This command allow to send mail to user in charge of litige')
            ->addArgument('email', InputArgument::OPTIONAL, 'Email utilisateur')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oDossiers = $this->em->getRepository(Dossier::class)->getEmailUserEnCharge();
        $context = $this->container->get('router')->getContext();
        $context->setHost($this->container->getParameter('router.request_context.host'));
        $context->setBaseUrl($this->container->getParameter('router.request_context.base_url'));
        $context->setScheme($this->container->getParameter('router.request_context.scheme'));

        $io = new SymfonyStyle($input, $output);
        $output->writeln('Envoi  à :');
        foreach ($oDossiers as $dossier) {
            $output->writeln($dossier['email']);
            $template = $this->container->get('templating')->render('dossier/mail_notification.html.twig', array(
                'reference' => $dossier['referenceDossier'],
                'nomDossier' => $dossier['nomDossier'],
                'societe' => $dossier['libele'],
                'nomPartieAdverse' => $dossier['nomPartieAdverse'],
                'echeance' => $dossier['echeance'],
                'etapeSuivante' => $dossier['libelle'],
                'application_name' => 'LITIGE',
                'dossier'=>$oDossiers
            ));
            try {
                $this->serviceMailer->sendNotification('Notification de litige',$dossier['email'], $template);
                $io->success('envoi reussi');
            } catch (\Exception $exception) {
                $io->$exception;die();
                $io->warning('Erreur d\'envoi ');
            }

        }
        $io->success('Traitement terminé');
    }
}
