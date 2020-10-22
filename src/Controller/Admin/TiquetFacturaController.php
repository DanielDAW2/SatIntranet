<?php

namespace App\Controller\Admin;

use App\Entity\TiquetFactura;
use App\Form\TiquetFacturaType;
use App\Repository\TiquetFacturaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\OrdentrabajoRepository;
use App\Entity\CierreReparaciones;
use App\Repository\UsuarioRepository;
use App\Repository\ConfCierreReparacionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
* @IsGranted("ROLE_TECNIC")
 * @Route("/tiquet/factura")
 */
class TiquetFacturaController extends AbstractController
{
    
    private $otController;
    
    public function __construct(OrdentrabajoController $ot)
    {
        $this->otController = $ot;
    }
    /**
	 * @IsGranted("ROLE_CENTRAL")
     * @Route("/", name="tiquet_factura_index", methods={"GET"})
     */
    public function index(TiquetFacturaRepository $tiquetFacturaRepository): Response
    {
        return $this->render('tiquet_factura/index.html.twig', [
            'tiquet_facturas' => $tiquetFacturaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tiquet_factura_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tiquetFactura = new TiquetFactura();
        $form = $this->createForm(TiquetFacturaType::class, $tiquetFactura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tiquetFactura);
            $entityManager->flush();

            return $this->redirectToRoute('tiquet_factura_index');
        }

        return $this->render('tiquet_factura/new.html.twig', [
            'tiquet_factura' => $tiquetFactura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tiquet_factura_show", methods={"GET"})
     */
    public function show(TiquetFactura $tiquetFactura): Response
    {
        return $this->render('tiquet_factura/show.html.twig', [
            'tiquet_factura' => $tiquetFactura,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tiquet_factura_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TiquetFactura $tiquetFactura): Response
    {
        $form = $this->createForm(TiquetFacturaType::class, $tiquetFactura);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tiquet_factura_index', [
                'id' => $tiquetFactura->getId(),
            ]);
        }

        return $this->render('tiquet_factura/edit.html.twig', [
            'tiquet_factura' => $tiquetFactura,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tiquet_factura_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TiquetFactura $tiquetFactura): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tiquetFactura->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tiquetFactura);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tiquet_factura_index');
    }
    
    /**
     * @IsGranted("ROLE_TECNIC")
     * @Route("/generate", name="tiquet_factura_generate")
     * @param UsuarioRepository $userRepo
     * @param OrdentrabajoRepository $repo
     * @param Request $req
     * @param ConfCierreReparacionRepository $conf
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function generateTiquetFactura(UsuarioRepository $userRepo, OrdentrabajoRepository $repo, Request $req, ConfCierreReparacionRepository $conf){
        $cierre = new CierreReparaciones();
        
        $ot = $repo->findOneBy(["n_orden"=>$req->request->get("orden")]);
        $user = $userRepo->find($req->request->get("user"));
        $em = $this->getDoctrine()->getManager();
        IF($req->request->get("tiquet"))
        {
            $tiquet = new TiquetFactura();
            $date = \DateTime::createFromFormat('d-m-y', str_replace("/","-",$req->request->get("fechatiquet")));
			if($date ===false)
			{
				$date = \DateTime::createFromFormat('d-m-Y', str_replace("/","-",$req->request->get("fechatiquet")));
			}
            $tiquet->setFecha($date);
            $tiquet->setOrdentrabajo($ot);
            $tiquet->setNumero($req->request->get("tiquet"));
            $em->persist($tiquet);
            $cierre->setTiquetCierre($tiquet);
            $eTiquet = $tiquet->getNumero()." ".$tiquet->getFecha()->format("d/m/y");
        }

        $cierre->setNOrden($ot->getNOrden());
        $cierre->setComentario($req->request->get("comment"));
        $cierre->setReparacion($ot);
        $cierre->setFecha(new \DateTime());
        $cierre->setUsuario($user);
        
        $newSituacion = $conf->getSituacion($ot->getSituacion()->getId(), false);
        if($newSituacion)
        {
            $ot->setSituacion($newSituacion->getSituacionFinal());
            $em->persist($ot);
            $em->persist($cierre);
            $em->flush();
            $date = new \DateTime();
           
            $message = $ot->getObservacionesInt()."\n".$date->format("d/m/y H:i")." ".$user->getNombre()." ".$user->getApellidos()." >> ".$req->request->get("comment")." \n";
            $this->otController->updateOrder($ot, $message, $eTiquet);

            return new Response($ot->getSituacion()->getNombre());
        }
       
        
        return new JsonResponse("Tiquet Generated");
        
    }
}
