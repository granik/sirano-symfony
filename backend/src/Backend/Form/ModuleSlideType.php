<?php

namespace App\Backend\Form;


use App\Domain\Entity\Module\Backend\DTO\ModuleSlideDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ModuleSlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => new NotBlank(
                        [
                            'groups' => ['update'],
                        ]
                    ),
                    'label'       => 'Название',
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
                        ])
                    ],
                    'label'       => 'Порядковый номер',
                ]
            )
            ->add(
                'imageFile',
                ImageType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Изображение',
                ]
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ModuleSlideDto::class,
        ]);
    }
}