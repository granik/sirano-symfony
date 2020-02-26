<?php

namespace App\Domain\Entity\Customer\Frontend\DTO;

use App\Domain\Entity\Customer\Customer;
use PHPUnit\Framework\TestCase;

class CustomerDtoAssemblerTest extends TestCase
{
    public function testAssemble()
    {
        $assembler = new CustomerDtoAssembler();
        $dto       = $assembler->assemble(
            (new Customer())
                ->setLastname('Lastname')
                ->setName('Name')
                ->setCityName('City')
        );
        
        $this->assertEquals('Lastname Name', $dto->name);
        $this->assertEquals('City', $dto->cityName);
        
        $dto = $assembler->assemble(
            (new Customer())
                ->setLastname('Lastname')
                ->setName('Name')
                ->setMiddlename('Middlename')
                ->setCityName('City')
        );
        
        $this->assertEquals('Lastname Name Middlename', $dto->name);
        $this->assertEquals('City', $dto->cityName);
    }
}
