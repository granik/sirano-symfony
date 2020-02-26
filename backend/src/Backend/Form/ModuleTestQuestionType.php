<?php

namespace App\Backend\Form;


use App\Domain\Entity\Module\Backend\DTO\ModuleTestQuestionDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ModuleTestQuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'question',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Текст вопроса',
                ]
            )
            ->add(
                'answer1',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Ответ А',
                ]
            )
            ->add(
                'answer2',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Ответ B',
                ]
            )
            ->add(
                'answer3',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Ответ C',
                ]
            )
            ->add(
                'answer4',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Ответ D',
                ]
            )
            ->add(
                'rightAnswer',
                ChoiceType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Правильный ответ',
                    'choices'  => [
                        'Ответ A' => 1,
                        'Ответ B' => 2,
                        'Ответ C' => 3,
                        'Ответ D' => 4,
                    ],
                ]
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModuleTestQuestionDto::class,
        ]);
    }
}