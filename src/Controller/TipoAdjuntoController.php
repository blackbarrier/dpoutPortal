<?php

namespace App\Controller;

use App\Entity\TipoAdjunto;
use App\Form\TipoAdjuntoType;
use App\Repository\TipoAdjuntoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("admin/tipoadjunto")
 */
class TipoAdjuntoController extends AbstractController
{
//    /**
//     * @Route("/", name="tipo_adjunto_index", methods={"GET"})
//     */
//    public function index(TipoAdjuntoRepository $tipoAdjuntoRepository): Response
//    {
//        
//        
//        
//        return $this->render('tipo_adjunto/index.html.twig', [
//            'tipo_adjuntos' => $tipoAdjuntoRepository->findBy(['borrado'=>0],['nombre'=>'ASC']),
//        ]);
//    }
//
//    /**
//     * @Route("/new", name="tipo_adjunto_new", methods={"GET","POST"})
//     */
//    public function new(Request $request): Response
//    {
//        $tipoAdjunto = new TipoAdjunto();
//        $form = $this->createForm(TipoAdjuntoType::class, $tipoAdjunto);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()) {
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($tipoAdjunto);
//            $entityManager->flush();
//            $this->get('session')->getFlashBag()->add('success', 'OK - Tipo de adjunto creado ');
//            return $this->redirectToRoute('tipo_adjunto_index');
//        }
//
//        return $this->render('tipo_adjunto/new.html.twig', [
//            'tipo_adjunto' => $tipoAdjunto,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}", name="tipo_adjunto_show", methods={"GET"})
//     */
//    public function show(TipoAdjunto $tipoAdjunto): Response
//    {
//        $this->redirectToRoute('app_login');
//        /*
//        return $this->render('tipo_adjunto/show.html.twig', [
//            'tipo_adjunto' => $tipoAdjunto,
//        ]);
//        */
//    }
//
//    /**
//     * @Route("/{id}/edit", name="tipo_adjunto_edit", methods={"GET","POST"})
//     */
//    public function edit(Request $request, TipoAdjunto $tipoAdjunto): Response
//    {
//        //$this->get('session')->getFlashBag()->clear();
//        $form = $this->createForm(TipoAdjuntoType::class, $tipoAdjunto);
//        $form->handleRequest($request);
//        
//       
//        if ($form->isSubmitted() && $form->isValid())
//        {                
//            
//            $tipoAdjunto->setNombre($tipoAdjunto->getNombre());
//            $this->getDoctrine()->getManager()->flush();
//            $this->get('session')->getFlashBag()->add('success', ('OK - Tipo de adjunto editado'));
//            return $this->redirectToRoute('tipo_adjunto_index');
//        }
//
//        return $this->render('tipo_adjunto/edit.html.twig', [
//            'tipo_adjunto' => $tipoAdjunto,
//            'form' => $form->createView(),
//        ]);
//    }
//
//    /**
//     * @Route("/{id}/eliminar", name="tipo_adjunto_delete", methods={"DELETE"})
//     */
//    public function delete(Request $request, TipoAdjunto $tipoAdjunto): Response
//    {
//        //$this->get('session')->getFlashBag()->clear();
//        if ($this->isCsrfTokenValid('delete'.$tipoAdjunto->getId(), $request->request->get('_token'))) {
//            $tipoAdjunto->setBorrado(1);
//            $entityManager = $this->getDoctrine()->getManager();
//            $entityManager->persist($tipoAdjunto);
//            $entityManager->flush();
//            $this->get('session')->getFlashBag()->add('success', ('OK - Tipo de adjunto eliminado'));
//        }
//
//        return $this->redirectToRoute('tipo_adjunto_index');
//    }
}
