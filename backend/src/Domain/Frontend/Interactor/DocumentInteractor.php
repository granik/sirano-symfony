<?php


namespace App\Domain\Frontend\Interactor;


use App\Domain\Entity\Document\Frontend\DocumentRepositoryInterface;

final class DocumentInteractor
{
    /**
     * @var DocumentRepositoryInterface
     */
    private $repository;
    
    /**
     * DocumentInteractor constructor.
     *
     * @param DocumentRepositoryInterface $repository
     */
    public function __construct(DocumentRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }
    
    public function list(int $page, int $perPage)
    {
        return $this->repository->list($page, $perPage);
    }
}