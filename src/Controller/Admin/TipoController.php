<?php

namespace App\Controller\Admin;

use App\Entity\Tipo;
use App\Form\TipoType;
use App\Repository\TipoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/tipo")
 */
class TipoController extends AbstractController
{
    /**
     * @Route("/", name="tipo_index", methods={"GET"})
     */
    public function index(TipoRepository $tipoRepository): Response
    {
        return $this->render('tipo/index.html.twig', [
            'tipos' => $tipoRepository->findAll(),
        ]);
    }
    
    /**
     * 
     */
        public function getTipos(TipoRepository $tipo)
        {
            return $this->render("tipo/filter.html.twig", ["datas"=>$tipo->findAll(),"filter"=>"tipo"]);
        }
    /**
     * @Route("/new", name="tipo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tipo = new Tipo();
        $form = $this->createForm(TipoType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($tipo);
            $entityManager->flush();

            return $this->redirectToRoute('tipo_index');
        }

        return $this->render('tipo/new.html.twig', [
            'tipo' => $tipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_show", methods={"GET"})
     */
    public function show(Tipo $tipo): Response
    {
        return $this->render('tipo/show.html.twig', [
            'tipo' => $tipo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="tipo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tipo $tipo): Response
    {
        $form = $this->createForm(TipoType::class, $tipo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tipo_index', [
                'id' => $tipo->getId(),
            ]);
        }

        return $this->render('tipo/edit.html.twig', [
            'tipo' => $tipo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="tipo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Tipo $tipo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tipo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($tipo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tipo_index');
    }
}
