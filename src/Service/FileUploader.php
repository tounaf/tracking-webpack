<?php
namespace App\Service;
use http\Env\Response;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = $file->getClientOriginalName();
        $ext = pathinfo($fileName, PATHINFO_EXTENSION);
        $fileName = sha1(uniqid()).'.'.$ext;
        try{
            $file->move($this->getTargetDirectory(), $fileName);
            return $fileName;
        }
        catch (FileException $e){
            return new Response('Upload failed !');
        }

    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }

    public function deleteFile($directoryFile, $fileName)
    {
        if(!empty($directoryFile))
        {
            $fs = new Filesystem();
            return $fs->remove($directoryFile.$fileName, $fileName);
        }
    }

    public function downFilePjIntervenant($filename)
    {
        if($filename)
        {
            $response = new \Symfony\Component\HttpFoundation\Response();
            $response->headers->set('Content-type', 'application/octet-stream');
            $response->headers->set('Content-Disposition', sprintf('attachment; filename="%s"', $filename ));
            $response->setContent(file_get_contents($this->targetDirectory.$filename));
            $response->setStatusCode(200);
            $response->headers->set('Content-Transfer-Encoding', 'binary');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', '0');
            return $response;
        }else{
            return false;
        }

    }
}