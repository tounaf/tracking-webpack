<?php

namespace App\Controller\Admin;

use App\Entity\Dossier;
use App\Form\DossierType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class DossierController
 * @package App\Controller\Admin
 * @Route("/admin")
 */
class DossierController extends Controller
{

    /**
     *
     * @Route("/dossier/create", name="create_dossier", methods={"GET","POST"})
     * @param Request $request
     * @param Dossier|null $dossier
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createDossier(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $dossier = new Dossier();

        $form = $this->createForm(DossierType::class, $dossier)->handleRequest($request);
        if ($form->isSubmitted()) {
            $em->persist($dossier);
            $em->flush();
        }

        return $this->render('dossier/form.html.twig', array(
            'form' => $form->createView()
        ));

    }
}
