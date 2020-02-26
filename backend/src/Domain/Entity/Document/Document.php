<?php


namespace App\Domain\Entity\Document;


class Document
{
    /** @var int */
    private $id;
    
    /** @var string Название */
    private $name;
    
    /** @var string|null Документ */
    private $file;
    
    /** @var boolean Опубликован/не опубликован */
    private $isActive;
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @param int $id
     *
     * @return Document
     */
    public function setId(int $id): Document
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     *
     * @return Document
     */
    public function setName(string $name): Document
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getFile(): ?string
    {
        return $this->file;
    }
    
    /**
     * @param string $file
     *
     * @return Document
     */
    public function setFile(string $file): Document
    {
        $this->file = $file;
        return $this;
    }
    
    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->isActive;
    }
    
    /**
     * @param bool $isActive
     *
     * @return Document
     */
    public function setIsActive(bool $isActive): Document
    {
        $this->isActive = $isActive;
        return $this;
    }
}