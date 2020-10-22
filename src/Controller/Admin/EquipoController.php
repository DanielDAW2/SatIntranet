<?php

namespace App\Controller\Admin;

use App\Entity\Equipo;
use App\Form\EquipoType;
use App\Repository\EquipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/equipo")
 * @IsGranted("ROLE_TECNIC")
 */
class EquipoController extends AbstractController
{
    /**
     * @Route("/", name="equipo_index", methods={"GET"})
	 * * @IsGranted("ROLE_CENTRAL")
     */
    public function index(EquipoRepository $equipoRepository): Response
    {
        return $this->render('equipo/index.html.twig', [
            'equipos' => $equipoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="equipo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $equipo = new Equipo();
        $form = $this->createForm(EquipoType::class, $equipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($equipo);
            $entityManager->flush();

            return $this->redirectToRoute('equipo_index');
        }

        return $this->render('equipo/new.html.twig', [
            'equipo' => $equipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipo_show", methods={"GET"})
     */
    public function show(Equipo $equipo): Response
    {
        return $this->render('equipo/show.html.twig', [
            'equipo' => $equipo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="equipo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Equipo $equipo): Response
    {
        $form = $this->createForm(EquipoType::class, $equipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('equipo_index', [
                'id' => $equipo->getId(),
            ]);
        }

        return $this->render('equipo/edit.html.twig', [
            'equipo' => $equipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="equipo_delete", methods={"DELETE"})
     * @IsGranted("ROLE_CENTRAL")
     */
    public function delete(Request $request, Equipo $equipo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$equipo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($equipo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('equipo_index');
    }
}
