<?php

namespace App\Domain\Entity\Customer\Frontend\DTO;

use App\Domain\Entity\Customer\Customer;
use PHPUnit\Framework\TestCase;

class CustomerProfileDtoAssemblerTest extends TestCase
{
    public function testAssemble()
    {
        $assembler = new CustomerProfileDtoAssembler('prefix');
        $dto       = $assembler->assemble(
            (new Customer())
                ->setLastname('Lastname')
                ->setName('Name')
                ->setSpecialty('Specialty')
                ->setAvatar('avatar')
        );
        
        $this->assertInstanceOf(CustomerProfileDto::class, $dto);
        $this->assertEquals('Name Lastname', $dto->name);
        $this->assertEquals('prefix/avatar', $dto->avatar);
        $this->assertEquals('Specialty', $dto->specialty);
    }
}
