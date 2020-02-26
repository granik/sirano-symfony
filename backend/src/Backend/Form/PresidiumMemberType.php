<?php


namespace App\Backend\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class PresidiumMemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'lastname',
                TextType::class,
                [
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label'       => 'Фамилия',
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label'       => 'Имя',
                ]
            )
            ->add(
                'middlename',
                TextType::class,
                [
                    'required' => false,
                    'label'    => 'Отчество',
                ]
            )
            ->add(
                'imageFile',
                ImageType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Фотография',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label'       => 'Описание',
                ]
            )
            ->add(
                'number',
                IntegerType::class,
                [
                    'constraints' => [
                        new NotBlank(['groups' => ['update']]),
                        new GreaterThanOrEqual([
                            'groups' => ['update'],
                            'value'  => 1
                        ])],
                    'label'       => 'Порядковый номер',
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