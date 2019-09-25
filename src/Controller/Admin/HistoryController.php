<?php
/**
 * Created by PhpStorm.
 * User: nambinina2
 * Date: 23/09/2019
 * Time: 09:54
 */

namespace App\Controller\Admin;


use App\Entity\Dossier;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class HistoryController extends Controller
{
    /**
     * @Route("/historique/list/{id}", name="list_historique")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(Dossier $dossier = null)
    {
        $histories = $dossier->getHistories();

        $metadatas = array();
        foreach ($histories as $history) {
            $metadata = json_decode($history->getMetadata(), true);
            $metadatas[$history->getId()]=$metadata;
        }
        return $this->render('historique/liste_historique.html.twig', array(
           'histories' => $histories,
            'metadatas' => $metadatas
        ));
    }
}