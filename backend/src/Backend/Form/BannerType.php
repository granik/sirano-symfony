<?php


namespace App\Backend\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class BannerType extends AbstractType
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
                'link',
                TextType::class,
                [
                    'required' => false,
                    'label'    => 'Ссылка',
                ]
            )
            ->add(
                'desktopImageFile',
                ImageType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Изображение для десктопа (190x325)',
                ]
            )
            ->add(
                'mobileImageFile',
                ImageType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Изображение для мобильного (320x96)',
                ]
            )
            ->add(
                'backgroundColor',
                ColorType::class,
                [
                    'label'    => 'Цвет фона',
                    'required' => false,
                ]
            )
            ->add(
                'number',
                IntegerType::class,
                [
                    'constraints' => [new NotBlank(), new GreaterThanOrEqual(1)],
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