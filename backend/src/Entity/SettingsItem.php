<?php


namespace App\Entity;


class SettingsItem
{
    /**
     * @var string
     */
    private $key;
    
    /**
     * @var string|null
     */
    private $value;
    
    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }
    
    /**
     * @param string $key
     *
     * @return SettingsItem
     */
    public function setKey(string $key): SettingsItem
    {
        $this->key = $key;
        return $this;
    }
    
    /**
     * @return string|null
     */
    public function getValue(): ?string
    {
        return $this->value;
    }
    
    /**
     * @param string|null $value
     *
     * @return SettingsItem
     */
    public function setValue(?string $value): SettingsItem
    {
        $this->value = $value;
        return $this;
    }
}