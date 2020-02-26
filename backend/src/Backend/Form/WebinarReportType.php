<?php

namespace App\Backend\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WebinarReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'subtitle',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Подзаголовок',
                ]
            )
            ->add(
                'description',
                TextareaType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Описание',
                    'attr'        => ['class' => 'trumbowyg'],
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
                    'required' => false,
                    'label'    => 'Изображение вебинара',
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
            ->add('Сохранить', SubmitType::class);
    }
}