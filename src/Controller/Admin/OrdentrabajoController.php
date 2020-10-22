<?php

namespace App\Controller\Admin;

use App\Entity\Tipo;
use App\Entity\Serie;
use App\Entity\Prioridad;
use App\Entity\Situacion;
use App\Entity\Delegacion;
use App\Entity\Materiales;
use App\Entity\Ordentrabajo;
use App\Entity\Marca;
use App\Entity\Modelo;

use App\Form\OrdentrabajoType;
use App\Repository\OrdentrabajoRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\JsonResponse;
use PhpParser\Node\Expr\Isset_;
use Doctrine\Common\Collections\ArrayCollection;
use App\Entity\Equipo;
use App\Repository\EquipoRepository;
use Symfony\Component\Intl\Tests\Data\Provider\Json\JsonRegionDataProviderTest;
use App\Entity\PartNumber;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\CierreReparacionesRepository;
use App\Entity\ConfCierreReparacion;
use App\Repository\ConfCierreReparacionRepository;



/**
 * @isGranted("ROLE_TECNIC", message="Solo el personal de Techfore tiene acceso a las reparaciones.")
 * @Route("/ordenestrabajo")
 */
class OrdentrabajoController extends AbstractController
{
    /**
     * @Route("/{page}", 
     * name="ordentrabajo_index", methods={"GET","POST"}, 
     * defaults={"page":"1"},
     * requirements={"page":"\d+"})
     */
    public function index(OrdentrabajoRepository $ordentrabajoRepository, $page = 1, Request $req): Response
    {
        $filters = array("delegacion"=>$req->get("delegacion"),"tipo"=>$req->get("tipo"),"situacion"=>$req->get("situacion"));
        
        
        if($this->getUser()->getDelegacion()->getId() == 57)
        {
            $result = $ordentrabajoRepository->findAllp($page, 20, $filters);
        } else {
            $result = $ordentrabajoRepository->findByDelegacion($this->getUser()->getDelegacion()->getId(),20, $page, $filters);
        }
        $Query = $result['query'];
        $maxPages = ceil($result['paginator']->count() / 20);
        $ordenes = $result['paginator'];
        $tipos = $this->getDoctrine()->getRepository(Tipo::class)->findAll();
        $delegaciones = $this->getDoctrine()->getRepository(Delegacion::class)->findAll();
        $situaciones = $this->getDoctrine()->getRepository(Situacion::class)->findAll();
        
        return $this->render("ordentrabajo/index.html.twig",[
            'tipos'=>$tipos,
            'delegaciones'=>$delegaciones,
            'situaciones'=>$situaciones,
            'ordentrabajos'=>$ordenes,
            'maxPages'=>$maxPages,
            'thisPage'=>$page,
            'all'=>$Query
                ]);

    }

    /**
     * @IsGranted("ROLE_TECNIC")
     * @Route("/UpdateClinica", name="ordentrabajo_update_clinica", methods={"GET"})
     */
    public function updateClinica()
    {
       $this->updateAllOTClinica($this->getUser()->getDelegacion()->getIdTr());
       return $this->redirectToRoute("ordentrabajo_index");
    }
    
    /**
     * @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
     * @Route("/UpdateAll", name="ordentrabajo_update_all", methods={"GET"})
     */
    public function UpdateAllOts()
    {
        $this->updateAllOT();
        return $this->redirectToRoute("ordentrabajo_index");
    }
    
    /**
     * @Route("/find", name="findOt", methods={"GET"})
     */
    public function findOt(Request $req, OrdentrabajoRepository $ordentrabajoRepository)
    {

        $caso = str_replace(" ","",$req->get("caso"));
        $ot = $this->updateOT($caso);
        if($ot)
        {
            $tipos = $this->getDoctrine()->getRepository(Tipo::class)->findAll();
            $delegaciones = $this->getDoctrine()->getRepository(Delegacion::class)->findAll();
            $situaciones = $this->getDoctrine()->getRepository(Situacion::class)->findAll();
            return $this->render("ordentrabajo/index.html.twig",[
                'tipos'=>$tipos,
                'delegaciones'=>$delegaciones,
                'situaciones'=>$situaciones,
                'ordentrabajos'=>$ot,
                'maxPages'=>0,
                'thisPage'=>0,
                'all'=>0
                    ]);
            
        }
        return $this->redirectToRoute("ordentrabajo_index");
        
    }

    /**
     * @isGranted("ROLE_TECNIC")
     * @Route("/{n_orden}/images", name="ordentrabajo_images")
     */
    public function uploadImages(Ordentrabajo $ot = null)
    {
        return $this->render("ordentrabajo/uploadimages.twig",["ordentrabajo"=>$ot]);
    }

    /**
     * @Route("/orders/fileuploadhandler", name="fileuploadhandler")
     * @isGranted("ROLE_TECNIC")
     */
    public function fileUploadHandler(Request $request, FileUploader $uploader) {
        try
        {
            $file = $request->files->get('file');
            $ftp = ftp_ssl_connect("XXX",5000);
            ftp_login($ftp,"","##");
            
        if(@ftp_chdir($ftp, $request->request->get("SERIE").$request->request->get("N_ORDEN")) ===   FALSE)
            {
                ftp_mkdir($ftp,$request->request->get("SERIE").$request->request->get("N_ORDEN"));
                ftp_chdir($ftp, $request->get("SERIE").$request->request->get("N_ORDEN"));
            }
            
    
            ftp_put($ftp, $request->get("type")."__".time()."__".$file->getClientOriginalName(), $file, FTP_BINARY);
            
            ftp_close($ftp);
            unlink($file);
            return new JsonResponse("Archivo enviado con éxito.");  
        }catch (\Exception $error)
        {
            return new JsonResponse("No se ha podido subir el archivo, comprueba que no excede el tamaño máximo permitido".$error->getMessage(),500);
        }
        
    }
    
    /**
     * @Route("/{n_orden}/uploadedFiles", name="ordentrabajo_getuploadedimages", methods={"GET"})
     * 
     * Get a list from FTP srv of files uploaded on the OT folder and returned it in JSON
     * 
     * @param Ordentrabajo $id
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function getUploadedImages(Ordentrabajo $id)
    {
        try
        {
            $ftp = ftp_ssl_connect("");
            ftp_login($ftp,"","##");
            $list = [];
            if(ftp_chdir($ftp, $id->getSerie()->getNombre().$id->getNOrden()))
            {
                $files = array_values(ftp_nlist($ftp, ftp_pwd($ftp)));
                ftp_close($ftp);
                foreach ($files as $value)
                {
                    if(strstr($value, "pnsn__"))
                    {
                        $list["pnsn"]=true;
                    }
                    if(strstr($value, "error__"))
                    {
                        $list["error"]=true;
                    }
                    if(strstr($value, "tiquet__"))
                    {
                        $list["tiquet"]=true;
                    }
                    if(strstr($value, "hojatecnica__"))
                    {
                        $list["hojatecnica"]=true;
                    }
                }
                return new JsonResponse($list);
            }
            
            return new JsonResponse(array("nodata"=>"No se han subido archivos a la orden."),404);
                        
            
        }catch (\Exception $error)
        {
            return new JsonResponse($error->getMessage(),500);
        }
    }

    /**
     * @isGranted("ROLE_TECNIC", statusCode=403, message="Acceso denegado")
     * @Route("/new/{tipo}", defaults={"tipo":null} , name="ordentrabajo_new", methods={"GET","POST"})
     */
    public function new(Request $request,$tipo): Response
    {
        if($tipo)
        {
           $ordentrabajo = new Ordentrabajo();
           $prioridad = $this->getDoctrine()->getRepository(Prioridad::class)->find(1);
           $date = new \DateTime();
            switch ($tipo)
            {
                case "presupuesto":
                    $tipoOrd = $this->getDoctrine()->getRepository(Tipo::class)->find(6);
                    $situacion = $this->getDoctrine()->getRepository(Situacion::class)->find(41);
                    $serie = $this->getDoctrine()->getRepository(Serie::class)->find(6);
                    
                    $view = "ordentrabajo/oow.html.twig";
            
                break;
                case "wty":

                    $tipoOrd = $this->getDoctrine()->getRepository(Tipo::class)->find(5);
                    $situacion = $this->getDoctrine()->getRepository(Situacion::class)->find(32);
                    $serie = $this->getDoctrine()->getRepository(Serie::class)->find(8);
                    $view = "ordentrabajo/wty.html.twig";
                    break;
            }
			
           $ordentrabajo->setSerie($serie);
           $ordentrabajo->setSituacion($situacion);
           $ordentrabajo->setTipo($tipoOrd);
           $ordentrabajo->setPrioridad($prioridad);
           $ordentrabajo->setFechaEntrada($date);
           $form = $this->createForm(OrdentrabajoType::class, $ordentrabajo, array('user'=>$this->getUser()));
           if ($tipo == "presupuesto")
           {
               $form->get('equipo')->remove("fechaCompraEquipo");
               $form->get('equipo')->remove("tiquet");
           }
           if($tipo == "wty")
           {
               $form->remove("tiquet");
               $form->get("equipo")->remove('tipoEquipo');
               $form->get("equipo")->remove('color');
               $form->get("equipo")->remove('color_lcd');
               $form->get("equipo")->remove('back_cover');
               $form->get("equipo")->remove('top_cover');
               $form->get("equipo")->remove('tipo_pantalla');
               $form->get("equipo")->remove('tipo_equipo');
               $form->get("equipo")->remove('observaciones');
           }
           
           $form->remove('observaciones_int');
			if($request->request->get($form->getName()))
			{	
				$data = [];
				$data = array_merge($request->request->get($form->getName()),["situacion"=>$situacion->getId(),"prioridad"=>$prioridad->getId(),"fecha_entrada"=>$date->format("d/m/y")]);
				$request->request->set($form->getName(),$data);
			}
           $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $delegacion = $this->getUser()->getDelegacion();
                $ordentrabajo->setDelegacion($delegacion);
                $ordentrabajo->setSerie($serie);
                $ordentrabajo->setSituacion($situacion);
                $ordentrabajo->setTipo($tipoOrd);
                $ordentrabajo->setPrioridad($prioridad);
                $ordentrabajo->setFechaEntrada(new \DateTime());
                $entityManager->persist($ordentrabajo);
                $entityManager->flush();
                
                $ordentrabajo = $this->importOrder($ordentrabajo);
                
                return $this->redirectToRoute('ordentrabajo_images',["n_orden"=>$ordentrabajo->getNOrden()]);
            }
            return $this->render($view, [
                'ordentrabajo' => $ordentrabajo,
                'form' => $form->createView(),]);
            
        }
        

        return $this->render('ordentrabajo/new.html.twig');
    }

    /**
     * @Route("/view/{n_orden}", name="ordentrabajo_show", methods={"GET"})
     */
    public function show(Ordentrabajo $ordentrabajo, Request $request): Response
    {
        $this->updateOT($ordentrabajo->getNOrden());

        $ftp = ftp_ssl_connect("");
        

        ftp_login($ftp,"",");

        $ftplist = ftp_nlist($ftp,"/");
        ftp_close($ftp);

        $result = in_array("/PRESUPUESTOS_TF_".$ordentrabajo->getNPresupuesto().".pdf",$ftplist);
        
        $form = $this->createForm(OrdentrabajoType::class, $ordentrabajo, array('user'=>$this->getUser()));

        if(count((array)$ordentrabajo->getMateriales()==0))
        {
            $form->remove('materiales');
        }

        return $this->render('ordentrabajo/edit.html.twig', [
            'ordentrabajo' => $ordentrabajo,
            'form' => $form->createView(),
            'budget' => $result
        ]);
    }

    /**
     * @Route("/view/{n_orden}/budget", name="ordentrabajo_budget")
    */
    public function getOrderBudget(Ordentrabajo $ordentrabajo)
    {
        $ftp = ftp_ssl_connect("");


        ftp_login($ftp,"","");

        $file = ftp_get($ftp,$this->getParameter("pdf")."/".$ordentrabajo->getNPresupuesto().".pdf","PRESUPUESTOS_TF_".$ordentrabajo->getNPresupuesto().".pdf",FTP_BINARY);
        ftp_close($ftp);

        return $this->file($this->getParameter("pdf")."/".$ordentrabajo->getNPresupuesto().".pdf","PR".$ordentrabajo->getNCaso().".pdf");
    }

    /**
     * 
     * @isGranted("ROLE_TECNIC", statusCode=403, message="Acceso denegado")
     * @Route("/{n_orden}/edit", name="ordentrabajo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Ordentrabajo $ordentrabajo, CierreReparacionesRepository $cierresRepo): Response
    {
        $this->updateOT($ordentrabajo->getNOrden());
        $form = $this->createForm(OrdentrabajoType::class, $ordentrabajo, array('user'=>$this->getUser()));
        $form->handleRequest($request);
        $ftp = ftp_ssl_connect("");
        
        
        ftp_login($ftp,"PR","#PR22/01/2019#RT");
        
        $ftplist = ftp_nlist($ftp,"/");
        ftp_close($ftp);
        
        $result = in_array("/PRESUPUESTOS_TF_".$ordentrabajo->getNPresupuesto().".pdf",$ftplist);

        $cierre = $cierresRepo->findOneBy(["NOrden"=>$ordentrabajo->getNOrden()]);
        return $this->render('ordentrabajo/edit.html.twig', [
            'cierre' => $cierre,
            'ordentrabajo' => $ordentrabajo,
            'form' => $form->createView(),
            'budget'=>$result
        ]);
    }
    
    /**
     * @Route("/{n_orden}/change", name="ordentrabajo_presupuesto")
     * @param Ordentrabajo $ot
     * @param mixed $type
     * @return Response
     */
    public function changeSituation(Ordentrabajo $ot, Request $req, ConfCierreReparacionRepository $conf) : Response
    {
        switch ($req->get("type"))
        {
            case 1:
                $ot->setPresupuestoAceptado(true);
                $newSituacion = $conf->getSituacion($ot->getSituacion()->getId(),true);
                break;
            case 0:
                $ot->setPresupuestoAceptado(false);
                $newSituacion = $conf->getSituacion($ot->getSituacion()->getId(),false);
                break;
            case 2:
                $newSituacion = $conf->getSituacion($ot->getSituacion()->getId(),false);
                break;
        }
        if($newSituacion)
        {
            $ot->setSituacion($newSituacion->getSituacionFinal());
            $em = $this->getDoctrine()->getManager();
            $em->persist($ot);
            $em->flush();
        }
        return $this->redirectToRoute("ordentrabajo_edit", ["n_orden"=>$ot->getNOrden()]);
    }
    
    

    /**
     * @ParamConverter("Ordentrabajo", options={"id" = "n_orden"})
     * @Route("/{n_orden}/delete", name="ordentrabajo_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Ordentrabajo $ordentrabajo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ordentrabajo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ordentrabajo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ordentrabajo_index');
    }
    
    /**
     *  @Route("/{n_orden}/import", name="ordentrabajo_import")
     *  @IsGranted("ROLE_TECNIC", message="Acceso denegado, solo el personal de TECHFORE puede crear ordenes")
     */
    
    public function import(Ordentrabajo $ot) : Response
    {
    
        $this->importOrder($ot);
        
        return $this->redirectToRoute("ordentrabajo_edit",["n_orden"=>$ot->getNOrden()]);
    }
    
    
    /**
     * @Route("/{n_orden}/update", name="ordentrabajo_update")
     * @isGranted("ROLE_TECNIC", statusCode=403, message="Acceso denegado, SOLO EL PERSONAL DE TECHFORE PUEDE ACTUALIZAR ORDENES")
     */
    public function update(Ordentrabajo $ot)
    {
        $this->updateOrder($ot);
        return $this->redirectToRoute("ordentrabajo_edit",["n_orden"=>$ot->getNOrden()]);   
    }
    
    public function updateOrder(Ordentrabajo $ot, $message, $tiquet = "")
    {
        $pr = '';
        if($ot->getPresupuestoAceptado())
        {
            $pr = 'a';
        }
        $datos_post = http_build_query(
            array(
                'order' => $ot->getNOrden(),
                'situacion' => $ot->getSituacion()->getIdTr(),
                'pr' => $pr,
                'message' => $message,
                'tiquet' => $tiquet
            )
            );
        $opciones = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $datos_post
            )
        );
        $contexto = stream_context_create($opciones);
        
        file_get_contents($this->getParameter("srv").'ot/update', false, $contexto);
        
        
        
    } 
    
    /**
     * @isGranted("ROLE_TECNIC", statusCode=403, message="Acceso denegado, SOLO EL PERSONAL DE TECHFORE PUEDE CREAR ORDENES")
     */
    public function importOrder(Ordentrabajo $ot)
    {
        $em = $this->getDoctrine()->getManager();
        $tiquet = "";
        if($ot->getNOrden())
        {
            return NULL ;
        }else
        {
            IF($ot->getTipo()->getId()==5)
            {
                
                $datos_post = http_build_query(
                    array(
                        'delegacion' => $ot->getDelegacion()->getIdTr(),
                        'serie' => $ot->getSerie()->getIdTr(),
                        'tipo'=>$ot->getTipo()->getIdTr(),
                        'situacion'=>$ot->getSituacion()->getIdTr(),
                        'prioridad'=>$ot->getPrioridad()->getIdTr(),
                        'fechaentrada'=>$ot->getFechaEntrada()->format("Y-m-d"),
                        'indicaciones-cliente'=>$ot->getIndicacionesCliente(),
                        'averias'=>$ot->getAveriasDetectadas(),
                        'poblaciones'=>$ot->getDelegacion()->getPoblacion(),
                        'direccion'=>$ot->getDelegacion()->getDireccion(),
                        'email'=>$ot->getDelegacion()->getEmail(),
                        'telefono'=>$ot->getDelegacion()->getTelefono(),
                        'provincia'=>$ot->getDelegacion()->getProvincia(),
                        'caso'=>$ot->getNCaso(),
                        'nserie'=>$ot->getEquipo()->getNSerie() ? $ot->getEquipo()->getNSerie() : "",
                        'marca'=>$ot->getEquipo()->getMarca() ? $ot->getEquipo()->getMarca()->getNombre() : "",
                        'modelo'=>$ot->getEquipo()->getModelo() ? $ot->getEquipo()->getModelo()->getNombre() : "",
                        'ProductNumber'=>$ot->getEquipo()->getPartnumber() ? $ot->getEquipo()->getPartnumber()->getPartnumber() : "",
                        'trabajos'=>$ot->getTrabajosaRealizar() ? $ot->getTrabajosaRealizar() : "",
                        'tiquet_equipo'=>$ot->getEquipo()->getTiquet()." ".$ot->getEquipo()->getFechaCompraEquipo()->format("d/m/Y"),
                        'tiquet_pr' => "",
                        'color' => "",
                        'pantalla'=>"",
                        'tipo_equipo'=>"",
                        'observaciones'=> $ot->getObservaciones() ? $ot->getObservaciones() : "",
                        'observaciones_equipo'=> $ot->getEquipo()->getObservaciones() ? $ot->getEquipo()->getObservaciones() : "",
                        'orden'=> $ot->getNOrden() ? "1" : "0",
                        'accesorios'=> $ot->getAccesorios() ? $ot->getAccesorios() : "",
                        'nombrecomercial'=>"GRANDES ALMACENES FNAC ESPAÑA S.A.U.",
                    )
                    );
            }else if($ot->getTipo()->getId()==6)
            
            {
                IF($ot->getTiquet())
                {
                    if($ot->getTiquet()->getCobrado())
                    {
                        $tiquet .= "NO COBRADO ";
                    }
                    
                    if($ot->getTiquet()->getCupon())
                    {
                        $tiquet .= "CHEQUE REPARACIÓN";
                    }
                    
                    if(!$ot->getTiquet()->getCobrado() && !$ot->getTiquet()->getCupon())
                    {
                        $tiquet .= $ot->getTiquet()->getNumero()." ".$ot->getTiquet()->getFecha()->format("d/m/Y");
                    }
                }
                $datos_post = http_build_query(
                    array(
                        'delegacion' => $ot->getDelegacion()->getIdTr(),
                        'serie' => $ot->getSerie()->getIdTr(),
                        'tipo'=>$ot->getTipo()->getIdTr(),
                        'situacion'=>$ot->getSituacion()->getIdTr(),
                        'prioridad'=>$ot->getPrioridad()->getIdTr(),
                        'fechaentrada'=>$ot->getFechaEntrada()->format("Y-m-d"),
                        'indicaciones-cliente'=>$ot->getIndicacionesCliente(),
                        'averias'=>$ot->getAveriasDetectadas(),
                        'poblaciones'=>$ot->getDelegacion()->getPoblacion(),
                        'direccion'=>$ot->getDelegacion()->getDireccion(),
                        'email'=>$ot->getDelegacion()->getEmail(),
                        'telefono'=>$ot->getDelegacion()->getTelefono(),
                        'provincia'=>$ot->getDelegacion()->getProvincia(),
                        'caso'=>$ot->getNCaso(),
                        'nserie'=>$ot->getEquipo()->getNSerie() ? $ot->getEquipo()->getNSerie() : "",
                        'marca'=>$ot->getEquipo()->getMarca() ? $ot->getEquipo()->getMarca()->getNombre() : "",
                        'modelo'=>$ot->getEquipo()->getModelo() ? $ot->getEquipo()->getModelo()->getNombre() : "",
                        'ProductNumber'=>$ot->getEquipo()->getPartnumber() ? $ot->getEquipo()->getPartnumber()->getPartnumber() : "",
                        'trabajos'=>$ot->getTrabajosaRealizar() ? $ot->getTrabajosaRealizar() : "",
                        'color'=>'LCD/BEZEL: '.$ot->getEquipo()->getColorLcd().' | BASE/TAPA: '.$ot->getEquipo()->getColor().' | TOP COVER: '.$ot->getEquipo()->getTopCover().' | BACK COVER: '.$ot->getEquipo()->getBackCover(),
                        'pantalla'=>$ot->getEquipo()->getTipoPantalla() ? $ot->getEquipo()->getTipoPantalla() :"",
                        'tipo_equipo'=>$ot->getEquipo()->getTipoEquipo() ? $ot->getEquipo()->getTipoEquipo()->getNombre() : "",
                        'tiquet_pr'=>$tiquet,
                        'tiquet_equipo'=>"",
                        'fecha_compra_equipo'=>"",
                        'orden'=> $ot->getNOrden() ? "1" : "0",
                        'observaciones'=> $ot->getObservaciones() ? $ot->getObservaciones() : "",
                        'observaciones_equipo'=> $ot->getEquipo()->getObservaciones() ? $ot->getEquipo()->getObservaciones() : "",
                        'accesorios'=> $ot->getAccesorios() ? $ot->getAccesorios() : "",
                        'nombrecomercial'=>"GRANDES ALMACENES FNAC ESPAÑA S.A.U.",
                    )
                    );
            }
            
            $opciones = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $datos_post
                )
            );
            
            $contexto = stream_context_create($opciones);
            
            $resultado = file_get_contents($this->getParameter("srv").'ot/insert/new', false, $contexto);
            
            $data = json_decode($resultado, true);
            if(!$ot->getNOrden())
            {
                $ot->setNOrden($data[0]["N_ORDEN_TRABAJO"]);
                $ot->setActualizar(true);
                $em->persist($ot);
                $em->flush();
            }
            
            
        }
        
        
        return  $ot;
    }
    

    public function updateOT($caso)
    {
        
        $data = json_decode(file_get_contents($this->getParameter("srv")."ot?order=".$caso), true);
        $ot = array();
        if(!empty($data))
        {   
            $i = 0;
            foreach($data as $value)
            {
                $em = $this->getDoctrine()->getManager();
                if(!$ordentrabajo = $this->getDoctrine()->getRepository(Ordentrabajo::class)->findOneBy(array("n_orden"=>$value["N_ORDEN_TRABAJO"])))
                {
                    $ordentrabajo =  new Ordentrabajo();
                }
                $ordentrabajo = $this->mergeData($ordentrabajo, $value);
                $em->persist($ordentrabajo);
               
                
                $datam = json_decode(file_get_contents($this->getParameter("srv")."ot/mats?order=".$ordentrabajo->getNOrden()), true);
                if(!empty($datam))
                {
                    foreach ($datam as $valuem)
                    {
                        if(!$this->getDoctrine()->getRepository(Materiales::class)->findOneBy(["id_linea_presupuesto"=>$valuem["ID_LINEAS_PRESUPUESTOS"]]))
                        {
                            for($i=0;$i<$valuem["CANTIDAD"];$i++)
                            {

                                $mat = new Materiales();
                                $mat->setNombre($valuem["DESCRIPCION"]);
                                $mat->setIdLineaPresupuesto($valuem["ID_LINEAS_PRESUPUESTOS"]);
                                $mat->setOrdenTrabajo($ordentrabajo);
                                $em->persist($mat);
                                
                                
                            }
                        }
                    }  
                }
                if($i == 30)
                {
                  $em->flush();
                  $i = 0;
                }
                $i++;
                $ot[]=$ordentrabajo;
            }
            $this->getDoctrine()->getManager()->flush();
            
        }
        else
        {
            return null;
        }
        
        return $ot;
        
    }
    /**
     * @isGranted("ROLE_CENTRAL", statusCode=403, message="Acceso denegado")
    */
    public function updateAllOT()
    {
        $this->getDoctrine()->getRepository(Ordentrabajo::class)->reset();
        $data = json_decode(file_get_contents($this->getParameter("srv")."ot/getALL"), true);
        if(!empty($data))
        {   
            foreach($data as $value)
            {
                $em = $this->getDoctrine()->getManager();
                if(!$ordentrabajo = $this->getDoctrine()->getRepository(Ordentrabajo::class)->findOneBy(array("n_orden"=>$value["N_ORDEN_TRABAJO"])))
                {
                    $ordentrabajo =  new Ordentrabajo();
                }
                $ordentrabajo = $this->mergeData($ordentrabajo, $value);
                $em->persist($ordentrabajo);
                $em->flush();
            }
        }
        else
        {
            return null;
        }
        
        return $ordentrabajo;
        
    }
    public function updateAllOTClinica($clinica)
    {
        
        $data = json_decode(file_get_contents($this->getParameter("srv")."ot/getALL/clinica?clinica=$clinica"), true);
        if(!empty($data))
        {   
            $this->getDoctrine()->getRepository(Ordentrabajo::class)->reset($this->getUser()->getDelegacion()->getId());
            foreach($data as $value)
            {
                $em = $this->getDoctrine()->getManager();
                if(!$ordentrabajo = $this->getDoctrine()->getRepository(Ordentrabajo::class)->findOneBy(array("n_orden"=>$value["N_ORDEN_TRABAJO"])))
                {
                    $ordentrabajo =  new Ordentrabajo();
                }
                $ordentrabajo = $this->mergeData($ordentrabajo, $value);
                $em->persist($ordentrabajo);
                
            }
            $em->flush();
        }
        else
        {
            return null;
        }
        
        return $ordentrabajo;
        
    }
    
    public function mergeData(Ordentrabajo $ordentrabajo, array $value)
    {
        if(!empty($value["CAMPOCONFC2"]))
        {
            $equipo = $this->getDoctrine()->getRepository(Equipo::class)->findOneBy(["n_serie"=>$value["CAMPOCONFC2"]]);
            if(!$equipo)
            {
                $equipo = new Equipo();
                $equipo->setNSerie($value["CAMPOCONFC2"]);
            }
            if(isset($value["CAMPOCONFC3"]))
            {
                if(!$marca = $this->getDoctrine()->getRepository(Marca::class)->findMarcaByName(strtoupper(trim($value["CAMPOCONFC3"]))))
                {
                    $marca = new Marca();
                    $marca->setVisible(false);
                    $marca->setNombre(strtoupper(trim($value["CAMPOCONFC3"])));
                }
                $equipo->setMarca($marca);
            }
            if(isset($value["CAMPOCONFC4"]))
            {
                if(!$modelo = $this->getDoctrine()->getRepository(Modelo::class)->findModeloByName(strtoupper(trim($value["CAMPOCONFC4"]))))
                {
                    $modelo = new Modelo();
                    
                    $modelo->setNombre(strtoupper(trim($value["CAMPOCONFC4"])));
                    $modelo->setVisible(false);
                    $modelo->setMarca($marca);
                }
                
                $equipo->setModelo($modelo);
            }
            if(isset($value["CAMPOCONFC5"]))
            {
                $partNumber = $this->getDoctrine()->getRepository(PartNumber::class)->findOneBy(["marca"=>$marca,"Modelo"=>$modelo]);
                if(!$partNumber)
                    $partNumber = new PartNumber();
                $partNumber->setPartnumber(str_replace(" ", "", $value["CAMPOCONFC5"]));
                $partNumber->setMarca($marca);
                $partNumber->setModelo($modelo);
                $this->getDoctrine()->getManager()->persist($partNumber);
                $equipo->setPartnumber($partNumber);
            }
            $ordentrabajo->setEquipo($equipo);
            
            
        }
        
        
        
        $ordentrabajo->setSituacion($this->getDoctrine()->getRepository(Situacion::class)->findOneBy(["idTr"=>$value["ID_SAT_ORDEN_SITUACION"]]));
        $ordentrabajo->setSerie($this->getDoctrine()->getRepository(Serie::class)->findOneBy(["idTr"=>$value["ID_SERIES"]]));
        $ordentrabajo->setIndicacionesCliente($value["INDICACIONES_CLIENTE"]);
        $ordentrabajo->setNOrden($value["N_ORDEN_TRABAJO"]);
        $ordentrabajo->setFechaEntrada(date_create_from_format("Y-m-d", $value["FECHA_ENTRADA"]));
        $ordentrabajo->setFechaSalida($value["FECHA_SALIDA"] ? date_create_from_format("Y-m-d", $value["FECHA_SALIDA"]) : NULL);
        $ordentrabajo->setNCaso($value["CAMPOCONFC1"]);
        $ordentrabajo->setAveriasDetectadas($value["AVERIAS_DETECTADAS"]);
        $ordentrabajo->setTrabajosaRealizar($value["TRABAJOS_AREALIZAR"]);
        $ordentrabajo->setObservaciones($value["OBSERVACIONES"]);
        $ordentrabajo->setObservacionesInt($value["OBSERVACIONES_INT"]);
        $ordentrabajo->setNPresupuesto($value["N_PRESUPUESTO"]);
        $ordentrabajo->setDelegacion($this->getDoctrine()->getRepository(Delegacion::class)->findOneBy(["idTr"=>$value["ID_CLIDIRECCIONES"]]));
        $ordentrabajo->setPrioridad($this->getDoctrine()->getRepository(Prioridad::class)->findOneBy(array("idTr"=>$value["ID_SAT_ORDPRIORIDAD"])));
        $ordentrabajo->setTipo($this->getDoctrine()->getRepository(Tipo::class)->findOneBy(array("idTr"=>$value["ID_SAT_ORDTIPO"])));
        IF($value["CAMPOCONFL4"] == 'a')
        {
            $ordentrabajo->setPresupuestoAceptado(true);
        }
        
        $ordentrabajo->setActualizar(true);
        
        
        return $ordentrabajo;
        
        
    }






}
