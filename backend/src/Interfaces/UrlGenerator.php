<?php


namespace App\Interfaces;


use App\Domain\Service\UrlGeneratorInterface;
use App\Webinar\Webinar;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface as SymfonyUrlGeneratorInterface;

final class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @var SymfonyUrlGeneratorInterface
     */
    private $router;
    
    /**
     * UrlGenerator constructor.
     *
     * @param SymfonyUrlGeneratorInterface $router
     */
    public function __construct(SymfonyUrlGeneratorInterface $router)
    {
        $this->router = $router;
    }
    
    public function urlForWebinar(Webinar $webinar)
    {
        return $this->router->generate('webinar', ['id' => $webinar->getId()], SymfonyUrlGeneratorInterface::ABSOLUTE_URL);
    }
}