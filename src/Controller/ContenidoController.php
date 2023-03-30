<?php
namespace App\Controller;

use App\Entity\Contenido;
use App\Form\EnlaceType;
use App\Entity\TipoContenido;
use App\Repository\TipoContenidoRepository;
use App\Repository\ContenidoRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

/**
 * @Route("/admin/contenido")
 */
class ContenidoController extends AbstractController
{

    /**
     * @Route("/", name="enlace", methods={"GET"})
     */
    public function index(ContenidoRepository $contenidoRepository): Response
    {

        return $this->render('enlace/index.html.twig', [
            'enlaces' => $contenidoRepository->findBy(['borrado'=>0],['id'=>'DESC']),
        ]);
    }

    /**
     * @Route("/biblioteca", name="enlace_biblioteca", methods={"GET"})
     */
    public function biblioteca_index(ContenidoRepository $contenidoRepository): Response
    {
        $enlaces = $contenidoRepository->obtenerTodosPorTipo(2);
        return $this->render('enlace/biblioteca_index.html.twig', [
            'enlaces' => $enlaces,
        ]);
    }

    /**
     * @Route("/nuevo", name="enlace_nuevo", methods={"GET","POST"})
     */
    public function new(Request $request,ContenidoRepository $contenidoRepository,TipoContenidoRepository $tipoContenidoRepository): Response
    {
        $enlace = new Contenido();
        $form = $this->createForm(EnlaceType::class, $enlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $enlace->setActivo(1);
            $enlace->setBorrado(0);
            $now = new \DateTime();
            $usuario=$this->getUser();
            $hash = hash('sha256', $usuario->getId() . $now->format('YmdHis'));
            $enlace->setHash($hash);

            $enlace->setTipo($tipoContenidoRepository->findOneBy(['id'=> TipoContenido::TIPO_CONTENIDO]));
            


            if ($contenidoRepository->guardar($enlace)) :
                $this->get('session')->getFlashBag()->add('success', 'OK - Enlace creado con exito.');
                return $this->redirectToRoute('enlace');
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'ERROR - No se pudo crear el enlace.');
            return $this->redirectToRoute('enlace_nuevo');
        }

        return $this->render('enlace/nuevo.html.twig', [
            'enlace' => $enlace,
            'enlaces' => $contenidoRepository->obtenerTodos(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{hash}/editar", name="enlace_editar", methods={"GET","POST"})
     * @ParamConverter("contenido", options={"mapping": {"hash": "hash"}})
     */
    public function edit(Request $request, Contenido $enlace, ContenidoRepository $contenidoRepository): Response
    {
        $form = $this->createForm(EnlaceType::class, $enlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) :
           
            if ($contenidoRepository->guardar($enlace)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! Enlace actualizado con exito.');
                return $this->redirectToRoute('enlace_editar', ['id' => $enlace->getId()]);
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se puedo actualizar el enlace.');
            return $this->redirectToRoute('enlace_editar', ['id' => $enlace->getId()]);
        endif;

        return $this->render('enlace/editar.html.twig', [
            'enlace' => $enlace,
            'enlaces' => $contenidoRepository->obtenerTodos(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/eliminar/{hash}", name="enlace_eliminar", methods={"DELETE"})
     * @ParamConverter("contenido", options={"mapping": {"hash": "hash"}})
     */
    public function delete(Request $request, Contenido $enlace = null, ContenidoRepository $contenidoRepository): Response
    {

        if(!$enlace){return $this->redirectToRoute('app_login');}


        if ($this->isCsrfTokenValid('eliminar_contenido' . $enlace->getId(), $request->request->get('_token'))) :
            if ($contenidoRepository->eliminar($enlace)) :
                $this->get('session')->getFlashBag()->add('success', 'OK! El contenido ha sido eliminado con exito.');
                return $this->redirectToRoute('enlace');
            endif;

            $this->get('session')->getFlashBag()->add('danger', 'Error! No se pudo eliminar el contenido.');
            return $this->redirectToRoute('enlace_eliminar');
        endif;
        $this->get('session')->getFlashBag()->add('warning', 'Cuidado! no se pudo validad la accion, por lo que no se eliminar el contenido.');
        return $this->redirectToRoute('enlace');
    }
}
