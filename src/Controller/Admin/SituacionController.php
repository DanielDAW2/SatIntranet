<?php

namespace App\Controller\Admin;

use App\Entity\Situacion;
use App\Form\SituacionType;
use App\Repository\SituacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
 * @Route("/situacion")
 */
class SituacionController extends AbstractController
{
    /**
     * @Route("/", name="situacion_index", methods={"GET"})
     */
    public function index(SituacionRepository $situacionRepository): Response
    {
        return $this->render('situacion/index.html.twig', [
            'situacions' => $situacionRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="situacion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $situacion = new Situacion();
        $form = $this->createForm(SituacionType::class, $situacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($situacion);
            $entityManager->flush();

            return $this->redirectToRoute('situacion_index');
        }

        return $this->render('situacion/new.html.twig', [
            'situacion' => $situacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="situacion_show", methods={"GET"})
     */
    public function show(Situacion $situacion): Response
    {
        return $this->render('situacion/show.html.twig', [
            'situacion' => $situacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="situacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Situacion $situacion): Response
    {
        $form = $this->createForm(SituacionType::class, $situacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('situacion_index', [
                'id' => $situacion->getId(),
            ]);
        }

        return $this->render('situacion/edit.html.twig', [
            'situacion' => $situacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="situacion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Situacion $situacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$situacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($situacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('situacion_index');
    }
    
    public function getSituaciones(SituacionRepository $situaciones)
    {
        return $this->render("ordentrabajo/filter.html.twig",["datas"=>$situaciones->findAll()]);
    }
}
