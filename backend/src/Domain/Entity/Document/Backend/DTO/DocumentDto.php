<?php


namespace App\Domain\Entity\Document\Backend\DTO;


final class DocumentDto
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var bool
     */
    public $isActive;
    /**
     * @var string
     */
    public $file;
    /**
     * @var \App\Domain\Backend\Interactor\File
     */
    public $fileFile;
}