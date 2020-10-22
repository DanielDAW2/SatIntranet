<?php

namespace App\Controller\Admin;

use App\Entity\Modelo;
use App\Form\ModeloType;
use App\Repository\ModeloRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MarcaRepository;
use App\Entity\Marca;

/**
 * @Route("/modelo")
 */
class ModeloController extends AbstractController
{
    /**
     * @Route("/", name="modelo_index", methods={"GET"})
     */
    public function index(ModeloRepository $modeloRepository): Response
    {
        return $this->render('modelo/index.html.twig', [
            'modelos' => $modeloRepository->findBy([],["marca"=>"ASC"]),
        ]);
    }

    /**
     * @Route("/new", name="modelo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $modelo = new Modelo();
        $form = $this->createForm(ModeloType::class, $modelo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($modelo);
            $entityManager->flush();

            return $this->redirectToRoute('modelo_index');
        }

        return $this->render('modelo/new.html.twig', [
            'modelo' => $modelo,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     *  @Route("/getmarcas", name="modelo_getmarca", methods={"POST"})
     */
    public function getModelos(MarcaRepository $repo, Request $req)
    {
        $modelos = $repo->findOneBy(["nombre"=>$req->request->get("marca")]);
        
        
        $list = array();
        if($modelos)
        {
            $modelos = $modelos->getModelos();
            foreach ($modelos as $value)
            {
                $list[$value->getId()] = $value->getNombre();
            }
        }
        
        
        return new Response(json_encode($list, JSON_UNESCAPED_SLASHES));
    }

    /**
     * @Route("/{id}", name="modelo_show", methods={"GET"})
     */
    public function show(Modelo $modelo): Response
    {
        return $this->render('modelo/show.html.twig', [
            'modelo' => $modelo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="modelo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Modelo $modelo): Response
    {
        $form = $this->createForm(ModeloType::class, $modelo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('modelo_index', [
                'id' => $modelo->getId(),
            ]);
        }

        return $this->render('modelo/edit.html.twig', [
            'modelo' => $modelo,
            'form' => $form->createView(),
        ]);
    }
    
    

    /**
     * @Route("/{id}", name="modelo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Modelo $modelo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$modelo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($modelo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('modelo_index');
    }
    
    
    
    /**
     * @Route("/check", name="modelo_check", methods={"POST"})
     * @param Request $req
     * @param ModeloRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function check(Request $req, ModeloRepository $repo, MarcaRepository $mRepo)
    {
        $marca = $mRepo->findOneBy(["nombre"=>$req->request->get("marca")]);
        return new Response($repo->findOneBy(["nombre"=>$req->request->get('modelo'), "marca"=>$marca]) ? "1" : $req->get('modelo'));
    }
    
    
    /**
     * @Route("/generate", name="modelo_generate", methods={"POST"})
     */
    public function generateModelo(Request $req, MarcaRepository $repo) {
        $marca = $repo->findOneBy(["nombre"=>$req->request->get("marca")]);
        $modelo = new Modelo();
        $modelo->setMarca($marca);
        $modelo->setNombre($req->request->get("modelo"));
        $modelo->setVisible(false);
        $this->getDoctrine()->getManager()->persist($modelo);
        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode(array("modelo"=>$modelo->getNombre(),"id"=>$modelo->getId())));
    }
}
