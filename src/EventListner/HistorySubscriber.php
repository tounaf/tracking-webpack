<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 19/09/2019
 * Time: 17:46
 */

namespace App\EventListner;


use App\Annotation\TrackableClass;
use App\Annotation\TrackableReader;
use App\Entity\Auxiliaires;
use App\Entity\Dossier;
use App\Entity\History;
use App\Entity\Intervenant;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class HistorySubscriber implements EventSubscriber
{
    private $serializer;
    private $container;
    private $security;
    private $trackable;
    public function __construct(Container $container, Security $security, TrackableReader $trackableReader)
    {
        $this->container = $container;
        $this->serializer = $container->get('serializer');
        $this->security = $security;
        $this->trackable = $trackableReader;
    }

    public function getSubscribedEvents()
    {
       return [
           'preUpdate'
       ];
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {
        $em= $eventArgs->getEntityManager();
        $unitOfWork = $em->getUnitOfWork();
        $entities =   $unitOfWork->getScheduledEntityUpdates();
        //supprimer cet event pour eviter de boucler sur flush lors de la migration de history
        $em->getEventManager()->removeEventListener(array('preUpdate'), $this);
        foreach ($entities as $entity) {

            // ne fait rien si l'entite n'est pas trackable
            if (!$this->trackable->isTrackable($entity)) {
                return;
            }


            $changesets = $unitOfWork->getEntityChangeSet($entity);

            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));
            $encoder = new JsonEncoder();

            $normalizer = new ObjectNormalizer($classMetadataFactory);
            $serializer = new Serializer([$normalizer], [$encoder]);

            if ($entity instanceof Dossier) {
                $dossier = $entity;
            } else if ($entity) {
                $dossier = $entity->getDossier();
                $normalizer->setIgnoredAttributes(['dossier']);
            } else {
                return;
            }
            
            // serialize data changesets
            $data = $serializer->normalize($changesets,null,['groups'=> ['groupe1']]);
            $dataserialize = $serializer->serialize($data, 'json');
            //get class name
            $nameClass = $em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
            $nameWithOutNameSpace = join('', array_slice(explode('\\', $nameClass), -1));
            $history = new History();
            $history->setClasseName($nameWithOutNameSpace);
            $history->setMetadata($dataserialize);
            $history->setUser($this->security->getToken()->getUser());
            $history->setDossier($dossier);
            $em->persist($history);
            $unitOfWork->computeChangeSets();
            $metaData = $em->getClassMetadata('App\Entity\History');
            $unitOfWork->computeChangeSet($metaData, $history);
            $em->flush();

        }
    }


}