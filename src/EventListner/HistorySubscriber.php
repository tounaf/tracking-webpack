<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 19/09/2019
 * Time: 17:46
 */

namespace App\EventListner;


use App\Entity\Dossier;
use App\Entity\History;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\EventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Serializer\Serializer;

class HistorySubscriber implements EventSubscriber
{
    private $serializer;
    private $container;
    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->serializer = $container->get('serializer');
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

            if (!($entity instanceof Dossier)) {
                return;
            }
            $changesets = $unitOfWork->getEntityChangeSet($entity);
            // serialize data changesets
            $dataserialize = $this->serializer->serialize($changesets, 'json');

            //get class name
            $nameClass = $em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();
            $nameWithOutNameSpace = join('', array_slice(explode('\\', $nameClass), -1));

            $history = new History();
            $history->setClasseName($nameWithOutNameSpace);
            $history->setMetadata($dataserialize);
            $history->setDossier($entity);
            $em->persist($history);
            $unitOfWork->computeChangeSets();
            $metaData = $em->getClassMetadata('App\Entity\History');
            $unitOfWork->computeChangeSet($metaData, $history);
            $em->flush();

        }
    }
}