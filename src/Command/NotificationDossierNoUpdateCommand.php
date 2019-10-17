<?php

namespace App\Command;

use App\Entity\Dossier;
use App\Service\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerInterface;

class NotificationDossierNoUpdateCommand extends Command
{
    protected static $defaultName = 'notification:dossier-no-update';
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
            ->setName('notification:dossier-no-update')
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $oDossiersNoUpdt = $this->em->getRepository(Dossier::class)->getUserEnChargDssrNoUpdt();
        $context = $this->container->get('router')->getContext();
        $context->setHost($this->container->getParameter('router.request_context.host'));
        $context->setBaseUrl($this->container->getParameter('router.request_context.base_url'));
        $context->setScheme($this->container->getParameter('router.request_context.scheme'));
        $io = new SymfonyStyle($input, $output);
/*        $arg1 = $input->getArgument('arg1');*/
        $output->writeln('Envoi  à :');
        foreach ($oDossiersNoUpdt as $dossier) {
            $output->writeln($dossier['email']);
            $template = $this->container->get('templating')->render('dossier/mail_notification.html.twig', array(
                'reference' => $dossier['referenceDossier'],
                'nomDossier' => $dossier['nomDossier'],
                'societe' => $dossier['libele'],
                'nomPartieAdverse' => $dossier['nomPartieAdverse'],
                'echeance' => $dossier['echeance'],
                'etapeSuivante' => $dossier['libelle'],
                'dossierNoUpdt' =>'dossierNoupdt',
                'application_name' => 'LITIGE',
                'dossier'=>$oDossiersNoUpdt
            ));
            try {
                $this->serviceMailer->sendNotification('Notification de litige',$dossier['email'], $template);
                $io->success('envoi reussi');
            } catch (\Exception $exception) {
                $io->$exception;die();
                $io->warning('Erreur d\'envoi ');
            }

        }
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
