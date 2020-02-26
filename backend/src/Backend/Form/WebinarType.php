<?php

namespace App\Backend\Form;


use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WebinarType extends WithDirectionType
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
                'subject',
                TextType::class,
                [
                    'required' => false,
                    'label'    => 'Тема',
                ]
            )
            ->add('direction', ChoiceType::class, [
                'choices'     => $this->getDirectionChoices(),
                'constraints' => new NotBlank(),
                'label'       => 'Направление',
            ])
            ->add(
                'startDatetime',
                DateTimeType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Дата проведения',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                ]
            )
            ->add(
                'endDatetime',
                DateTimeType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Время окончания',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                ]
            )
            ->add(
                'score',
                IntegerType::class,
                [
                    'constraints' => [new NotBlank(), new GreaterThanOrEqual(0)],
                    'label'       => 'Баллы за онлайн просмотр вебинара',
                ]
            )
            ->add(
                'confirmationTime1',
                TimeType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Время подтверждения просмотра. Окно №1',
                    'widget'      => 'single_text',
                ]
            )
            ->add(
                'confirmationTime2',
                TimeType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Время подтверждения просмотра. Окно №2',
                    'widget'      => 'single_text',
                ]
            )
            ->add(
                'confirmationTime3',
                TimeType::class,
                [
                    'required' => false,
                    'label'    => 'Время подтверждения просмотра. Окно №3',
                    'widget'   => 'single_text',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                    'label'    => 'Описание',
                ]
            )
            ->add(
                'youtubeCode',
                TextareaType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Код видео (youtube)',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Контактный e-mail',
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