<?php

namespace App\Service;


use App\Domain\Entity\Direction\Direction;
use App\Domain\Frontend\Interactor\FilterDirectionInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

final class FilterDirection implements FilterDirectionInterface
{
    const FILTER_SELECTED_DIRECTION = 'filter_selected_direction';
    const FILTER_WAS_SELECTED       = 'filter_was_selected';
    
    /**
     * @var SessionInterface
     */
    private $session;
    
    /**
     * FilterDirection constructor.
     *
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }
    
    /**
     * @return bool
     */
    public function isSetDirection(): bool
    {
        session_start();
        
        if (!isset($_SESSION[self::FILTER_SELECTED_DIRECTION])) {
            return false;
        }
        
        return $_SESSION[self::FILTER_SELECTED_DIRECTION] !== null;
    }
    
    /**
     * @return bool
     */
    public function wasSetDirection(): bool
    {
        session_start();
        
        return isset($_SESSION[self::FILTER_WAS_SELECTED]);
    }
    
    /**
     * @return \App\Domain\Entity\Direction\Direction|null
     */
    public function getSelectedDirection(): ?Direction
    {
        if (!$this->session->has(self::FILTER_SELECTED_DIRECTION)) {
            return null;
        }
        
        return $this->session->get(self::FILTER_SELECTED_DIRECTION);
    }
    
    /**
     * @param \App\Domain\Entity\Direction\Direction $selectedDirection
     *
     * @return $this
     */
    public function save(Direction $selectedDirection)
    {
        $this->session->set(self::FILTER_SELECTED_DIRECTION, $selectedDirection);
        $this->session->set(self::FILTER_WAS_SELECTED, true);
        
        return $this;
    }
    
    public function clear()
    {
        $this->session->remove(self::FILTER_SELECTED_DIRECTION);
        $this->session->set(self::FILTER_WAS_SELECTED, true);
        
        return $this;
    }
}