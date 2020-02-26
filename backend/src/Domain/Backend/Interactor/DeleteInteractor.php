<?php


namespace App\Domain\Backend\Interactor;


use App\Interactors\NonExistentEntity;

trait DeleteInteractor
{
    /**
     * @param $id
     *
     * @throws NonExistentEntity
     */
    public function delete($id)
    {
        $entity = $this->find($id);
        
        if ($entity === null) {
            throw new NonExistentEntity();
        }
        
        $this->repository->delete($entity);
    }
}