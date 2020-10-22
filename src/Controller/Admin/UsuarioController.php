<?php

namespace App\Controller\Admin;

use App\Entity\Usuario;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use App\Security\LoginAuthenticator;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use App\Form\RegistrationFormType;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use App\Service\FileUploader;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Form\CsvType;
use App\Entity\Delegacion;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

/**
 * @isGranted("ROLE_USER", statusCode=403, message="Acceso denegado")
 * @Route("/usuarios")
 */
class UsuarioController extends AbstractController
{
    /**
     * @Route("/", name="usuario_index", methods={"GET"})
     * @Security("is_granted('ROLE_ADMIN') or is_granted('ROLE_CENTRAL')")
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    /**
     * @Route("/import", name="importUsers")
     * @isGranted("ROLE_ADMIN")
     */
    public function importUsersCSV(Request $req, FileUploader $file, UserPasswordEncoderInterface $encoder)
    {
        $form = $this->createForm(CsvType::class);

        $form->handleRequest($req);
        if($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $csv = $data['file'];
            $fcsv = fopen($csv,'r');
            $FHeaders = 0;
            $em = $this->getDoctrine()->getManager();
            while($data = fgetcsv($fcsv,null,";"))
            {
                if($FHeaders === 0)
                {
                    $headers = $data;
                    $FHeaders++;
                }
                else
                {
                    $data = array_combine($headers,$data);
                
                    $user = $this->getDoctrine()->getRepository(Usuario::class)->findOneBy(["username"=>utf8_encode($data["Usuario"])]);
                    $delegacion = $this->getDoctrine()->getRepository(Delegacion::class)->find($data["Clinica"]);
                    if(null === $user)
                    {
                        $user = new Usuario();
                    }
                    $user->setUsername(utf8_encode($data["Usuario"]));
                    $user->setNombre(utf8_encode($data["Nombre"]));
                    $user->setApellidos(utf8_encode($data["Primer_Apellido"])." ".utf8_encode($data["Segundo_Apellido"]));
                    $user->setPassword($encoder->encodePassword($user,$data["Pass"]));
                    $user->setDelegacion($delegacion);
                    $em->persist($user);
                    
                }
                
            }
            $em->flush();
            fclose($fcsv);
            unlink($csv);
            
        }
        return $this->render("usuario/importcsv.twig",array("form"=>$form->createView()));
    }

    /**
     * @isGranted("ROLE_ADMIN", statusCode=403, message="Acceso denegado")
     * @Route("/registrar", name="app_register")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginAuthenticator $authenticator): Response
    {
        $user = new Usuario();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            /*
            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
                
            );*/
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    


    /**
     * @Route("/{id}", name="usuario_show", methods={"GET"})
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * 
     * @Route("/{id}/edit", name="usuario_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Usuario $usuario, UserPasswordEncoderInterface $pwdencoder): Response
    {
        if($this->getUser()->getId() === $usuario->getId() || $this->isGranted("ROLE_CENTRAL"))
        {
            $form = $this->createForm(UsuarioType::class, $usuario);
            
            if(!$this->isGranted("ROLE_CENTRAL"))
            {
                $form->remove('delegacion');
                $form->remove('isactive');
            }
            
            $form->handleRequest($request);
            
            if ($form->isSubmitted() && $form->isValid()) {
                $usuario = $this->getDoctrine()->getRepository(Usuario::class)->find($usuario->getId());
                foreach ( $usuario->getRoles() as $role)
                {
                    if(!$this->isGranted($role))
                        throw new AccessDeniedException("No puedes editar este usuario");
                }
                if($form->get('plainPassword')->getData()!== null)
                {
                    $usuario->setPassword($pwdencoder->encodePassword($usuario, $form->get('plainPassword')->getData()));
                }
                
                $this->getDoctrine()->getManager()->flush();
                
                return $this->redirectToRoute('usuario_edit', [
                    'id' => $usuario->getId(),
                ]);
            }
            
            return $this->render('usuario/edit.html.twig', [
                'usuario' => $usuario,
                'form' => $form->createView(),
            ]);
            
        }
        throw new AccessDeniedException("Accesso Denegado, solo puedes editar tu usuario, este intento a sido registrado.");
        
    }

    /**
     * @Route("/{id}", name="usuario_delete", methods={"DELETE"})
     * @isGranted("ROLE_ADMIN")
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('usuario_index');
    } 
}
