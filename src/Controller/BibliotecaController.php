<?php

namespace App\Controller;


use App\Entity\Contenido;
use App\Entity\TipoContenido;
use App\Form\EnlaceType;
use App\Form\BibliotecaType;
use App\Repository\ContenidoRepository;
use App\Repository\TipoContenidoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/biblioteca")
 */
class BibliotecaController extends AbstractController
{

    /**
     * @Route("/", name="biblioteca_bandeja", methods={"GET"})
     */
    public function index(ContenidoRepository $contenidoRepository): Response
    {
        $enlaces = $contenidoRepository->obtenerTodosPorTipo(TipoContenido::TIPO_BIBLIOTECA);
        return $this->render('biblioteca/index.html.twig', [
            'enlaces' => $enlaces,
        ]);
    }



    /**
     * @Route("/nuevo", name="biblioteca_nuevo", methods={"GET","POST"})
     */
    public function new(Request $request, 
                        ContenidoRepository $contenidoRepository, 
                        TipoContenidoRepository $tipoContenidoRepository
                        ): Response
    {
        $enlace = new Contenido();
        $form = $this->createForm(EnlaceType::class, $enlace);
        $form->handleRequest($request);

        $now = new \DateTime();

        if ($form->isSubmitted() && $form->isValid()) {

            $enlace->setActivo(1);
            $enlace->setBorrado(0);
            
            $now = new \DateTime();
            $usuario=$this->getUser();
            $hash = hash('sha256', $usuario->getId() . $now->format('YmdHis'));
            $enlace->setHash($hash);
            
            $enlace->setTipo($tipoContenidoRepository->findOneBy(['id'=> TipoContenido::TIPO_BIBLIOTECA]));

            if ($contenidoRepository->guardar($enlace)) :
                $this->get('session')->getFlashBag()->add('success', 'OK - Enlace creado con exito.');
                return $this->redirectToRoute('biblioteca_bandeja');
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'ERROR - No se pudo crear el enlace.');
            return $this->redirectToRoute('biblioteca_nuevo');
        }

        return $this->render('enlace/nuevo.html.twig', [
            'enlace' => $enlace,
            'enlaces' => $contenidoRepository->obtenerTodos(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{hash}/editar", name="biblioteca_editar", methods={"GET","POST"})
     * @ParamConverter("contenido", options={"mapping": {"hash": "hash"}})
     */
    public function edit(Request $request, Contenido $enlace, ContenidoRepository $contenidoRepository): Response
    {
        $form = $this->createForm(BibliotecaType::class, $enlace);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) :
            $datos = $request->request->all();

            $publicado = $datos['publicado'];
            $enlace->setPublicado(new \DateTime());
            if ($contenidoRepository->guardar($enlace)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! Enlace actualizado con exito.');
                return $this->redirectToRoute('biblioteca_editar', ['id' => $enlace->getId()]);
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se puedo actualizar el enlace.');
            return $this->redirectToRoute('biblioteca_editar', ['id' => $enlace->getId()]);
        endif;
        $enlaces = $contenidoRepository->obtenerTodosPorTipo(2);
        return $this->render('biblioteca/editar.html.twig', [
            'enlace' => $enlace,
            'enlaces' => $enlaces,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{hash}/publicar", name="biblioteca_publicar", methods={"GET"})
     * @ParamConverter("contenido", options={"mapping": {"hash": "hash"}})
     */
    public function publicar(Request $request, Contenido $contenido, ContenidoRepository $contenidoRepository): Response
    {
        $now = new \DateTime();
        if ($contenido->getPublicado()):
            $contenido->setPublicado(null);
        else:
            $contenido->setPublicado($now);
        endif;

        if ($contenidoRepository->guardar($contenido)):

            if ($contenido->getPublicado()):
                $this->addFlash('success', 'OK! Contenido publicado con exito.');
            else:
                $this->addFlash('success', 'OK! Contenido bloquedo con exito.');
            endif;

            return $this->redirectToRoute('biblioteca_bandeja');
        endif;

        $this->addFlash('danger', 'Error! No se puedo publicar/bloquear el contenmido.');
        return $this->redirectToRoute('biblioteca_bandeja');
    }

    /**
     * @Route("/eliminar/{hash}", name="biblioteca_contenido_eliminar", methods={"DELETE"})
     * @ParamConverter("contenido", options={"mapping": {"hash": "hash"}})
     */
    public function delete(Request $request, Contenido $enlace, ContenidoRepository $contenidoRepository): Response
    {
        if ($this->isCsrfTokenValid('eliminar_contenido' . $enlace->getId(), $request->request->get('_token'))) :
            if ($contenidoRepository->eliminar($enlace)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! El contenido ha sido eliminado con exito.');
                return $this->redirectToRoute('biblioteca_bandeja');
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se pudo eliminar el contenido.');
            return $this->redirectToRoute('enlace_eliminar');
        endif;
        $this->get('session')->getFlashBag()->add('warning', 'Cuidado! no se pudo validad la accion, por lo que no se eliminar el contenido.');
        return $this->redirectToRoute('biblioteca_bandeja');
    }
}
