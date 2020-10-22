<?php

namespace App\Controller\Admin;

use App\Entity\Manual;
use App\Form\ManualType;
use App\Repository\ManualRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/manual")
 * @IsGranted("ROLE_TECNIC", statusCode=403 ,message="Solo los técnicos de techfore tienen acceso a sus manuales.")
 */
class ManualController extends AbstractController
{
    /**
     * @Route("/", name="manual_index", methods={"GET"})
     */
    public function index(ManualRepository $manualRepository): Response
    {
        return $this->render('manual/index.html.twig', [
            'manuals' => $manualRepository->findAll(),
        ]);
    }
    
    /**
     * 
     */
    public function getManuales(ManualRepository $manuales){
        return $this->render("manual/list.html.twig",["manuales"=>$manuales->findAll()]);
    }
    /**
     * @Route("/new", name="manual_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $manual = new Manual();
        $form = $this->createForm(ManualType::class, $manual);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($manual);
            $entityManager->flush();

            return $this->redirectToRoute('manual_index');
        }

        return $this->render('manual/new.html.twig', [
            'manual' => $manual,
            'form' => $form->createView(),
        ]);
    }

    /**
	 *@isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
     * @Route("/{id}", name="manual_show", methods={"GET"})
     */
    public function show(Manual $manual): Response
    {
        return $this->render('manual/show.html.twig', [
            'manual' => $manual,
        ]);
    }

    /**
	* @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
     * @Route("/{id}/edit", name="manual_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Manual $manual): Response
    {
        $form = $this->createForm(ManualType::class, $manual);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('manual_index', [
                'id' => $manual->getId(),
            ]);
        }

        return $this->render('manual/edit.html.twig', [
            'manual' => $manual,
            'form' => $form->createView(),
        ]);
    }

    /**
	* @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
     * @Route("/{id}", name="manual_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Manual $manual): Response
    {
        if ($this->isCsrfTokenValid('delete'.$manual->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($manual);
            $entityManager->flush();
        }

        return $this->redirectToRoute('manual_index');
    }
}
