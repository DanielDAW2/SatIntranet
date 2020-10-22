<?php

namespace App\Controller\Admin;

use App\Entity\ConfCierreReparacion;
use App\Form\ConfCierreReparacionType;
use App\Repository\ConfCierreReparacionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
 * @Route("/conf/cierre/reparacion")
 */
class ConfCierreReparacionController extends AbstractController
{
    /**
     * @Route("/", name="conf_cierre_reparacion_index", methods={"GET"})
     */
    public function index(ConfCierreReparacionRepository $confCierreReparacionRepository): Response
    {
        return $this->render('conf_cierre_reparacion/index.html.twig', [
            'conf_cierre_reparacions' => $confCierreReparacionRepository->findBy([],["SituacionOrigen"=>"DESC"]),
        ]);
    }

    /**
     * @Route("/new", name="conf_cierre_reparacion_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $confCierreReparacion = new ConfCierreReparacion();
        $form = $this->createForm(ConfCierreReparacionType::class, $confCierreReparacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($confCierreReparacion);
            $entityManager->flush();

            return $this->redirectToRoute('conf_cierre_reparacion_index');
        }

        return $this->render('conf_cierre_reparacion/new.html.twig', [
            'conf_cierre_reparacion' => $confCierreReparacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="conf_cierre_reparacion_show", methods={"GET"})
     */
    public function show(ConfCierreReparacion $confCierreReparacion): Response
    {
        return $this->render('conf_cierre_reparacion/show.html.twig', [
            'conf_cierre_reparacion' => $confCierreReparacion,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="conf_cierre_reparacion_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ConfCierreReparacion $confCierreReparacion): Response
    {
        $form = $this->createForm(ConfCierreReparacionType::class, $confCierreReparacion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('conf_cierre_reparacion_index', [
                'id' => $confCierreReparacion->getId(),
            ]);
        }

        return $this->render('conf_cierre_reparacion/edit.html.twig', [
            'conf_cierre_reparacion' => $confCierreReparacion,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="conf_cierre_reparacion_delete", methods={"DELETE"})
     */
    public function delete(Request $request, ConfCierreReparacion $confCierreReparacion): Response
    {
        if ($this->isCsrfTokenValid('delete'.$confCierreReparacion->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($confCierreReparacion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('conf_cierre_reparacion_index');
    }
}
