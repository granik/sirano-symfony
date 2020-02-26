<?php


namespace App\Backend\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

final class UserPasswordUpdateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'newPassword',
                RepeatedType::class,
                [
                    'type'            => PasswordType::class,
                    'invalid_message' => 'Пароли должны совпадать',
                    'required'        => true,
                    'first_options'   => ['label' => 'Новый пароль'],
                    'second_options'  => ['label' => 'Повторите новый пароль'],
                ]
            )
            ->add('Обновить пароль', SubmitType::class);
    }
}