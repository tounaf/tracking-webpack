<?php

namespace App\Controller;

use App\Entity\Cloture;
use App\Entity\Dossier;
use App\Entity\PjCloture;
use App\Form\ClotureType;
use App\Form\PjClotureType;
use App\Repository\ClotureRepository;
use Psr\Container\ContainerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\FileBag;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * @Route("/admin/dossier/cloture")
 */
class ClotureController extends Controller
{
    private $entityManager;
    private $repository;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    private $session;
    private $id ;
    // Set up all necessary variable
    protected function initialise()
    {
        $this->entityManager = $this->getDoctrine()->getManager();
        $this->repository = $this->entityManager->getRepository('App:PjCloture');
        $this->translator = $this->get('translator');
    }
    /**
     *
     * UtilisateurController constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator, SessionInterface $session)
    {
        $this->translator = $translator;
        $this->session = $session;
        $this->id = $this->session->get('id');
    }


    /**
     * @Route("/new/{id}", name="cloture_new", methods={"GET","POST"}, options={"expose"=true})
     */
    public function new(Request $request, Dossier $dossier=null)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $dossier = $entityManager->getRepository(Dossier::class)->find($this->id);
        $dossierExist = $entityManager->getRepository(Cloture::class)->findOneBy([
                'dossier'=> $dossier->getId()
            ]
        );
        if ($dossier->getCloture()){
            $cloture = $dossier->getCloture();
        } else {

            $cloture = new Cloture();
        }
        $formCloture = $this->createForm(PjClotureType::class, new PjCloture(), array(
            'action' => $this->generateUrl('upload_file'),
            'method' => 'POST'
        ));
        $form = $this->createForm(ClotureType::class, $cloture, array(
            'action' =>$this->generateUrl('cloture_new', array('id' => $request->get('id')))
        ));
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $datecloture = $request->get('cloture')['dateCloture'];
            $date = new \DateTime($datecloture);
          try{
              $entityManager = $this->getDoctrine()->getManager();
              $cloture->setDateCloture($date);
              $cloture->setDossier($dossier);
              if (!$dossierExist){
                  $entityManager->persist($cloture);
                  $entityManager->flush();
                  $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.create.success'));
              }
              else{
                  $entityManager->flush();
                  $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.edit.success'));
              }

/*              $this->session->set('idCloture',$cloture->getId());*/
          } catch (Exception $exception){
              $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.create.error'));
          }
            return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'cloture'));
        }
        return $this->render('cloture/_form.html.twig', [
            'cloture' => $cloture,
            'formCloture' => $form->createView(),
            'formPj' => $formCloture->createView()
        ]);
    }
    /**
     * @Route("/file/render", name="upload_file_render", options={"expose"=true}, methods={"POST"})
     * @Route("/file/upload", name="upload_file", options={"expose"=true}, methods={"POST"})
     */
    public function savepjinf(Request $request)
    {
        $pjCloture = new PjCloture();
        $form = $this->createForm(PjClotureType::class, $pjCloture, array(
            'method' => 'POST',
            'action' => $this->generateUrl('upload_file')
        ))->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $cloture = $em->getRepository(Cloture::class)->find($request->get('id'));
            $pjCloture->setCloture($cloture);
            $directory = $this->get('uploaderfichier');
            $pjCloture->getUploadDir($directory);
            $em->persist($pjCloture);
            $em->flush();
            return new JsonResponse(array(
                'message' => 'upload termineé',
                'statut' => 200
            ));
        }
        return $this->render('cloture/pjform.html.twig', array(
            'formPj' => $form->createView()
        ));
    }

    /**
     *
     * @Route("/list-pj/{id}", name="liste_pj_cloture",  options={"expose"=true})
     * @param Request $request
     * @return JsonResponse
     * @throws
     */
    public function ajaxlistPj(Request $request)
    {
        $idCloture = $request->get('id');
        $response = new JsonResponse();
        $draw = $request->get('draw');
        $length = $request->get('length');
        $start = $request->get('start');
        $search = $request->get('search');
        $filters = [
            'query' => $search['value'],
        ];
        $vOrder = $request->get('order');
        $orderBy = $vOrder[0]['column'];
        $order = $vOrder[0]['dir'];
        $extraParams = array(
            'filters' => $filters,
            'start' => $start,
            'length' => $length,
            'orderBy' => $orderBy,
            'order' => $order,
        );
        $listIntervenant = $this->getDoctrine()->getRepository(PjCloture::class)->listPjCloture($extraParams, $idCloture, false);
        $nbrRecords = $this->getDoctrine()->getRepository(PjCloture::class)->listPjCloture($extraParams, $idCloture, true);
        $response->setData(array(
            'draw' => (int)$draw,
            'recordsTotal' => (int)$nbrRecords[0]['record'],
            'recordsFiltered' => (int)$nbrRecords[0]['record'],
            'data' => $listIntervenant,
        ));
        return $response;
    }

    /**
     *
     * @Route("/pj-cloture/delete/{id}", name="delete_pj_cloture", options={"expose"=true})
     * @param PjCloture|null $pjCloture
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteSubDossier(PjCloture $pjCloture = null)
    {
        if ($pjCloture) {
            $em = $this->getDoctrine()->getManager();
            try {
                $em->remove($pjCloture);
                $em->flush();
                $this->get('session')->getFlashBag()->add('success', $this->translator->trans('label.delete.success'));
            } catch (\Exception $exception) {
                $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
            }
            return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'cloture'));
        }
        $this->get('session')->getFlashBag()->add('danger', $this->translator->trans('label.delete.error'));
        return $this->redirectToRoute('render_edit_dossier', array('id' => $this->id, 'currentTab' => 'cloture'));
    }

}
