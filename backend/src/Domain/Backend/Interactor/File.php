<?php

namespace App\Domain\Backend\Interactor;


final class File
{
    /** @var string|null */
    private $filePath;
    
    /** @var UploadedFile|null */
    private $uploadedFile;
    
    /**
     * @param string|null $filePath
     *
     * @return File
     */
    public function setFilePath(?string $filePath): File
    {
        $this->filePath = $filePath;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getFilePath(): ?string
    {
        return $this->filePath;
    }
    
    /**
     * @param UploadedFile|null $uploadedFile
     *
     * @return File
     */
    public function setUploadedFile(UploadedFile $uploadedFile): File
    {
        $this->uploadedFile = $uploadedFile;
        return $this;
    }
    
    /**
     * @return UploadedFile|null
     */
    public function getUploadedFile(): ?UploadedFile
    {
        return $this->uploadedFile;
    }
}