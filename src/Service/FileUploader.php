<?php
namespace App\Service;


use App\Entity\TipoAdjunto;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file, $pre_name_file = '', $path = null)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        //$safeFilename = transliterator_transliterate('Any-Latin; Latin-ASCII; [^A-Za-z0-9_] remove; Lower()', $originalFilename);
        $fileName = $pre_name_file . uniqid(). '.' . $file->guessExtension();

        $path_file = $this->getTargetDirectory();
        if ($path):
            $path_file .=  DIRECTORY_SEPARATOR . $path;
        endif;

        try {
            $file->move($path_file, $fileName);
        } catch (FileException $e) {
            throw $e;
        }

        //return $path_file . DIRECTORY_SEPARATOR . $fileName;
        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}
