<?php

namespace App\Backend\Form;


use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Entity\Direction\Direction;
use Symfony\Component\Form\AbstractType;

class WithDirectionType extends AbstractType
{
    /**
     * @var DirectionInteractor
     */
    protected $directionInteractor;
    
    /**
     * WithDirectionType constructor.
     *
     * @param DirectionInteractor $directionInteractor
     */
    public function __construct(DirectionInteractor $directionInteractor)
    {
        $this->directionInteractor = $directionInteractor;
    }
    
    protected function getDirectionChoices()
    {
        $choices = [];
        
        /** @var Direction $direction */
        foreach ($this->directionInteractor->activeList() as $direction) {
            $choices[$direction->getName()] = $direction->getId();
        }
        
        return $choices;
    }
}