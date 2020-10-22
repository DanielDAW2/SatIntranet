<?php

namespace App\Controller\Admin;

use App\Entity\EstadosClinica;
use App\Form\EstadosClinicaType;
use App\Repository\EstadosClinicaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_ADMIN", statusCode=403, message="Acceso denegado")
 * @Route("/estados/clinica")
 */
class EstadosClinicaController extends AbstractController
{
    /**
     * @Route("/", name="estados_clinica_index", methods={"GET"})
     */
    public function index(EstadosClinicaRepository $estadosClinicaRepository): Response
    {
        return $this->render('estados_clinica/index.html.twig', [
            'estados_clinicas' => $estadosClinicaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="estados_clinica_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $estadosClinica = new EstadosClinica();
        $form = $this->createForm(EstadosClinicaType::class, $estadosClinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($estadosClinica);
            $entityManager->flush();

            return $this->redirectToRoute('estados_clinica_index');
        }

        return $this->render('estados_clinica/new.html.twig', [
            'estados_clinica' => $estadosClinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estados_clinica_show", methods={"GET"})
     */
    public function show(EstadosClinica $estadosClinica): Response
    {
        return $this->render('estados_clinica/show.html.twig', [
            'estados_clinica' => $estadosClinica,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="estados_clinica_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, EstadosClinica $estadosClinica): Response
    {
        $form = $this->createForm(EstadosClinicaType::class, $estadosClinica);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('estados_clinica_index', [
                'id' => $estadosClinica->getId(),
            ]);
        }

        return $this->render('estados_clinica/edit.html.twig', [
            'estados_clinica' => $estadosClinica,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="estados_clinica_delete", methods={"DELETE"})
     */
    public function delete(Request $request, EstadosClinica $estadosClinica): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estadosClinica->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($estadosClinica);
            $entityManager->flush();
        }

        return $this->redirectToRoute('estados_clinica_index');
    }
}
