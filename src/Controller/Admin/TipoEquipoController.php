<?php

namespace App\Controller\Admin;

use App\Entity\TipoEquipo;
use App\Form\TipoEquipoType;
use App\Repository\TipoEquipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo-equipo")
 */
class TipoEquipoController extends AbstractController
{
    /**
     * @Route("/", name="tipo_equipo_index", methods={"GET"})
     */
    public function index(TipoEquipoRepository $tipoEquipoRepository): Response
    {
        return $this->render('tipo_equipo/index.html.twig', [
            'tipo_equipos' => $tipoEquipoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tipo_equipo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipoEquipo = new TipoEquipo();
        $form = $this->createForm(TipoEquipoType::class, $tipoEquipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipoEquipo);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_equipo_index');
        }

        return $this->render('tipo_equipo/new.html.twig', [
            'tipo_equipo' => $tipoEquipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_equipo_show", methods={"GET"})
     */
    public function show(TipoEquipo $tipoEquipo): Response
    {
        return $this->render('tipo_equipo/show.html.twig', [
            'tipo_equipo' => $tipoEquipo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_equipo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, TipoEquipo $tipoEquipo): Response
    {
        $form = $this->createForm(TipoEquipoType::class, $tipoEquipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_equipo_index', [
                'id' => $tipoEquipo->getId(),
            ]);
        }

        return $this->render('tipo_equipo/edit.html.twig', [
            'tipo_equipo' => $tipoEquipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_equipo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, TipoEquipo $tipoEquipo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipoEquipo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipoEquipo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_equipo_index');
    }
}
