<?php

namespace App\Controller;

use App\Entity\Auxiliaires;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class AuxiliairesController extends Controller
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * UtilisateurController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @Route("/auxiliaires", name="auxiliaires")
     */
    public function index()
    {
        return $this->render('auxiliaires/index.html.twig', [
        ]);
    }
    /**
     * @Route("/getListAuxiliaire", name="liste_auxiliaire",  options={"expose"=true})
     */
    public function ajaxlistAuxiliaire(Request $request)
    {
        $length = $request->get('length');
        $length = $length && ($length!=-1)?$length:0;

        $start = $request->get('start');
        $start = $length?($start && ($start!=-1)?$start:0)/$length:0;

        $search = $request->get('search');
        $filters = [
            'query' => @$search['value']
        ];
        $auxis = $this->getDoctrine()->getRepository('App:Auxiliaires')->ajaxlistAuxiliaire(
            $filters, $start, $length
        );
        $output = array(
            'data' => array(),
            'recordsFiltered' => count($this->getDoctrine()->getRepository('App:Auxiliaires')->ajaxlistAuxiliaire($filters, 0, false)),
            'recordsTotal' => count($this->getDoctrine()->getRepository('App:Auxiliaires')->ajaxlistAuxiliaire(array(), 0, false))
        );
        foreach ($auxis as $auxi) {
            $output['data'][] = [
                'id'=>$auxi->getId(),
                'user' =>$auxi->getUser()?
                    $auxi->getUser()->getLastname().' '.
                    $auxi->getUser()->getName():''
                ,
                'convenu' => $auxi->getConvenu(),
                'payer' => $auxi->getPayer(),
                'reste_payer' => $auxi->getRestePayer(),
                'devise' => $auxi->getDevise()->getLibelle(),
                'prestation' => $auxi->getPrestation()->getLibelle(),
                'statuts' => $auxi->getStatutIntervenant(),
            ];
        }

        return new Response(json_encode($output), 200, ['Content-Type' => 'application/json']);
    }


}
