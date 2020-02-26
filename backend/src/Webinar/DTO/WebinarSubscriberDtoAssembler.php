<?php

namespace App\Webinar\DTO;


use App\DTO\DtoAssembler;
use App\Webinar\WebinarSubscriber;

final class WebinarSubscriberDtoAssembler extends DtoAssembler
{
    protected function createDto()
    {
        return new WebinarSubscriberDto();
    }
    
    /**
     * @param WebinarSubscriberDto $dto
     * @param WebinarSubscriber    $entity
     */
    protected function fill($dto, $entity)
    {
        $dto->customerEmail = $entity->getCustomer()->getEmail();
        $dto->customerName  = $entity->getCustomer()->getLastname() . ' ' . $entity->getCustomer()->getName() . ' ' . $entity->getCustomer()->getMiddlename();
        $dto->confirmNumber = $entity->getConfirmNumber();
    }
}