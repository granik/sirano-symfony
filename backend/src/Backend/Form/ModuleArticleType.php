<?php


namespace App\Backend\Form;


use App\Domain\Backend\Interactor\ArticleInteractor;
use App\Domain\Entity\Article\Article;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ModuleArticleType extends AbstractType
{
    /**
     * @var ArticleInteractor
     */
    private $articleInteractor;
    
    /**
     * ModuleArticleType constructor.
     *
     * @param ArticleInteractor $articleInteractor
     */
    public function __construct(ArticleInteractor $articleInteractor)
    {
        $this->articleInteractor = $articleInteractor;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'article',
                ChoiceType::class, [
                    'choices'     => $this->getChoices(),
                    'constraints' => new NotBlank(),
                    'label'       => 'Статья',
                ]
            );
    }
    
    private function getChoices()
    {
        $choices = [];
    
        /** @var Article $article */
        foreach ($this->articleInteractor->listAll() as $article) {
            $choices[$article->getName()] = $article->getId();
        }
    
        return $choices;
    }
}