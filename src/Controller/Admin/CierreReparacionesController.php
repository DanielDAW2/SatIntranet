<?php

namespace App\Controller\Admin;

use App\Entity\CierreReparaciones;
use App\Form\CierreReparacionesType;
use App\Repository\CierreReparacionesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\UsuarioRepository;
use App\Repository\OrdentrabajoRepository;
use App\Repository\ConfCierreReparacionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\Ordentrabajo;

/**
 * @Route("/cierre/reparaciones")
 */
class CierreReparacionesController extends AbstractController
{
    private $otController;
    
    public function __construct(OrdentrabajoController $ot) {
        
        $this->otController = $ot;
    }
    
    /**
     * @Route("/", name="cierre_reparaciones_index", methods={"GET"})
     */
    public function index(CierreReparacionesRepository $cierreReparacionesRepository): Response
    {
        return $this->render('cierre_reparaciones/index.html.twig', [
            'cierre_reparaciones' => $cierreReparacionesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cierre_reparaciones_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cierreReparacione = new CierreReparaciones();
        $form = $this->createForm(CierreReparacionesType::class, $cierreReparacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cierreReparacione);
            $entityManager->flush();

            return $this->redirectToRoute('cierre_reparaciones_index');
        }

        return $this->render('cierre_reparaciones/new.html.twig', [
            'cierre_reparacione' => $cierreReparacione,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cierre_reparaciones_show", methods={"GET"})
     */
    public function show(CierreReparaciones $cierreReparacione): Response
    {
        return $this->render('cierre_reparaciones/show.html.twig', [
            'cierre_reparacione' => $cierreReparacione,
        ]);
    }
    
    /**
     * @Route("/orden/{n_orden}", name="cierre_reparaciones_orden")
     * @param CierreReparacionesRepository $repo
     * @param Ordentrabajo $ot
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getByOrder(CierreReparacionesRepository $repo, Ordentrabajo $ot)
    {
        $cierres = $repo->findBy(["NOrden"=>$ot->getNOrden()]);
        
        return $this->render("/cierre_reparaciones/index.html.twig",["cierre_reparaciones"=>$cierres,"ot"=>$ot->getNOrden()]);
        
    }

    /**
     * @Route("/{id}/edit", name="cierre_reparaciones_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CierreReparaciones $cierreReparacione): Response
    {
        $form = $this->createForm(CierreReparacionesType::class, $cierreReparacione);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cierre_reparaciones_index', [
                'id' => $cierreReparacione->getId(),
            ]);
        }

        return $this->render('cierre_reparaciones/edit.html.twig', [
            'cierre_reparacione' => $cierreReparacione,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cierre_reparaciones_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CierreReparaciones $cierreReparacione): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cierreReparacione->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cierreReparacione);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cierre_reparaciones_index');
    }
    
    /**
     * @IsGranted("ROLE_TECNIC")
     * @Route("/generate", name="cierre_reparaciones_generate")
     * @param UsuarioRepository $userRepo
     * @param OrdentrabajoRepository $repo
     * @param Request $req
     * @param ConfCierreReparacionRepository $conf
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function generateCierre(UsuarioRepository $userRepo, OrdentrabajoRepository $repo, Request $req, ConfCierreReparacionRepository $conf){
        $cierre = new CierreReparaciones();
        
        $ot = $repo->findOneBy(["n_orden"=>$req->request->get("orden")]);
        $user = $userRepo->find($req->request->get("user"));
        $em = $this->getDoctrine()->getManager();

        $cierre->setNOrden($ot->getNOrden());
        $cierre->setComentario($req->request->get("comment"));
        $cierre->setReparacion($ot);
        $cierre->setFecha(new \DateTime());
        $cierre->setUsuario($user);
        
        switch ($req->request->get("type"))
        {
           case 0:
            $ot->setPresupuestoAceptado(false);
            $newSituacion = $conf->getSituacion($ot->getSituacion()->getId(), false);
         break;
           case 2:
               $ot->setPresupuestoAceptado(true);
               $newSituacion = $conf->getSituacion($ot->getSituacion()->getId(), true);
            break;
        }
        
        if($newSituacion)
        {
            $ot->setSituacion($newSituacion->getSituacionFinal());
            $em->persist($ot);
            $em->persist($cierre);
            $date = new \DateTime();
            $message = $ot->getObservacionesInt()."\n".$date->format("d/m/y")." ".$user->getNombre()." ".$user->getApellidos()." >> ".$req->request->get("comment")." \n";
            
            $this->otController->updateOrder($ot, $message);
            
            $em->flush();
            return new Response($newSituacion->getSituacionFinal()->getNombre());
        }
        return new JsonResponse("Tiquet Generated");
        
    }
}
