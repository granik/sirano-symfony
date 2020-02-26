<?php

namespace App\Backend\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class DirectionType extends AbstractType
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
                'iconFile',
                ImageType::class,
                [
                    'label'    => 'Пиктограмма',
                    'required' => false,
                ]
            )
            ->add(
                'activeIconFile',
                ImageType::class,
                [
                    'label'    => 'Пиктограмма (активная)',
                    'required' => false,
                ]
            )
            ->add(
                'imageFile',
                ImageType::class,
                [
                    'label'       => 'Изображение',
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                ]
            )
            ->add(
                'isMainPage',
                CheckboxType::class,
                [
                    'label'    => 'Показывать в блоке направлений на главной',
                    'required' => false,
                ]
            )
            ->add(
                'number',
                IntegerType::class,
                [
                    'constraints' => [
                        new GreaterThanOrEqual([
                            'groups' => ['update'],
                            'value'  => 1
                        ])],
                    'label'       => 'Порядковый номер',
                    'required'    => false,
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
            ->add(
                'categories',
                CollectionType::class, [
                    'label'         => false,
                    'entry_type'    => DirectionCategoryType::class,
                    'entry_options' => ['label' => false, 'validation_groups' => ['update']],
                    'allow_add'     => true,
                    'allow_delete'  => true,
                ]
            )
            ->add('Сохранить', SubmitType::class);
    }
}