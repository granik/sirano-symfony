<?php


namespace App\Backend\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => new NotBlank([
                        'groups' => ['update'],
                    ]),
                    'label' => 'Название',
                ]
            )
            ->add(
                'fileFile',
                FileType::class,
                [
                    'constraints' => new NotBlank(),
                    'label' => 'Документ',
                ]
            )
            ->add(
                'isActive',
                CheckboxType::class,
                [
                    'label' => 'Активно',
                    'required' => false,
                ]
            )
            ->add('Сохранить', SubmitType::class);
    }
}