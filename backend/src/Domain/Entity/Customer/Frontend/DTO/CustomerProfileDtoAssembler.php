<?php


namespace App\Domain\Entity\Customer\Frontend\DTO;


use App\Domain\Backend\Interactor\AdditionalSpecialtyInteractor;
use App\Domain\Backend\Interactor\MainSpecialtyInteractor;
use App\Domain\Entity\Customer\Customer;
use App\DTO\DtoAssembler;

final class CustomerProfileDtoAssembler extends DtoAssembler
{
    /**
     * @var string
     */
    private $fileUrlPrefix;
    /**
     * @var MainSpecialtyInteractor
     */
    private $mainSpecialtyInteractor;
    /**
     * @var AdditionalSpecialtyInteractor
     */
    private $additionalSpecialtyInteractor;
    
    /**
     * CustomerProfileDtoAssembler constructor.
     *
     * @param string                        $fileUrlPrefix
     * @param MainSpecialtyInteractor       $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
     */
    public function __construct(
        string $fileUrlPrefix,
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
    ) {
        $this->fileUrlPrefix                 = $fileUrlPrefix;
        $this->mainSpecialtyInteractor       = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor = $additionalSpecialtyInteractor;
    }
    
    protected function createDto()
    {
        return new CustomerProfileDto();
    }
    
    /**
     * @param CustomerProfileDto $dto
     * @param Customer           $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->name = $entity->getName() . ' ' . $entity->getLastname();
        
        if (!empty($entity->getAvatar())) {
            $dto->avatar = "{$this->fileUrlPrefix}/{$entity->getAvatar()}";
        }
        
        $dto->mainSpecialty = $this->mainSpecialtyInteractor->getNameById($entity->getMainSpecialtyId());
        
        if ($entity->getAdditionalSpecialtyId() !== null) {
            $dto->additionalSpecialty = "({$this->additionalSpecialtyInteractor->getNameById(
                $entity->getAdditionalSpecialtyId()
            )})";
        }
    }
}