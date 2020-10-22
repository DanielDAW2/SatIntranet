<?php

namespace App\Controller\Admin;

use App\Entity\Delegacion;
use App\Form\DelegacionType;
use App\Repository\DelegacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
 * @Route("/delegacion")
 */
class DelegacionController extends AbstractController
{
    /**
     * @Route("/", name="delegacion_index", methods={"GET"})
     */
    public function index(DelegacionRepository $delegacionRepository): Response
    {
        return $this->render('delegacion/index.html.twig', [
            'delegacions' => $delegacionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="delegacion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $delegacion = new Delegacion();
        $form = $this->createForm(DelegacionType::class, $delegacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($delegacion);
            $entityManager->flush();

            return $this->redirectToRoute('delegacion_index');
        }

        return $this->render('delegacion/new.html.twig', [
            'delegacion' => $delegacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delegacion_show", methods={"GET"})
     */
    public function show(Delegacion $delegacion): Response
    {
        return $this->render('delegacion/show.html.twig', [
            'delegacion' => $delegacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="delegacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Delegacion $delegacion): Response
    {
        $form = $this->createForm(DelegacionType::class, $delegacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('delegacion_index', [
                'id' => $delegacion->getId(),
            ]);
        }

        return $this->render('delegacion/edit.html.twig', [
            'delegacion' => $delegacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="delegacion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Delegacion $delegacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$delegacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($delegacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('delegacion_index');
    }
    
    /**
     * 
     */
    public function getDelegaciones(DelegacionRepository $delegaciones) 
    {
        return $this->render("delegacion/filter.html.twig",["datas"=>$delegaciones->findAll(),"filter"=>"delegacion"]);
    }
}
