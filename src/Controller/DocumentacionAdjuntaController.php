<?php

namespace App\Controller;

use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/adjuntos")
 */
class DocumentacionAdjuntaController extends AbstractController {

    /**
     * @Route("/", name="upload_adjunto", methods={"POST"})
    */
    public function upload(Request $request, FileUploader $fileUploader) {
        $files = $request->files;

        if (!$files->count()):
            throw (new FileNotFoundException());
        endif;

        $file = $request->files->get('file');
        $namefile = $fileUploader->upload($file, 'contenido_', 'contenido');
        $url_base = $this->getParameter('URL_BASE_ADJUNTO');

        return (new Response(json_encode(['location' => $url_base . "/contenido/{$namefile}"])));
    }



}
