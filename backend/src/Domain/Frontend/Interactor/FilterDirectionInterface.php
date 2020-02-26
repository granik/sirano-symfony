<?php

namespace App\Domain\Frontend\Interactor;

use App\Domain\Entity\Direction\Direction;

interface FilterDirectionInterface
{
    /**
     * @return bool
     */
    public function isSetDirection(): bool;

    /**
     * @return bool
     */
    public function wasSetDirection(): bool;

    /**
     * @return Direction|null
     */
    public function getSelectedDirection(): ?Direction;

    /**
     * @param Direction $selectedDirection
     *
     * @return $this
     */
    public function save(Direction $selectedDirection);

    public function clear();
}