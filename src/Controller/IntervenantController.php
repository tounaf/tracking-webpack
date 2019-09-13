<?php

namespace App\Controller;

use App\Entity\Intervenant;
use App\Form\IntervenantType;
use App\Repository\IntervenantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/intervenant")
 */
class IntervenantController extends Controller
{
    /**
     * @Route("/", name="intervenant_index", methods={"GET"})
     */
    public function index()
    {
        return $this->render('intervenant/index.html.twig', [
        ]);
    }

    /**
     * @Route("/getListAvocat", name="liste_avocat",  options={"expose"=true})
     */
    public function paginateAction(Request $request)
    {
        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;

        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];
        $avocats = $this->getDoctrine()->getRepository('App:Intervenant')->search(
            $filters, $start, $length
        );
        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('App:Intervenant')->search($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('App:Intervenant')->search(array(), 0, false))
        );
        foreach ($avocats as $avocat) {
            $output['data'][] = [
                'user' =>$avocat->getUser()?
                    $avocat->getUser()->getLastname().' '.
                    $avocat->getUser()->getName():''
                ,
                'convenu' => $avocat->getConvenu(),
                'payer' => $avocat->getPayer(),
                'reste_payer' => $avocat->getRestePayer(),
                'devise' => $avocat->getDevise()->getLibelle(),
                'prestation' => $avocat->getPrestation()->getLibelle(),
                'statuts' => $avocat->getStatutIntervenant(),
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }

}
