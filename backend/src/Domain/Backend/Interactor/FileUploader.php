<?php

namespace App\Domain\Backend\Interactor;


use App\Domain\Backend\Interactor\Exceptions\NoUploadFile;

final class FileUploader
{
    /**
     * @var string
     */
    private $targetDirectory;
    
    /**
     * FileUploader constructor.
     *
     * @param string $targetDirectory
     */
    public function __construct(string $targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }
    
    /**
     * @param File   $file
     * @param string $name
     * @param string $path
     *
     * @return string
     * @throws NoUploadFile
     */
    public function upload(File $file, string $name, string $path)
    {
        $uploadedFile = $file->getUploadedFile();
        
        if (!$uploadedFile instanceof UploadedFile) {
            throw new NoUploadFile();
        }
        
        $oldFilePath = $file->getFilePath();
        $newFilePath = $uploadedFile->upload($this->targetDirectory, $name, $path);
        
        if (!empty($oldFilePath) && $oldFilePath != $newFilePath) {
            unlink("{$this->targetDirectory}/$oldFilePath");
        }
        
        return $newFilePath;
    }
}