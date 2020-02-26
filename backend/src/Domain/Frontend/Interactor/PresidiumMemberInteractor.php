<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\PresidiumMember\Frontend\PresidiumMemberRepositoryInterface;

final class PresidiumMemberInteractor
{
    /**
     * @var PresidiumMemberRepositoryInterface
     */
    private $repository;

    /**
     * PresidiumMemberInteractor constructor.
     *
     * @param PresidiumMemberRepositoryInterface $repository
     */
    public function __construct(PresidiumMemberRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
}