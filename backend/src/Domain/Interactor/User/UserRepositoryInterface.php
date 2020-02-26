<?php

namespace App\Domain\Interactor\User;


use App\Domain\Entity\Customer\Customer;
use DateTime;

interface UserRepositoryInterface
{
    public function findByLogin($login);

    public function findByCode($code);

    public function store(User $user): User;

    public function update(User $user): User;
    
    public function findAnyByLogin($login);
    
    public function findByCustomer(Customer $customer): ?User;
    
    public function delete(User $user);
    
    /**
     * @param DateTime $sendDateTime
     * @param int      $maxTries
     * @param int      $limit
     *
     * @return User[]
     */
    public function getNotConfirmedUsers(DateTime $sendDateTime, int $maxTries, int $limit): array;
}