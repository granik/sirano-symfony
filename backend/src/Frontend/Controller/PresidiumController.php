<?php


namespace App\Frontend\Controller;


use App\Domain\Entity\PresidiumMember\Frontend\DTO\PresidiumMemberDtoAssembler;
use App\Domain\Frontend\Interactor\PresidiumMemberInteractor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

final class PresidiumController extends AbstractController
{
    const PER_PAGE = 12;
    /**
     * @var PresidiumMemberInteractor
     */
    private $interactor;
    /**
     * @var PresidiumMemberDtoAssembler
     */
    private $dtoAssembler;

    /**
     * PresidiumMemberController constructor.
     *
     * @param PresidiumMemberInteractor   $interactor
     * @param PresidiumMemberDtoAssembler $dtoAssembler
     */
    public function __construct(PresidiumMemberInteractor $interactor, PresidiumMemberDtoAssembler $dtoAssembler)
    {
        $this->interactor   = $interactor;
        $this->dtoAssembler = $dtoAssembler;
    }

    public function index(Request $request)
    {
        $page  = $request->query->get('page', 1);
        $limit = $request->query->get('limit', self::PER_PAGE);

        $list     = $this->interactor->list($page, $limit);
        $entities = $this->dtoAssembler->assembleList($list);

        $pages = ceil($list->count() / $limit);

        return $this->render(
            'frontend/presidium/list.html.twig',
            [
                'list'  => $entities,
                'page'  => $page,
                'pages' => $pages,
                'limit' => $limit,
            ]
        );
    }
}