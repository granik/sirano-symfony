<?php

namespace App\DTO;

use App\Domain\Entity\Customer\Customer;
use PHPUnit\Framework\TestCase;

class CustomerDtoAssemblerTest extends TestCase
{
    public function testAssemble()
    {
        $this->markTestIncomplete(
            'Этот тест ещё не реализован.'
        );
        $assembler = new CustomerUpdateDtoAssembler(
//            new UserInteractor()
        );
        $dto       = $assembler->assemble(
            (new Customer())
                ->setLastname('Lastname')
                ->setName('Name')
                ->setMiddlename('Middlename')
                ->setCityName('City')
                ->setEmail('email@example.com')
                ->setPhone('+7(111)111-11-11')
                ->setSpecialty('Specialty')
        );
        
        $this->assertEquals('Name', $dto->name);
        $this->assertEquals('Middlename', $dto->middlename);
        $this->assertEquals('Lastname', $dto->lastname);
        $this->assertEquals('email@example.com', $dto->email);
        $this->assertEquals('+7(111)111-11-11', $dto->phone);
        $this->assertNull($dto->directionId);
        $this->assertNull($dto->avatar);
        $this->assertNull($dto->avatarFile);
        $this->assertEquals('City', $dto->cityName);
        $this->assertEquals('Specialty', $dto->specialty);
    }
}
