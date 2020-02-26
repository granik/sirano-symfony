<?php

namespace App\Backend\Form;


use App\Domain\Backend\Interactor\ConferenceSeriesInteractor;
use App\Domain\Backend\Interactor\DirectionInteractor;
use App\Domain\Entity\City;
use App\Domain\Entity\Conference\ConferenceSeries;
use App\Domain\Interactor\CityInteractor;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ConferenceType extends WithDirectionType
{
    /**
     * @var CityInteractor
     */
    private $cityInteractor;
    /**
     * @var ConferenceSeriesInteractor
     */
    private $conferenceSeriesInteractor;
    
    /**
     * ConferenceType constructor.
     *
     * @param DirectionInteractor        $directionInteractor
     * @param CityInteractor             $cityInteractor
     * @param ConferenceSeriesInteractor $conferenceSeriesInteractor
     */
    public function __construct(
        DirectionInteractor $directionInteractor,
        CityInteractor $cityInteractor,
        ConferenceSeriesInteractor $conferenceSeriesInteractor
    ) {
        parent::__construct($directionInteractor);
        
        $this->cityInteractor             = $cityInteractor;
        $this->conferenceSeriesInteractor = $conferenceSeriesInteractor;
    }
    
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
                'direction',
                ChoiceType::class, [
                    'choices'     => $this->getDirectionChoices(),
                    'constraints' => new NotBlank(),
                    'label'       => 'Направление',
                ]
            )
            ->add(
                'series',
                ChoiceType::class, [
                    'choices'  => $this->getSeriesChoices(),
                    'label'    => 'Цикл',
                    'required' => false,
                ]
            )
            ->add(
                'city',
                ChoiceType::class, [
                    'choices'     => $this->getCities(),
                    'constraints' => new NotBlank(),
                    'label'       => 'Город',
                ]
            )
            ->add(
                'address',
                TextType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Адрес',
                ]
            )
            ->add(
                'startDateTime',
                DateTimeType::class,
                [
                    'constraints' => new NotBlank(),
                    'label'       => 'Дата проведения',
                    'date_widget' => 'single_text',
                    'time_widget' => 'single_text',
                ]
            )
            ->add(
                'endDateTime',
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
                    'label'       => 'Баллы',
                    'help'        => 'Баллы за посещение конференции',
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
                'isActive',
                CheckboxType::class,
                [
                    'label'    => 'Активно',
                    'required' => false,
                ]
            )
            ->add(
                'programs',
                CollectionType::class, [
                    'label'         => false,
                    'entry_type'    => ConferenceProgramType::class,
                    'entry_options' => ['label' => false],
                    'allow_add'     => true,
                    'allow_delete'  => true,
                ]
            )
            ->add('Сохранить', SubmitType::class);
    }
    
    private function getCities()
    {
        $choices = [];
        
        /** @var City $city */
        foreach ($this->cityInteractor->listAll() as $city) {
            $choices[$city->getName()] = $city->getId();
        }
        
        return $choices;
    }
    
    private function getSeriesChoices()
    {
        $choices = [];
        
        /** @var ConferenceSeries $conferenceSeries */
        foreach ($this->conferenceSeriesInteractor->listAll() as $conferenceSeries) {
            $choices[$conferenceSeries->getName()] = $conferenceSeries->getId();
        }
        
        return $choices;
    }
}