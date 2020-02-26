<?php

namespace App\Domain\Backend\Interactor;


final class UploadedFile
{
    private $name;
    private $type;
    private $size;
    private $tmpName;
    private $error;
    
    /**
     * UploadedFile constructor.
     *
     * @param $name
     * @param $type
     * @param $size
     * @param $tmpName
     * @param $error
     */
    public function __construct($name, $type, $size, $tmpName, $error)
    {
        $this->name    = $name;
        $this->type    = $type;
        $this->size    = $size;
        $this->tmpName = $tmpName;
        $this->error   = $error;
    }
    
    /**
     * @param string $targetDirectory
     * @param string $fileName
     * @param string $directory
     *
     * @return mixed|string
     */
    public function upload(string $targetDirectory, string $fileName, string $directory = '')
    {
        if (empty($fileName)) {
            $fileName = $this->getFileName();
        }
        
        $extension = $this->getExtension();
        $fileName  = "$fileName.$extension";
        
        if (!empty($directory)) {
            $targetDirectory .= "/$directory";
        }
        
        if (!is_readable($targetDirectory)) {
            if (!mkdir($targetDirectory, 0777, true) && !is_dir($targetDirectory)) {
                throw new \RuntimeException(sprintf('Directory "%s" was not created', $targetDirectory));
            }
        }
        
        $result = move_uploaded_file($this->tmpName, "$targetDirectory/$fileName");

        if (!empty($directory)) {
            $fileName = "$directory/$fileName";
        }
        
        return $fileName;
    }
    
    private function getExtension()
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }
    
    private function getFileName()
    {
        return pathinfo($this->name, PATHINFO_FILENAME);
    }
}