<?php

namespace App\Domain\Entity\Conference\DTO;


use App\Domain\Entity\Conference\ConferenceSubscriber;
use App\DTO\DtoAssembler;

final class ConferenceSubscriberDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new ConferenceSubscriberDto();
    }
    
    /**
     * @param ConferenceSubscriberDto $dto
     * @param ConferenceSubscriber    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->customerId    = $entity->getCustomer()->getId();
        $dto->customerEmail = $entity->getCustomer()->getEmail();
        $dto->customerName  = $entity->getCustomer()->getLastname() . ' ' . $entity->getCustomer()->getName() . ' ' . $entity->getCustomer()->getMiddlename();
        $dto->visit         = $entity->isVisit();
    }
}