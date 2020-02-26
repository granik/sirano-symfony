<?php


namespace App\Backend\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class NewsType extends WithDirectionType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Название',
                ]
            )
            ->add(
                'direction',
                ChoiceType::class, [
                    'choices'     => $this->getDirectionChoices(),
                    'constraints' => new NotBlank(),
                    'label'       => 'Направление',
                ]
            )
            ->add(
                'createdAt',
                DateType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Дата публикации',
                    'widget'      => 'single_text',
                ]
            )
            ->add(
                'announceImageFile',
                ImageType::class,
                [
                    'required' => false,
                    'label'    => 'Изображение анонса',
                ]
            )
            ->add(
                'imageFile',
                ImageType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Изображение новости',
                ]
            )
            ->add(
                'text',
                TextareaType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Текст новости',
                    'attr'        => ['class' => 'editor'],
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
    }
}