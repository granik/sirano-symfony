<?php


namespace App\Interactors;


use App\Domain\Interactor\SettingsInterface;
use App\Entity\SettingsItem;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

final class Settings implements SettingsInterface
{
    /** @var EntityManagerInterface */
    private $entityManager;
    
    /** @var ObjectRepository */
    private $objectRepository;
    
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager    = $entityManager;
        $this->objectRepository = $this->entityManager->getRepository(SettingsItem::class);
    }
    
    public function getMaxTries(): int
    {
        return (int)$this->getValue('max_tries');
    }
    
    public function getHoursToConfirm(): int
    {
        return (int)$this->getValue('hours_to_confirm');
    }
    
    public function setMaxTries($value)
    {
        $this->setValue('max_tries', $value);
    }
    
    public function setHoursToConfirm($value)
    {
        $this->setValue('hours_to_confirm', $value);
    }
    
    /**
     * @param string $key
     *
     * @return string
     */
    private function getValue(string $key): string
    {
        $settingsItem = $this->objectRepository->find($key);
        
        if ($settingsItem instanceof SettingsItem) {
            return $settingsItem->getValue();
        }
        
        return '';
    }
    
    private function setValue(string $key, $value)
    {
        $settingsItem = $this->objectRepository->find($key);
    
        if ($settingsItem instanceof SettingsItem) {
            $settingsItem->setValue($value);
        } else {
            $settingsItem = (new SettingsItem())->setKey($key)->setValue($value);
    
            $this->entityManager->persist($settingsItem);
        }
        
        $this->entityManager->flush();
    }
}