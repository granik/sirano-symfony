<?php

namespace App\Frontend\Form;


use App\Domain\Entity\Direction\Direction;
use App\Domain\Entity\Specialty\AdditionalSpecialty;
use App\Domain\Entity\Specialty\MainSpecialty;
use App\Domain\Frontend\Interactor\AdditionalSpecialtyInteractor;
use App\Domain\Frontend\Interactor\DirectionInteractor;
use App\Domain\Frontend\Interactor\MainSpecialtyInteractor;
use App\Domain\Interactor\UserInteractor;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class CustomerType extends AbstractType
{
    /**
     * @var UserInteractor
     */
    private $userInteractor;
    /**
     * @var DirectionInteractor
     */
    private $directionInteractor;
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
     * @param UserInteractor                $userInteractor
     * @param DirectionInteractor           $directionInteractor
     * @param MainSpecialtyInteractor       $mainSpecialtyInteractor
     * @param AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
     */
    public function __construct(
        UserInteractor $userInteractor,
        DirectionInteractor $directionInteractor,
        MainSpecialtyInteractor $mainSpecialtyInteractor,
        AdditionalSpecialtyInteractor $additionalSpecialtyInteractor
    ) {
        $this->userInteractor                = $userInteractor;
        $this->directionInteractor           = $directionInteractor;
        $this->mainSpecialtyInteractor       = $mainSpecialtyInteractor;
        $this->additionalSpecialtyInteractor = $additionalSpecialtyInteractor;
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, ['constraints' => new NotBlank()])
            ->add('middlename', TextType::class, ['required' => false])
            ->add('lastname', TextType::class, ['constraints' => new NotBlank()])
            ->add('phone', TextType::class, ['constraints' => new NotBlank()])
            ->add('email', EmailType::class, ['constraints' => [new NotBlank(), new Callback([$this, 'validateEmail'])]])
            ->add('password', RepeatedType::class, [
                'type'            => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'required'        => true,
            ])
            ->add(
                'directionId',
                ChoiceType::class, [
                    'choices'  => $this->getDirectionChoices(),
                    'required' => false,
                ]
            )
            ->add('kladrId', HiddenType::class, ['required' => false])
            ->add('country', HiddenType::class, ['constraints' => new NotBlank()])
            ->add('cityName', HiddenType::class, ['constraints' => new NotBlank()])
            ->add('fullCityName', HiddenType::class, ['constraints' => new NotBlank()])
            ->add(
                'mainSpecialtyId',
                ChoiceType::class, [
                'choices'     => $this->getMainSpecialtyChoices(),
                'constraints' => new NotBlank(),
                'placeholder' => true,
            ])
            ->add(
                'additionalSpecialtyId',
                ChoiceType::class, [
                'choices'  => $this->getAdditionalSpecialtyChoices(),
                'required' => false,
            ])
            ->add('agreeTerms', CheckboxType::class, ['mapped' => false]);
    }
    
    public function validateEmail($value, ExecutionContextInterface $context)
    {
        $form = $context->getRoot();
        $data = $form->getData();
        
        if ($this->userInteractor->checkIfUserExists($data->email)) {
            $context
                ->buildViolation('Такой email уже есть')
                ->atPath('email')
                ->addViolation();
        }
    }
    
    private function getDirectionChoices()
    {
        $choices = [];
        
        /** @var Direction $direction */
        foreach ($this->directionInteractor->activeList() as $direction) {
            $choices[$direction->getName()] = $direction->getId();
        }
        
        return $choices;
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