<?php

namespace App\Controller\Admin;

use App\Entity\Marca;
use App\Form\MarcaType;
use App\Repository\MarcaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Repository\ModeloRepository;
use App\Entity\Modelo;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @IsGranted("ROLE_TECNIC")
 * @Route("/marca")
 */
class MarcaController extends AbstractController
{
    /**
     * @Route("/", name="marca_index", methods={"GET"})
     */
    public function index(MarcaRepository $marcaRepository): Response
    {
        return $this->render('marca/index.html.twig', [
            'marcas' => $marcaRepository->findAll(),
        ]);
    }
    
    /**
     * @Route("/update", name="marca_updateAll")
     */
    public function updateMarcas(MarcaRepository $marcaRepo, ModeloRepository $modeloRepo, EntityManagerInterface $em)
    {
        $marcas = json_decode(file_get_contents($this->getParameter("srv")."marcas"), true);
        
        foreach ($marcas as $value)
        {
            if(!$marca = $marcaRepo->findMarcaByName(trim(strtoupper($value["MARCA_SAT"]))))
            {
              $marca = new Marca();
              $marca->setNombre($value["MARCA_SAT"]);
              $marca->setIdTR($value["ID_SAT_MARCAS"]);
              $marca->setVisible(true);
              $em->persist($marca);
            }
            if(!$modelo = $modeloRepo->findModeloByName(trim(strtoupper($value["MODELO_SAT"]))))
            {
                $modelo = new Modelo();
                $modelo->setNombre($value["MODELO_SAT"]);
                $modelo->setIdTR($value["ID_SAT_MODELOS"]);
                $modelo->setMarca($marca);
                $em->persist($modelo);
            }
            
            $em->flush();
        }
        return $this->redirectToRoute("marca_index");
    }

    /**
     * @Route("/new", name="marca_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $marca = new Marca();
        $form = $this->createForm(MarcaType::class, $marca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($marca);
            $entityManager->flush();

            return $this->redirectToRoute('marca_index');
        }

        return $this->render('marca/new.html.twig', [
            'marca' => $marca,
            'form' => $form->createView(),
        ]);
    }
    
    

    /**
     * @Route("/{id}", name="marca_show", methods={"GET"})
     */
    public function show(Marca $marca): Response
    {
        return $this->render('marca/show.html.twig', [
            'marca' => $marca,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="marca_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Marca $marca): Response
    {
        $form = $this->createForm(MarcaType::class, $marca);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('marca_index', [
                'id' => $marca->getId(),
            ]);
        }

        return $this->render('marca/edit.html.twig', [
            'marca' => $marca,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="marca_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Marca $marca): Response
    {
        if ($this->isCsrfTokenValid('delete'.$marca->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($marca);
            $entityManager->flush();
        }

        return $this->redirectToRoute('marca_index');
    }
    
    /**
     * @Route("/check", name="marca_check", methods={"POST"})
     * @param Request $req
     * @param MarcaRepository $repo
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function check(Request $req, MarcaRepository $repo) 
    {
        return new Response($repo->findOneBy(["nombre"=>$req->request->get('marca')]) ? "1" : $req->get('marca'));
    }
    
    
    /**
     * @Route("/generate", name="marca_generate", methods={"POST"})
     */
    public function generateMarca(Request $req, MarcaRepository $repo) {
        $marca = new Marca();
        $marca->setNombre($req->get("marca"));
        $marca->setVisible(false);
        $this->getDoctrine()->getManager()->persist($marca);
        $this->getDoctrine()->getManager()->flush();
        
        return new Response(json_encode(array("marca"=>$marca->getNombre(),"id"=>$marca->getId())));
    }
    
    
}
