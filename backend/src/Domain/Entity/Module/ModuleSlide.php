<?php

namespace App\Domain\Entity\Module;


class ModuleSlide
{
    /** @var int */
    private $id;
    
    /** @var Module */
    private $module;
    
    /** @var string Заголовок */
    private $name;
    
    /** @var string|null */
    private $image;
    
    /** @var int Порядковый номер */
    private $number;
    
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
     * @return ModuleSlide
     */
    public function setId(int $id): ModuleSlide
    {
        $this->id = $id;
        return $this;
    }
    
    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }
    
    /**
     * @param Module $module
     *
     * @return ModuleSlide
     */
    public function setModule(Module $module): ModuleSlide
    {
        $this->module = $module;
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
     * @return ModuleSlide
     */
    public function setName(string $name): ModuleSlide
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }
    
    /**
     * @param string $image
     *
     * @return ModuleSlide
     */
    public function setImage(string $image): ModuleSlide
    {
        $this->image = $image;
        return $this;
    }
    
    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }
    
    /**
     * @param int $number
     *
     * @return ModuleSlide
     */
    public function setNumber(int $number): ModuleSlide
    {
        $this->number = $number;
        return $this;
    }
}