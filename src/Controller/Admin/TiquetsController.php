<?php

namespace App\Controller\Admin;

use App\Entity\Tiquets;
use App\Form\TiquetsType;
use App\Repository\TiquetsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tiquets")
 */
class TiquetsController extends AbstractController
{
    /**
     * @Route("/", name="tiquets_index", methods={"GET"})
     */
    public function index(TiquetsRepository $tiquetsRepository): Response
    {
        return $this->render('tiquets/index.html.twig', [
            'tiquets' => $tiquetsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="tiquets_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tiquet = new Tiquets();
        $form = $this->createForm(TiquetsType::class, $tiquet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tiquet);
            $entityManager->flush();

            return $this->redirectToRoute('tiquets_index');
        }

        return $this->render('tiquets/new.html.twig', [
            'tiquet' => $tiquet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tiquets_show", methods={"GET"})
     */
    public function show(Tiquets $tiquet): Response
    {
        return $this->render('tiquets/show.html.twig', [
            'tiquet' => $tiquet,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tiquets_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tiquets $tiquet): Response
    {
        $form = $this->createForm(TiquetsType::class, $tiquet);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tiquets_index', [
                'id' => $tiquet->getId(),
            ]);
        }

        return $this->render('tiquets/edit.html.twig', [
            'tiquet' => $tiquet,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tiquets_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tiquets $tiquet): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tiquet->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tiquet);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tiquets_index');
    }
}
