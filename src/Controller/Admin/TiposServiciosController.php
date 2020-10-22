<?php

namespace App\Controller\Admin;

use App\Entity\TiposServicios;
use App\Form\TiposServiciosType;
use App\Repository\TiposServiciosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/tipos/servicios")
 * 
 */
class TiposServiciosController extends AbstractController
{
    /**
     * @Route("/list", name="tipos_servicios_index", methods={"GET"})
     */
    public function index(TiposServiciosRepository $tiposServiciosRepository): Response
    {
        return $this->render('tipos_servicios/index.html.twig', [
            'tipos_servicios' => $tiposServiciosRepository->findAll(),
        ]);
    }

    /**
     * @IsGranted("ROLE_CENTRAL", message="Acceso denegado")
     * @Route("/new", name="tipos_servicios_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tiposServicio = new TiposServicios();
        $form = $this->createForm(TiposServiciosType::class, $tiposServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tiposServicio);
            $entityManager->flush();

            return $this->redirectToRoute('tipos_servicios_index');
        }

        return $this->render('tipos_servicios/new.html.twig', [
            'tipos_servicio' => $tiposServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_CENTRAL", message="Acceso denegado")
     * @Route("/{id}", name="tipos_servicios_show", methods={"GET"})
     */
    public function show(TiposServicios $tiposServicio): Response
    {
        return $this->render('tipos_servicios/show.html.twig', [
            'tipos_servicio' => $tiposServicio,
        ]);
    }

    /**
     * @IsGranted("ROLE_CENTRAL", message="Accesso denegado")
     * @Route("/{id}/edit", name="tipos_servicios_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TiposServicios $tiposServicio): Response
    {
        $form = $this->createForm(TiposServiciosType::class, $tiposServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipos_servicios_index', [
                'id' => $tiposServicio->getId(),
            ]);
        }

        return $this->render('tipos_servicios/edit.html.twig', [
            'tipos_servicio' => $tiposServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN", message="Acceso denegado, solo un adminitrador puede eliminar, pone en contacto con draya@techfore.es para mayor información")
     * @Route("/{id}", name="tipos_servicios_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TiposServicios $tiposServicio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tiposServicio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tiposServicio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipos_servicios_index');
    }
    
    public function getServices(TiposServiciosRepository $serviceRepo) {
        
        return $this->render("tipos_servicios/list.html.twig",["services" => $serviceRepo->findAll()]);
    }
}
