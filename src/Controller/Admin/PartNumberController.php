<?php

namespace App\Controller\Admin;

use App\Entity\PartNumber;
use App\Form\PartNumberType;
use App\Repository\PartNumberRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Marca;
use App\Entity\Modelo;
use App\Repository\MarcaRepository;
use App\Repository\ModeloRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/partnumber")
 */
class PartNumberController extends AbstractController
{
    /**
     * @Route("/", name="part_number_index", methods={"GET"})
     */
    public function index(PartNumberRepository $partNumberRepository): Response
    {
        return $this->render('part_number/index.html.twig', [
            'part_numbers' => $partNumberRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="part_number_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $partNumber = new PartNumber();
        $form = $this->createForm(PartNumberType::class, $partNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($partNumber);
            $entityManager->flush();

            return $this->redirectToRoute('part_number_index');
        }

        return $this->render('part_number/new.html.twig', [
            'part_number' => $partNumber,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @IsGranted("ROLE_CENTRAL", message="Acceso denegado, solo personal de central.")
     * @Route("/update", name="part_number_updateAll")
     */
    public function update(MarcaRepository $marcaRepo, ModeloRepository $modeloRepo, EntityManagerInterface $em, PartNumberRepository $partRepo){
        $pns = json_decode(file_get_contents($this->getParameter("srv")."partnumber"), true);
        $iterator = 0;
        $pn = null;
        foreach ($pns as $value)
        {

            $marca = $marcaRepo->findOneBy(["idTR"=>trim($value["ID_SAT_MARCAS"])]);
            $modelo = $modeloRepo->findOneBy(["idTR"=>trim($value["ID_SAT_MODELOS"])]);
            $pn = $partRepo->findOneBy(["partnumber"=>strtoupper(trim($value["CLASE_EQUIPO"])),
                "marca"=>$marca,
                "Modelo"=>$modelo]);
            if($pn === null)
            {
                if($marca == null)
                {
                    $marca = new Marca();
                    $marca->setNombre($value["MARCA_SAT"]);
                    $marca->setIdTR($value["ID_SAT_MARCAS"]);
                    $marca->setVisible(true);
                    $em->persist($marca);
                    $em->flush();
                }
                if($modelo == null)
                {
                    $modelo = new Modelo();
                    $modelo->setNombre($value["MODELO_SAT"]);
                    $modelo->setIdTR($value["ID_SAT_MODELOS"]);
                    $modelo->setMarca($marca);
                    $modelo->setVisible(true);
                    $em->persist($modelo);
                    $em->flush();
                }
                if($pn === null)
                {
                    $pn = new PartNumber();
                    $pn->setPartnumber($value["CLASE_EQUIPO"]);
                }
                $pn->setMarca($marca);
                $pn->setModelo($modelo);
                $em->persist($pn);
                
                if($iterator == 20){
                    $em->flush();
                    $iterator = 0;
                }else {
                    $iterator++;
                } 
            }       
        }
        $em->flush();
        return $this->redirectToRoute("part_number_index");
    }
    /**
     * @Route("/{id}", name="part_number_show", methods={"GET"})
     */
    public function show(PartNumber $partNumber): Response
    {
        return $this->render('part_number/show.html.twig', [
            'part_number' => $partNumber,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="part_number_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, PartNumber $partNumber): Response
    {
        $form = $this->createForm(PartNumberType::class, $partNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('part_number_index', [
                'id' => $partNumber->getId(),
            ]);
        }

        return $this->render('part_number/edit.html.twig', [
            'part_number' => $partNumber,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="part_number_delete", methods={"DELETE"})
     */
    public function delete(Request $request, PartNumber $partNumber): Response
    {
        if ($this->isCsrfTokenValid('delete'.$partNumber->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($partNumber);
            $entityManager->flush();
        }

        return $this->redirectToRoute('part_number_index');
    }
}
