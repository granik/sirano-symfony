<?php

namespace App\Backend\Form;


use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Entity\Specialty\AdditionalSpecialty;
use App\Domain\Entity\Specialty\MainSpecialty;
use App\Domain\Frontend\Interactor\AdditionalSpecialtyInteractor;
use App\Domain\Frontend\Interactor\MainSpecialtyInteractor;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CustomerType extends WithDirectionType
{
    /**
     * @var MainSpecialtyInteractor
     */
    private $mainSpecialtyInteractor;
    /**
     * @var AdditionalSpecialtyInteractor
     */
    private $additionalSpecialtyInteractor;
    
    /**
     * CustomerType constructor.
     *
     * @param DirectionInteractor           $directionInteractor
     * @param MainSpecialtyInteractor       $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
     */
    public function __construct(
        DirectionInteractor $directionInteractor,
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
    ) {
        parent::__construct($directionInteractor);
        $this->mainSpecialtyInteractor       = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor = $additionalSpecialtyInteractor;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'lastname',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Фамилия',
                ]
            )
            ->add(
                'name',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
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
                'phone',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Телефон',
                ]
            )
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'E-mail',
                ]
            )
            ->add(
                'cityName',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Населенный пункт',
                ]
            )
            ->add('kladrId', HiddenType::class, ['required' => false])
            ->add('country', HiddenType::class, ['constraints' => new NotBlank()])
            ->add('fullCityName', HiddenType::class, ['constraints' => new NotBlank()])
            ->add(
                'foreignCity',
                CheckboxType::class, [
                    'mapped'   => false,
                    'label'    => 'Иностранное государство',
                    'required' => false,
                ]
            )
            ->add(
                'directionId',
                ChoiceType::class, [
                    'choices'  => $this->getDirectionChoices(),
                    'required' => false,
                    'label'    => 'Направление',
                ]
            )
            ->add(
                'mainSpecialtyId',
                ChoiceType::class, [
                'choices'     => $this->getMainSpecialtyChoices(),
                'constraints' => new NotBlank(),
                'label'       => 'Специальность',
            ])
            ->add(
                'additionalSpecialtyId',
                ChoiceType::class, [
                'choices'  => $this->getAdditionalSpecialtyChoices(),
                'required' => false,
                'label'    => 'Ученая степень и титул',
            ])
            ->add(
                'sendingCounter',
                IntegerType::class,
                [
                    'required' => false,
                    'label'    => '№ уведомления об активации профиля',
                ]
            )
            ->add(
                'admin',
                CheckboxType::class,
                [
                    'required' => false,
                    'label'    => 'Администратор сайта',
                ]
            )
            ->add('Сохранить', SubmitType::class);
    }
    
    private function getMainSpecialtyChoices()
    {
        $choices = [];
        
        /** @var MainSpecialty $specialty */
        foreach ($this->mainSpecialtyInteractor->list() as $specialty) {
            $choices[$specialty->getName()] = $specialty->getId();
        }
        
        return $choices;
    }
    
    private function getAdditionalSpecialtyChoices()
    {
        $choices = [];
        
        /** @var AdditionalSpecialty $specialty */
        foreach ($this->additionalSpecialtyInteractor->list() as $specialty) {
            $choices[$specialty->getName()] = $specialty->getId();
        }
        
        return $choices;
    }
}