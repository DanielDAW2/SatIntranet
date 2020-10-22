<?php

namespace App\Controller\Admin;

use App\Entity\Servicios;
use App\Form\ServiciosType;
use App\Form\ImportServicesType;
use App\Repository\ServiciosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\TiposServiciosRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Route("/servicios", defaults={"tipo":"all"})
 */
class ServiciosController extends AbstractController
{

    /**
     * @IsGranted("ROLE_CENTRAL", message="Acceso denegado, solo puedes ver los codigos que ya est�n asignados")
     * @Route("/all", name="servicios_index_all", methods={"GET"})
     */
    public function indexAll(ServiciosRepository $serviciosRepository, $tipo): Response
    {
        return $this->render('servicios/index.html.twig', [
            'servicios' => $serviciosRepository->findAll(),
            'tipo'=>$tipo
        ]);
    }
    
    /**
     * @IsGranted("ROLE_ADMIN", message="Acceso denegado, solo el administrador puede importar códigos.")
     * @Route("/import", name="servicios_import")
     */
    public function importCodes(Request $req, ServiciosRepository $repo) {
        $form = $this->createForm(ImportServicesType::class);
        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            
            $file = fopen($data['file'], "r");
            $cont = 0;
            while($code = fgetcsv($file,null,";"))
            {
                if(!$repo->findOneBy(['codigoPromo'=>$code[0],'tipo'=>$data['tipo']]))
                {
                   $service = new Servicios();
                    $service->setCodigoPromo($code[0]);
                    $service->setTipo($data['tipo']);
                    $this->getDoctrine()->getManager()->persist($service);
                    if($cont!=30)
                    {
                        $this->getDoctrine()->getManager()->flush();
                        $cont=0;
                    }
                    $cont++; 
                }
                
                
            }
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('notice', 'Se han importado Correctamente');
        }
        
        return $this->render("servicios/import.html.twig", ["form"=>$form->createView()]);
    }
    
    /**
     * @Route("/{tipo}", defaults={"tipo":"arranque_premium"} ,name="servicios_index", methods={"GET"})
     */
    public function index(ServiciosRepository $serviciosRepository, $tipo): Response
    {
        
        return $this->render('servicios/index.html.twig', [
            'servicios' => $serviciosRepository->findByService($tipo, $this->getUser()->getDelegacion()->getId()),
            'tipo'=>$tipo
        ]);
    }
    
    /**
     * @Route("/{tipo}/solicitar", name="servicios_solicitar")
     */
    public function getFreeCode(ServiciosRepository $repo)
    {
        $code = $repo->getCodeFree();
        $code->setSolicitante($this->getUser());
        $code->setFechaAlta(new \DateTime());
        $dateadd = date_add(new \DateTime(), new \DateInterval("P1Y"));
        $code->setFechaFin($dateadd);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($code);
        $entityManager->flush();
        $file = fopen("ElifeDrive-".$code->getCodigoPromo().".txt","w");
        fwrite($file, "Tu código para activar tus 2TB de ElifeDrive es: ".$code->getCodigoPromo());
        fclose($file);
        $response = new File("ElifeDrive-".$code->getCodigoPromo().".txt");
        $response->move($this->getParameter("elifedir"));
        return $this->file(new File($this->getParameter("elifedir")."/ElifeDrive-".$code->getCodigoPromo().".txt"));
    }
    
    
    

    /**
     * @Route("/{tipo}/new", name="servicios_new", methods={"GET","POST"})
     */
    public function new(TiposServiciosRepository $repo, Request $request, $tipo): Response
    {
        $servicio = new Servicios();
        $servicio->setTipo($repo->findOneBy(["slug"=>$tipo]));
        $form = $this->createForm(ServiciosType::class, $servicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $servicio->setSolicitante($this->getUser());
            $servicio->setFechaAlta(new \DateTime());
            $dateadd = date_add(new \DateTime(), new \DateInterval("P1Y"));
            $servicio->setFechaFin($dateadd);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($servicio);
            $entityManager->flush();

            return $this->redirectToRoute('servicios_index',["tipo"=>$tipo]);
        }

        return $this->render('servicios/new.html.twig', [
            'servicio' => $servicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{tipo}/{id}", name="servicios_show", methods={"GET"})
     */
    public function show(Servicios $servicio): Response
    {
        return $this->render('servicios/show.html.twig', [
            'servicio' => $servicio,
        ]);
    }

    /**
     * @Route("/{tipo}/{id}/edit", name="servicios_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Servicios $servicio): Response
    {
        $form = $this->createForm(ServiciosType::class, $servicio);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('servicios_index', [
                'id' => $servicio->getId(),
            ]);
        }

        return $this->render('servicios/edit.html.twig', [
            'servicio' => $servicio,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @IsGranted("ROLE_ADMIN", message="Solo un administrador puede eliminar.")
     * @Route("/{tipo}/{id}/delete", name="servicios_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Servicios $servicio): Response
    {
        if ($this->isCsrfTokenValid('delete'.$servicio->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($servicio);
            $entityManager->flush();
        }

        return $this->redirectToRoute('servicios_index');
    }
    
    
}
