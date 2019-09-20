<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 19/09/2019
 * Time: 17:46
 */

namespace App\EventListner;


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
//        dump($entities);
//        $em->removeEventListener('onFlush', $this);
//        $eventArgs
        $em->getEventManager()->removeEventListener(array('preUpdate'), $this);
        foreach ($entities as $entity) {

            $changesets = $unitOfWork->getEntityChangeSet($entity);

            $dataserialize = $this->serializer->serialize($changesets, 'json');

            $nameClass = $em->getMetadataFactory()->getMetadataFor(get_class($entity))->getName();

            $nameWithOutNameSpace = join('', array_slice(explode('\\', $nameClass), -1));

            $history = new History();
            $history->setClasseName($nameWithOutNameSpace);
            $history->setMetadata($dataserialize);
//            $history->setNewValue();
            $em->persist($history);
            $unitOfWork->computeChangeSets();
            $metaData = $em->getClassMetadata('App\Entity\History');
            $unitOfWork->computeChangeSet($metaData, $history);
            $em->flush();
            $data = json_encode($history->getMetadata());
//            dump(json_decode($data));
//            die;
//            foreach ($changesets as $column => $changeset) {
////                $this->addToHistory($entity, $column, $changeset, $em);
//                dump($changeset);
//                dump($column);

//                $metaData = $em->getClassMetadata('App\Entity\History');
////                dump($metaData);die;

//                dump($history);//die;
//                $em->flush();
//            }//die;

        }
    }

    private function addToHistory($entity, $column, $changeset, $em)
    {
        $history = new History();
        $history->setColumnUpdated($column);
        $history->setOldValue($changeset[0]);
        $history->setNewValue($changeset[1]);
        $em->persist($history);
        $em->flush();
    }

}