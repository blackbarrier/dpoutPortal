<?php

namespace App\Controller;

use App\Entity\Adjunto;
use App\Entity\Contenido;
use App\Entity\TipoAdjunto;
use App\Form\BibliotecaType;
use App\Repository\AdjuntoRepository;
use App\Repository\TagRepository;
use App\Repository\OrganismoRepository;
use App\Repository\ContenidoRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Repository\TipoAdjuntoRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/inicio", name="default")
     */
    public function inicio(Request $request, TagRepository $tagRepository, ContenidoRepository $contenidoRepository): Response
    {
        $enlaces = array();
        $tags_seleccionados = "";
        if ($request->isMethod('post')) {
            $parametros = $request->request->all();

            $tags_seleccionados = implode(" ", $parametros['temas']);

            $enlaces = $contenidoRepository->obtenerEnlaces($tags_seleccionados, 1);
            $inicio = false;
        } else {
            $inicio = true;
        
            $enlaces = $contenidoRepository->obtenerEnlacesConLimite(1, 10);
        }
        
        return $this->render('default/inicio.html.twig', [
            'tags' => $tagRepository->findAll(),
            'enlaces' => $enlaces,
            'tags_seleccionados' => $tags_seleccionados,
            'inicio' => $inicio
        ]);
    }



    /**
     * @Route("/observatorio", name="observatorio")
     */
    public function observatorio(Request $request, TagRepository $tagRepository, ContenidoRepository $contenidoRepository): Response
    {
        $enlaces = array();


        $enlaces = $contenidoRepository->obtenerEnlacesConLimite(1, 10);
        return $this->render('default/observatorio.html.twig', [

            'enlaces' => $enlaces
        ]);
    }



    /**
     * @Route("/botonera", name="botonera")
     */
    public function botonera(OrganismoRepository $organismoRepository): Response
    {
        $organismos = array();

        $organismos = $organismoRepository->findAll();
        return $this->render('default/botones_organismos.html.twig', [

            'organismos' => $organismos
        ]);
    }


    /**
     * @Route("/biblioteca/inicio", name="biblioteca_inicio")
     */
    public function biblioteca(Request $request, FileUploader $fileUploader, ContenidoRepository $contenidoRepository, AdjuntoRepository $adjuntoRepository, TipoAdjuntoRepository $tipoAdjuntoRepository): Response
    {
        $contenido = new Contenido();

        $form = $this->createForm(BibliotecaType::class, $contenido);
        $form->handleRequest($request);

        $contenidos = $contenidoRepository->obtenerTodosParaBiblioteca();

        if ($form->isSubmitted() && $form->isValid()):

            $now = new \DateTime();
            $archivo_nombre = '';

            $archivo = $form['adjuntos']->getData();
            if($archivo):
                $tipo_adjunto = $tipoAdjuntoRepository->find(TipoAdjunto::GENERICO);
                $path = "{$now->format('Ymd')}";
                try {

                    $extension = $archivo->getMimeType();

                    $archivo_nombre = $fileUploader->upload($archivo, $tipo_adjunto->getId() . "_", $path);

                    $adjunto = new Adjunto();
                    $adjunto->setNombre($archivo_nombre);
                    $adjunto->setPath('/'.$path);
                    $adjunto->setExtension($extension);
                    $adjunto->setTipoAdjunto($tipo_adjunto);
                    $adjunto->setContenido($contenido);
                    $contenido->addAdjunto($adjunto);

                } catch (\Exception $e) {
                    dd($e);
                    $this->addFlash('danger', "ERROR!, {$e->getMessage()}");
                    return $this->redirectToRoute('biblioteca_inicio');
                }
            endif;

            //dd($form['tags']->getData(), $form->getData());

            $fomr_tags = $form['tags']->getData();
            $tags_array = [];
            foreach ($fomr_tags as $tag):
                $tags_array[] = $tag->getNombre();
            endforeach;

            if (count($tags_array)):
                $contenido->setTags(implode(',', $tags_array));
            endif;

            if ($contenidoRepository->guardar($contenido)):
                $this->addFlash('success', 'CONTENIDO CREADO!');
                return $this->redirectToRoute('biblioteca_inicio');
            endif;

            $this->addFlash('danger', 'ERROR AL INTENTAR CREAR CONTENIDO!');
            return $this->redirectToRoute('biblioteca_inicio');

        endif;

        return $this->render('default/biblioteca.html.twig', [
            'form' => $form->createView(),
            'enlaces' => $contenidos
        ]);
    }
    /**
     * @Route("/biblioteca/agregar", name="biblioteca_agregar")
     */
    public function agregarBiblioteca(Request $request, ContenidoRepository $contenidoRepository): Response
    {
        $contenido = new Contenido();
        $form = $this->createForm(BibliotecaType::class, $contenido);
        $form->handleRequest($request);

        $enlaces = $contenidoRepository->obtenerTodosPorTipo(2);


        return $this->render('default/biblioteca.html.twig', [
            'form' => $form->createView(),

        ]);
    }



    /**
     * @Route("/{hash}", name="contenido_visualizar", methods={"GET"})
    * @ParamConverter("contenido", options={"mapping": {"hash": "hash"}})
     */
    public function visualizar_contenido(Request $request, Contenido $contenido, ContenidoRepository $contenidoRepository): Response
    {

        return $this->render('default/visualizar_contenido.html.twig', [
            'contenido' => $contenido,

        ]);
    }
}
