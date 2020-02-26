<?php


namespace App\Backend\Form;


use App\Domain\Entity\Direction\Backend\DTO\DirectionCategoryDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

final class DirectionCategoryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'id',
                HiddenType::class,
                [
                    'label'    => false,
                    'required' => false,
                ]
            )
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
            );
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => DirectionCategoryDto::class,
        ]);
    }
}