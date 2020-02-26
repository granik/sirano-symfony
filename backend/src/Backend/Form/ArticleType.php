<?php


namespace App\Backend\Form;


use App\Domain\Entity\Article\Backend\DTO\ArticleDto;
use App\Domain\Entity\Direction\Category;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ArticleType extends WithDirectionType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var ArticleDto|null $articleDto */
        $articleDto = $options['data'] ?? null;
        $direction  = $articleDto ? $articleDto->direction : null;
        
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label'       => 'Название',
                ]
            )
            ->add(
                'direction',
                ChoiceType::class, [
                    'choices'     => $this->getDirectionChoices(),
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label'       => 'Направление',
                ]
            )
            ->add(
                'category',
                ChoiceType::class, [
                    'choices'  => $this->getCategoryChoices($direction),
                    'required' => false,
                    'label'    => 'Категория',
                ]
            )
            ->add(
                'author',
                TextType::class,
                [
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label'       => 'Автор',
                ]
            )
            ->add(
                'fileFile',
                FileType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Материал',
                ]
            )
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'label'    => 'Активно',
                    'required' => false,
                ]
            )
            ->add('Сохранить', SubmitType::class);
        
        $builder->get('direction')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $this->setupCategoryField($form->getParent(), $form->getData());
            }
        );
        
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var ArticleDto|null $data */
                $data = $event->getData();
                
                if (!$data) {
                    return;
                }
                
                $this->setupCategoryField($event->getForm(), $data->direction);
            }
        );
    }
    
    /**
     * @param $direction
     *
     * @return array
     * @throws \App\Interactors\NonExistentEntity
     */
    private function getCategoryChoices($direction)
    {
        $choices = [];
        
        if ($direction === null) {
            return $choices;
        }
        
        /** @var Category $category */
        foreach ($this->directionInteractor->listCategoryByDirection($direction) as $category) {
            $choices[$category->getName()] = $category->getId();
        }
        
        return $choices;
    }
    
    private function setupCategoryField(FormInterface $form, $direction)
    {
        if ($direction === null) {
            $form->remove('category');
            
            return;
        }
        
        $choices = $this->getCategoryChoices($direction);
        
        if (empty($choices)) {
            $form->remove('category');
            
            return;
        }
        
        $form->add(
            'category',
            ChoiceType::class, [
                'choices'  => $choices,
                'required' => false,
                'label'    => 'Категория',
            ]
        );
    }
}