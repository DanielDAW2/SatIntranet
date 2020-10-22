<?php

namespace App\Controller\Admin;

use App\Entity\EstadosCentral;
use App\Form\EstadosCentralType;
use App\Repository\EstadosCentralRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_ADMIN", statusCode=403, message="Acceso denegado")
 * @Route("/estados/central")
 */
class EstadosCentralController extends AbstractController
{
    /**
     * @Route("/", name="estados_central_index", methods={"GET"})
     */
    public function index(EstadosCentralRepository $estadosCentralRepository): Response
    {
        return $this->render('estados_central/index.html.twig', [
            'estados_centrals' => $estadosCentralRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estados_central_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadosCentral = new EstadosCentral();
        $form = $this->createForm(EstadosCentralType::class, $estadosCentral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadosCentral);
            $entityManager->flush();

            return $this->redirectToRoute('estados_central_index');
        }

        return $this->render('estados_central/new.html.twig', [
            'estados_central' => $estadosCentral,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estados_central_show", methods={"GET"})
     */
    public function show(EstadosCentral $estadosCentral): Response
    {
        return $this->render('estados_central/show.html.twig', [
            'estados_central' => $estadosCentral,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estados_central_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadosCentral $estadosCentral): Response
    {
        $form = $this->createForm(EstadosCentralType::class, $estadosCentral);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estados_central_index', [
                'id' => $estadosCentral->getId(),
            ]);
        }

        return $this->render('estados_central/edit.html.twig', [
            'estados_central' => $estadosCentral,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estados_central_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadosCentral $estadosCentral): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadosCentral->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($estadosCentral);
            $entityManager->flush();
        }
        return $this->redirectToRoute('estados_central_index');
    }
}
