<?php

namespace App\Controller\Admin;

use App\Entity\NotasServicios;
use App\Form\NotasServiciosType;
use App\Repository\NotasServiciosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/notas/servicios")
 */
class NotasServiciosController extends AbstractController
{
    /**
     * @Route("/", name="notas_servicios_index", methods={"GET"})
     */
    public function index(NotasServiciosRepository $notasServiciosRepository): Response
    {
        return $this->render('notas_servicios/index.html.twig', [
            'notas_servicios' => $notasServiciosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="notas_servicios_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $notasServicio = new NotasServicios();
        $form = $this->createForm(NotasServiciosType::class, $notasServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($notasServicio);
            $entityManager->flush();

            return $this->redirectToRoute('notas_servicios_index');
        }

        return $this->render('notas_servicios/new.html.twig', [
            'notas_servicio' => $notasServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notas_servicios_show", methods={"GET"})
     */
    public function show(NotasServicios $notasServicio): Response
    {
        return $this->render('notas_servicios/show.html.twig', [
            'notas_servicio' => $notasServicio,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="notas_servicios_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, NotasServicios $notasServicio): Response
    {
        $form = $this->createForm(NotasServiciosType::class, $notasServicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('notas_servicios_index', [
                'id' => $notasServicio->getId(),
            ]);
        }

        return $this->render('notas_servicios/edit.html.twig', [
            'notas_servicio' => $notasServicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="notas_servicios_delete", methods={"DELETE"})
     */
    public function delete(Request $request, NotasServicios $notasServicio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notasServicio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($notasServicio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('notas_servicios_index');
    }
}
