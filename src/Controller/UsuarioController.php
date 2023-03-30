<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Rol;
use App\Entity\AgentesFunerarias;
use App\Entity\Funerarias;
use App\Entity\Mail;
use App\Entity\Delegacion;
use App\Entity\Entidad;
use App\Form\UsuarioType;
use App\Form\RegistroType;
use App\Form\AsociarFunerariaType;
use App\Form\AsociarOrganismoType;
use App\Form\CambiarPasswordType;
use App\Form\RegistroUsuarioMunicipioType;
use App\Repository\UsuarioRepository;
//use App\Repository\ItemRepository;
use App\Repository\EntidadRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/usuario")
 */
class UsuarioController extends AbstractController
{

    /**
     * @Route("/", name="usuario", methods={"GET"})
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->obtenerTodos(),
            // 'items' => $itemRepository->obtenerTodos(),
        ]);
    }

    /**
     * @Route("/nuevo", name="usuario_nuevo", methods={"GET","POST"})
     */
    public function new(Request $request, UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        $now = new \DateTime();

        if ($form->isSubmitted() && $form->isValid()) {

            $usuario->setFechaAlta($now);
            $password = $encoder->encodePassword($usuario, $usuario->getDni());
            $usuario->setPassword($password);

            if ($usuarioRepository->guardar($usuario)) :
                $this->get('session')->getFlashBag()->add('success', 'OK - Usuario creado con exito.');
                return $this->redirectToRoute('usuario');
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'ERROR - No se pudo crear al usuario.');
            return $this->redirectToRoute('usuario');
        }

        return $this->render('usuario/nuevo.html.twig', [
            'usuario' => $usuario,

            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/editar", name="usuario_editar", methods={"GET","POST"})
     */
    public function edit(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) :

            if ($usuarioRepository->guardar($usuario)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! Usuario actualizado con exito.');
                return $this->redirectToRoute('usuario_editar', ['id' => $usuario->getId()]);
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se puedo actualizar el usuario.');
            return $this->redirectToRoute('usuario_editar', ['id' => $usuario->getId()]);
        endif;

        return $this->render('usuario/editar.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/eliminar/{id}", name="usuario_eliminar", methods={"DELETE"})
     */
    public function delete(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository): Response
    {
        if ($this->getUser()->getRol()->getId() == Rol::ROLE_ADMIN) {
            $ruta = 'usuario';
        } else {
            if ($usuario->getRol()->getId() == Rol::ROLE_ORGANISMO) {
                $ruta = 'usuario_municipios';
            } else {
                $ruta = 'usuario_delegacion';
            }
        }

        if ($this->isCsrfTokenValid('delete' . $usuario->getId(), $request->request->get('_token'))) :
            if ($usuarioRepository->eliminar($usuario)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! El usuario ha sido eliminado con exito.');
                return $this->redirectToRoute($ruta);
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se pudo eliminar el usuario.');
            return $this->redirectToRoute($ruta);
        endif;
        $this->get('session')->getFlashBag()->add('warning', 'Cuidado! no se pudo validad la accion, por lo que no se elimin� al usuario.');
        return $this->redirectToRoute($ruta);
    }

    /**
     * @Route("/clave/{id}", name="usuario_clave", methods={"POST"})
     */
    public function clave(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $encoder): Response
    {
        if ($this->isCsrfTokenValid('password' . $usuario->getId(), $request->request->get('_token'))) :

            $current_password = $request->request->get('current_password');
            $new_password = $request->request->get('new_password');
            $new_password_confirm = $request->request->get('confirm_new_password');

            if (!$encoder->isPasswordValid($usuario, $current_password)) :
                $this->get('session')->getFlashBag()->add('danger', utf8_encode('ERROR! Contraseña actual incorrecta.'));
                return $this->redirectToRoute('usuario_perfil');
            endif;

            $password = $encoder->encodePassword($usuario, $new_password);
            $usuario->setPassword($password);

            if ($usuarioRepository->guardar($usuario)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! La clave a sido cambiada.');
                return $this->redirectToRoute('usuario_perfil');
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se pudo cambiar la clave.');
            return $this->redirectToRoute('usuario_perfil');
        endif;

        $this->get('session')->getFlashBag()->add('warning', 'Cuidado! no se pudo validad la accion, por lo que no se pudo cambiar la clave.');
        return $this->redirectToRoute('usuario_perfil');
    }

    /**
     * @Route("/perfil", name="usuario_perfil", methods={"GET"})
     */
    public function perfil(Request $request, UsuarioRepository $usuarioRepository): Response
    {
        $usuario = $this->getUser();

        return $this->render('usuario/perfil.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * @Route("/registrar/funeraria", name="usuario_registrar", methods={"GET","POST"})
     */
    public function registrar(Request $request, UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $usuario = new Usuario();
        $form->handleRequest($request);

        $now = new \DateTime();
        $mail = new Mail();

        if ($form->isSubmitted() && $form->isValid()) {
            $params = $request->request->all();

            $personalFuneraria = $this->getDoctrine()
                ->getRepository(AgentesFunerarias::class)
                ->findOneBy(['dni' => $usuario->getDni(), 'borrado' => 0, 'funeraria' => $params['registro']['funeraria']]);

            if (!$personalFuneraria) {
                $this->get('session')->getFlashBag()->add('danger', 'Error - No se pudo dar de alta el usuario, por que el DNI ingresado no se encuetra habilitado');
                return $this->render('usuario/registro.html.twig', [
                    'usuario' => $usuario,
                    'form' => $form->createView(),
                ]);
            }

            $usuarioExistente = $this->getDoctrine()
                ->getRepository(Usuario::class)
                ->findOneBy(['dni' => $usuario->getDni()]);

            if ($usuarioExistente) {
                $this->get('session')->getFlashBag()->add('danger', 'Error - No se pudo dar de alta el usuario, su DNI ya ha sido registrado');
                return $this->render('usuario/registro.html.twig', [
                    'usuario' => $usuario,
                    'form' => $form->createView(),
                ]);
            }

            $personalFuneraria->getRazonSocial();


            $rol = $this->getDoctrine()
                ->getRepository(Rol::class)
                ->findOneBy(['nombre' => 'ROLE_FUNERARIA']);

            $funeraria = $this->getDoctrine()
                ->getRepository(Funerarias::class)
                ->findOneBy(['id' => $params['registro']['funeraria']]);


            $usuario->setRol($rol);
            $passwordPlano = $this->generarPassword(8);
            $password = $encoder->encodePassword($usuario, $passwordPlano);
            $usuario->setPassword($password);
            $usuario->setActivo(1);


            $usuario->addFuneraria($funeraria);



            if ($usuarioRepository->guardar($usuario)) :

                $this->get('session')->getFlashBag()->add('success', 'OK - Usuario creado con exito, su clave ha sido enviada a su correo electronico ');


                $htmlTextoAEnviar = '<p>Bienvenido a la plataforma de Defunciones Online</p>'
                    . '<p>Estas son sus credenciales de acceso:</p>'
                    . '<p>usuario:' . $usuario->getDni() . '</p>'
                    . '<p>password:<b>' . $passwordPlano . '</b></p><br>'
                    . '<p>Respete las Mayusculas y minusculas</p>';
                $mail->enviarMail($usuario->getEmail(), 'Credenciales de acceso', $htmlTextoAEnviar);

                return $this->render('usuario/registro.html.twig', [
                    'usuario' => $usuario,
                    'inserto' => true,
                    'form' => $form->createView(),
                ]);

            endif;

            $this->get('session')->getFlashBag()->add('danger', 'ERROR - No se pudo crear al usuario.');
            return $this->render('usuario/registro.html.twig', [
                'usuario' => $usuario,
                'inserto' => true,
                'form' => $form->createView(),
            ]);
        }

        return $this->render('usuario/registro.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    //    /**
    //     * @Route("/registrar/municipio", name="usuario_registrar_municipio", methods={"GET","POST"})
    //     */
    //    public function registrarParaMunicipio(Request $request, UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $encoder): Response {
    //        $usuario = new Usuario();
    //        $form = $this->createForm(RegistroUsuarioMunicipioType::class, $usuario);
    //        $form->handleRequest($request);
    //
    //        $now = new \DateTime();
    //        $mail = new Mail();
    //
    //
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $params = $request->request->all();
    ////            echo "<pre>";
    ////            var_dump($params);
    ////            echo "</pre>";
    ////            Exit;
    //            $usuarioExistente = $this->getDoctrine()
    //                    ->getRepository(Usuario::class)
    //                    ->findOneBy(['dni' => $usuario->getDni()]);
    //
    //            if ($usuarioExistente) {
    //                $this->get('session')->getFlashBag()->add('danger', 'ERROR - No se pudo dar de alta el usuario, su DNI ya ha sido registrado');
    //                return $this->render('usuario/registro_usuario_municipio.html.twig', [
    //                            'usuario' => $usuario,
    //                            'form' => $form->createView(),
    //                ]);
    //            }
    //
    //            $rol = $this->getDoctrine()
    //                    ->getRepository(Rol::class)
    //                    ->findOneBy(['id' => Rol::ROLE_ORGANISMO]);
    //            $entidad = $this->getDoctrine()
    //                    ->getRepository(Entidad::class)
    //                    ->findOneBy(['id' => $params['registro_usuario_municipio']['entidad']]);
    //
    //            $usuario->setRol($rol);
    //            $usuario->addEntidad($entidad);
    //            $usuario->setActivo(0);
    //
    //            $password = $encoder->encodePassword($usuario, $this->generarPassword(8));
    //            $usuario->setPassword($password);
    //
    //            if ($usuarioRepository->guardar($usuario)):
    //                
    //                /*activo el organismo seleccionado */
    //                $usuarioRepository->activarOrganismoSeleccionado($usuario->getId(),$entidad->getId());
    //                /* fin */
    //                $this->get('session')->getFlashBag()->add('success', 'Datos Enviados con exito para su verificacion. Luego recibira un mail con sus datos de acceso');
    //
    //                return $this->render('usuario/registro_usuario_municipio.html.twig', [
    //                            'usuario' => $usuario,
    //                            'inserto' => true,
    //                            'form' => $form->createView(),
    //                ]);
    //
    //            endif;
    //
    //            $this->get('session')->getFlashBag()->add('danger', 'ERROR - No se pudo crear al usuario.');
    //            return $this->redirectToRoute('usuario');
    //        }
    //
    //        return $this->render('usuario/registro_usuario_municipio.html.twig', [
    //                    'usuario' => $usuario,
    //                    'form' => $form->createView(),
    //        ]);
    //    }
    //
    //    /**
    //     * @Route("/municipios", name="usuario_municipios", methods={"GET"})
    //    */
    //    public function listadoParaMunicipios(UsuarioRepository $usuarioRepository): Response {
    //       
    //        $usuarios=$usuarioRepository->obtenerTodosConOrigenMunicipioArray();
    //
    //        return $this->render('usuario/listado_para_municipios.html.twig', [
    //                    'usuarios' =>$usuarios 
    //        ]);
    //    }

    /**
     * @Route("/delegacion", name="usuario_delegacion", methods={"GET"})
     */
    public function listadoParaDelegaciones(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->obtenerTodosConOrigenDelegacion(),
        ]);
    }

    /**
     * @Route("/municipios/activar/{id}", name="usuario_activar", methods={"GET"})
     */
    public function activar(Request $request, Usuario $usuario, UsuarioRepository $usuarioRepository, UserPasswordEncoderInterface $encoder): Response
    {
        $template = 'usuario_municipios';
        $mail = new Mail();

        if ($usuario->getActivo()) {
            $usuario->setActivo(0);
        } else {
            $usuario->setActivo(1);
            $passwordPlano = $this->generarPassword(8);
            $password = $encoder->encodePassword($usuario, $passwordPlano);
            $usuario->setPassword($password);
        }
        if ($usuarioRepository->guardar($usuario)) {
            if ($usuario->getActivo()) {
                $htmlTextoAEnviar = '<p>Bienvenido a la plataforma de Defunciones Online</p>'
                    . '<p>Estas son sus credenciales de acceso:</p>'
                    . '<p>usuario:' . $usuario->getDni() . '</p>'
                    . '<p>password:<b>' . $passwordPlano . '</b></p><br>'
                    . '<p>Respete las Mayusculas y minusculas</p>';
                $mail->enviarMail($usuario->getEmail(), 'Credenciales de acceso', $htmlTextoAEnviar);
            }
        }

        return $this->redirectToRoute('usuario');
    }

    function generarPassword($longitud = 6, $opcLetra = TRUE, $opcNumero = TRUE, $opcMayus = TRUE, $opcEspecial = FALSE)
    {
        $letras = "abcdefghijklmnopqrstuvwxyz";
        $letras = "aeiou";
        $numeros = "1234567890";
        //$letrasMayus = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $letrasMayus = "BCDFGH";
        $especiales = "|@#~$%()=^*+[]{}-_";
        $listado = "";
        $password = "";
        if ($opcLetra == TRUE)
            $listado .= $letras;
        if ($opcNumero == TRUE)
            $listado .= $numeros;
        if ($opcMayus == TRUE)
            $listado .= $letrasMayus;
        if ($opcEspecial == TRUE)
            $listado .= $especiales;

        for ($i = 1; $i <= $longitud; $i++) {
            $caracter = $listado[rand(0, strlen($listado) - 1)];
            $password .= $caracter;
            $listado = str_shuffle($listado);
        }
        return $password;
    }

    function enviarMail($destinatario, $asunto, $texto, $tipo)
    {

        $mail = new PHPMailer;


        $body = '<html>
                    <head>
                      <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                      <title></title>
                    </head>
                    <body>
                      <div >
                        <fieldset>
                          <div align="left">   
                            
                              <img src="cid:encabezado_izquierda" alt="encabezado" align="left">

                              <img src="cid:encabezado_derecha" alt="encabezado" align="right">
                            
                        </div>
                        <br><br><br><br><br><br>

                        <div style=" font-family: Arial, Helvetica, sans-serif; font-size: 16px;">
                           
                           ' . $texto . '
                          
                        </div>

                        <br>
                        <hr>
                        <div style="text-align: center; font-family: Arial, Helvetica, sans-serif; font-size: 11px;">
                        <p>Este e-mail es enviado desde una casilla autom&aacute;tica. Por favor no lo responda.
                        Si recibi&oacute; este correo electr&oacute;nico por error, es probable que otro usuario haya ingresado su 
                        direcci&oacute;n de correo electr&eacute;nico por equivocaci&oacute;n mientras intentaba registrarse. 
                        Si no fue Ud. quien envi&oacute; la solicitud, no es necesario que tome ninguna medida y puede ignorar este correo electr&oacute;nico sin problemas.</p>    
                        <br>
                        </div>
                        <p style="text-align: center;">Ministerio de Gobierno - Direcci&oacute;n de Inform&aacute;tica y Tecnolog&iacute;as de la Informaci&oacute;n</p> 

                        </fieldset>

                      </div>
                    </body>
                    </html>';

        $mail->isSMTP();
        $mail->AddEmbeddedImage('images/encabezado_email_izquierda.png', 'encabezado_izquierda');
        $mail->AddEmbeddedImage('images/encabezado_email_derecha.png', 'encabezado_derecha');
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;
        $mail->Host = 'mail.gob.gba.gov.ar';
        $mail->Port = 587;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->SMTPOptions = array(
            'ssl' => [
                'verify_peer' => false,
                'allow_self_signed' => true,
            ],
        );
        $mail->SMTPAuth = true;
        $mail->Username = 'no-responder-partidas';
        $mail->Password = 'KdLTyOGHD1@2';
        $mail->setFrom('no-responder-partidas@gob.gba.gov.ar', $asunto);
        $mail->addAddress($destinatario, '');
        $mail->Subject = $asunto;
        $mail->msgHTML(file_get_contents('contents.html'), __DIR__);

        $mail->Body = $body;


        if ($mail->send()) {
            // echo "OK<br>";
            return true;
        } else {
            // echo " -> EL MAIL NO HA SIDO ENVIADO, VUELVA A INTENTARLO MAS TARDE ".$mail->ErrorInfo."<br>";
            return false;
        }
    }

    /**
     * @Route("/{id}/nueva-password", name="usuario_nueva_pass", methods={"POST","GET"},requirements={"id"="\d+"})
     */
    public function nuevaPassBusqueda(Request $request, Usuario $usuario, UsuarioRepository $repositorio, UserPasswordEncoderInterface $encoder): Response
    {


        $form = $this->createForm(CambiarPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $params = $request->request->all();
            $usuario = $this->getUser();
            $passwordActual = trim($params['cambiar_password']['password']);


            if ($encoder->isPasswordValid($usuario, $passwordActual)) {


                $nuevaPassword = trim($params['cambiar_password']['nuevaPassword']);
                $repetirPassword = trim($params['cambiar_password']['repetirPassword']);
                if ($nuevaPassword != $repetirPassword) {
                    $this->addFlash('danger', 'ERROR - LAS CONTRASEÑAS NO COINCIDEN');
                    return $this->redirectToRoute('usuario_nueva_pass', ['id' => $usuario->getId()]);
                } else {

                    $password = $encoder->encodePassword($usuario, trim($params['cambiar_password']['nuevaPassword']));
                    $usuario->setPassword($password);

                    if ($repositorio->guardar($usuario)) {
                        return $this->redirect($this->generateUrl('app_login'));
                    } else {
                        $this->addFlash('danger', 'ERROR - NO SE PUDO ACTUALIZAR LA CONTRASEÑA');
                    }
                }
            } else {

                $this->addFlash('danger', 'ERROR - LA CONTRASEÑA ACTUAL NO COINCIDE');
            }
        }

        return $this->render('usuario/cambiar_password.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/resetPass", name="usuario_reset_pass", methods={"POST","GET"})
     */
    public function resetPassAction(Usuario $usuario, UsuarioRepository $repositorio, UserPasswordEncoderInterface $encoder)
    {

        $dni = $usuario->getDni();
        $password = $encoder->encodePassword($usuario, $dni);

        $usuario->setPassword($password);
        if ($repositorio->guardar($usuario)) {
            $this->addFlash('success', 'OK - CONTRASEÑA RESETEADA CORRECTAMENTE');
        } else {
            $this->addFlash('danger', 'ERROR - NO SE PUDO RESETEAR LA CONTRASEÑA');
        }


        return $this->redirect($this->generateUrl('usuario'));
    }

    //    /**
    //     * @Route("/{id}/asociar/funeraria", name="usuario_asociar_funeraria", methods={"POST","GET"},requirements={"id"="\d+"})
    //     */
    //     public function asociarFuneraria(Request $request, Usuario $usuario, 
    //                                                        UsuarioRepository $usuarioRepository,
    //                                                        FunerariasRepository $funerariaRepository): Response 
    //    {
    //        
    //         if($usuario->getId()!=$this->getUser()->getId())
    //         {
    //             $this->redirect('app_login');
    //         }    
    //
    //        //$usuario = new Usuario();
    //        $form = $this->createForm(AsociarFunerariaType::class, $usuario);
    //        $form->handleRequest($request);
    //
    //        
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $params = $request->request->all();
    //            if (isset($params['asociar_funeraria']['funeraria']))
    //            {    
    //                $idFuneraria=$params['asociar_funeraria']['funeraria'];
    //                $funeraria=$funerariaRepository->findOneBy(['id'=>$idFuneraria]);
    //                if($funeraria)
    //                {
    //                        $usuario->addFuneraria($funeraria);
    //                        if($usuarioRepository->guardar($usuario))
    //                        {
    //                            $this->get('session')->getFlashBag()->add('success', 'OK - funeraria asociada al usuario'); 
    //                            //$this->redirect('bandeja_funeraria');
    //                            $form = $this->createForm(AsociarFunerariaType::class, $usuario);
    //                        }
    //                        else
    //                        {
    //                            $this->get('session')->getFlashBag()->add('danger', 'Error - no se pudo asociar la funeraria al usuario'); 
    //                        }    
    //                }
    //                else
    //                {
    //                    $this->get('session')->getFlashBag()->add('danger', 'Error - no se encontró la funeraria'); 
    //                }    
    //            }
    //            else 
    //            {
    //               $this->get('session')->getFlashBag()->add('danger', 'Error - falta seleccionar funeraria'); 
    //            }
    //        }
    //        
    //        return $this->render('usuario/asociar_funeraria.html.twig', [
    //                    'usuario' => $usuario,
    //                    'form' => $form->createView()
    //        ]);
    //    }

    //    /**
    //     * @Route("/{id}/desasociar/funeraria/{idf}", name="usuario_desasociar_funeraria", methods={"POST","GET"},requirements={"id"="\d+","idf"="\d+"})
    //     * @ParamConverter("funeraria", options={"id"="idf"})
    //     */
    //     public function desasociarFuneraria(Request $request, Usuario $usuario,Funerarias $funeraria=null, 
    //                                                        UsuarioRepository $usuarioRepository): Response 
    //    {
    //        $this->get('session')->getFlashBag()->clear();
    //        if($usuario->getId()!=$this->getUser()->getId())
    //        {
    //             $this->redirect('app_login');
    //        }    
    //
    //        if($funeraria)
    //        {
    //                    $usuario->removeFuneraria($funeraria);
    //                    if($usuarioRepository->guardar($usuario))
    //                    {
    //                        $this->get('session')->getFlashBag()->add('success', 'OK - funeraria desasociada al usuario'); 
    //                        
    //                    }
    //                    else
    //                    {
    //                        $this->get('session')->getFlashBag()->add('danger', 'Error - no se pudo desasociar la funeraria al usuario'); 
    //                    }    
    //        }
    //        else
    //        {
    //                $this->get('session')->getFlashBag()->add('danger', 'Error - no se encontró la funeraria'); 
    //        }    
    //            
    //        return $this->redirectToRoute('usuario_asociar_funeraria',['id'=>$usuario->getId()]);
    //        
    //
    //    }
    //    /**
    //     * @Route("/funeriarias", name="usuario_funerarias", methods={"GET"})
    //     */
    //    public function listadoParaFunerarias(UsuarioRepository $usuarioRepository): Response {
    //        return $this->render('usuario/funerarias.html.twig', [
    //            'usuarios' => $usuarioRepository->obtenerTodosConFuneriarias(),
    //        ]);
    //
    //    }
    //    /**
    //     * @Route("/funeriarias/editCorreo", name="usuario_funerarias_edit_correo", methods={"GET","POST"})
    //     */
    //    public function editCorreo(Request $request){
    //        $em = $this->getDoctrine()->getManager();
    //        if ($request->isMethod('POST')) {
    //            $data = $request->request->all();
    //            $repositoryUsuario = $em->getRepository('App:Usuario');
    //            $usuarioId = $data['usuario_id'];
    //            $usuario = $repositoryUsuario->obtenerPorId($usuarioId);
    //            $usuario->setEmail($data['correo']);
    //            $repositoryUsuario->guardar($usuario);
    //            $this->get('session')->getFlashBag()->add('success', 'OK - usuario editado correctamente');
    //            if($this->getUser()->getRol()->getId()==Rol::ROLE_ESTADISTICA){
    //               return $this->redirectToRoute('usuario_funerarias');
    //            }
    //            if($this->getUser()->getRol()->getId()==Rol::ROLE_ADMIN_DELEGACION){
    //                return $this->redirectToRoute('usuario_municipios');
    //            }
    //        }
    //
    //    }
    //    
    //    
    //    /**
    //     * @Route("/{id}/asociar/organismo", name="usuario_asociar_organismo", methods={"POST","GET"},requirements={"id"="\d+"})
    //     */
    //     public function asociarOrganismo(Request $request, Usuario $usuario, 
    //                                                        UsuarioRepository $usuarioRepository,
    //                                                        EntidadRepository $entidadRepository): Response 
    //    {
    //        
    //         $this->get('session')->getFlashBag()->clear();
    //         if($usuario->getId()!=$this->getUser()->getId())
    //         {
    //             $this->redirect('app_login');
    //         }    
    //
    //        
    //        $form = $this->createForm(AsociarOrganismoType::class, $usuario);
    //        $form->handleRequest($request);
    //
    //        
    //        if ($form->isSubmitted() && $form->isValid()) {
    //            $params = $request->request->all();
    //            
    //            if (isset($params['asociar_organismo']['entidad']))
    //            {    
    //                $idOrganismo=$params['asociar_organismo']['entidad'];
    //                $organismo=$entidadRepository->findOneBy(['id'=>$idOrganismo]);
    //                if($organismo)
    //                {
    //                        $usuario->addEntidad($organismo);
    //                        if($usuarioRepository->guardar($usuario))
    //                        {
    //                            $this->get('session')->getFlashBag()->add('success', 'OK - organismo asociado al usuario. '
    //                                    . 'Para poder empezar a operar con este nuevo organismo debe esperar ser habilitado.'); 
    //                            $form = $this->createForm(AsociarOrganismoType::class, $usuario);
    //                        }
    //                        else
    //                        {
    //                            $this->get('session')->getFlashBag()->add('danger', 'Error - no se pudo asociar el organismo al usuario'); 
    //                        }    
    //                }
    //                else
    //                {
    //                    $this->get('session')->getFlashBag()->add('danger', 'Error - no se encontró el organismo'); 
    //                }    
    //            }
    //            else 
    //            {
    //               $this->get('session')->getFlashBag()->add('danger', 'Error - falta seleccionar organismo'); 
    //            }
    //            
    //        }
    //        
    //        $organismosAsociados=$entidadRepository->obtenerEntidadesActivas($this->getUser()->getId());
    //        $organismosSinActivar=$entidadRepository->obtenerEntidadesInactivas($this->getUser()->getId());
    //        return $this->render('usuario/asociar_organismo.html.twig', [
    //                    'usuario' => $usuario,
    //                    'organismos_asociados'=>$organismosAsociados,
    //                    'organismos_sin_activar'=>$organismosSinActivar,
    //                    'form' => $form->createView()
    //        ]);
    //    }
    //    
    //    
    //    /**
    //     * @Route("/{id}/desasociar/organismo/{ido}", name="usuario_desasociar_organismo", methods={"DELETE"},requirements={"id"="\d+","ido"="\d+"})
    //     * @ParamConverter("entidad", options={"id"="ido"})
    //     */
    //    public function desasociarOrganismo(Request $request, Usuario $usuario, Entidad $entidad=null, 
    //                                                        UsuarioRepository $usuarioRepository): Response 
    //    {
    //        $this->get('session')->getFlashBag()->clear();
    //        if($usuario->getId()!=$this->getUser()->getId())
    //        {
    //             $this->redirect('app_login');
    //        }    
    //        
    //        if ($this->isCsrfTokenValid('delete' . $usuario->getId(), $request->request->get('_token')))
    //        {        
    //
    //            if($entidad)
    //            {
    //                        $usuario->removeEntidad($entidad);
    //                        if($usuarioRepository->guardar($usuario))
    //                        {
    //                            $this->get('session')->getFlashBag()->add('success', 'OK - organismo desasociado al usuario'); 
    //
    //                        }
    //                        else
    //                        {
    //                            $this->get('session')->getFlashBag()->add('danger', 'Error - no se pudo desasociar el organismo al usuario'); 
    //                        }    
    //            }
    //            else
    //            {
    //                    $this->get('session')->getFlashBag()->add('danger', 'Error - no se encontró el organismo'); 
    //            }   
    //        
    //        }
    //            
    //        if($this->getUser()->getRol()->getId()==Rol::ROLE_ORGANISMO)
    //        {    
    //            return $this->redirectToRoute('usuario_asociar_organismo',['id'=>$usuario->getId()]);
    //        }
    //        else
    //        {
    //            // es ADMIN DE DELEGACIONES
    //            return $this->redirectToRoute('usuario_organismo_asociados_listado',['id'=>$usuario->getId()]);
    //        }    
    //
    //    }
    //    
    //    
    //    
    //    /**
    //     * @Route("/{id}/organismosasosciados", name="usuario_organismo_asociados_listado", methods={"POST","GET"},requirements={"id"="\d+"})
    //     */
    //     public function listadoOrganismosAsociados(Usuario $usuario,EntidadRepository $entidadRepository): Response 
    //    {
    //         if($this->getUser()->getRol()->getId()!=Rol::ROLE_ADMIN_DELEGACION)
    //         {
    //             $this->redirect('app_login');
    //         }    
    //
    //        
    //        
    //        $organismosAsociados=$entidadRepository->obtenerEntidadesActivas($usuario->getId());
    //        
    //        return $this->render('usuario/listado_organismos_asociados.html.twig', [
    //                    'usuario' => $usuario,
    //                    'organismos_asociados'=>$organismosAsociados
    //                    
    //        ]);
    //    }
}
