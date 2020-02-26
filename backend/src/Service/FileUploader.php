<?php

namespace App\Service;


use App\Interactors\FileUploaderInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class FileUploader implements FileUploaderInterface
{
    private $targetDirectory;

    public function __construct($targetDirectory)
    {
        $this->targetDirectory = $targetDirectory;
    }

    /**
     * @param UploadedFile $file
     * @param string $fileName
     * @param string $directory
     * @return string
     */
    public function upload($file, string $fileName, string $directory = ''): string
    {
        $extension = $file->guessExtension();

        if (empty($extension)) {
            $extension = $file->getClientOriginalExtension();
        }

        $fileName = $fileName . '.' . $extension;
        $targetDirectory = $this->getTargetDirectory();

        if (!empty($directory)) {
            $targetDirectory .= "/$directory";
        }

        try {
            $file->move($targetDirectory, $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
            throw $e;
        }

        if (!empty($directory)) {
            $fileName = "$directory/$fileName";
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }
}