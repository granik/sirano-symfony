<?php


namespace App\Backend\Form;


use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ConferenceSeriesType extends WithDirectionType
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
            ->add('direction', ChoiceType::class, [
                'choices'     => $this->getDirectionChoices(),
                'constraints' => new NotBlank(),
                'label'       => 'Направление',
            ])
            ->add('Сохранить', SubmitType::class);
    }
}