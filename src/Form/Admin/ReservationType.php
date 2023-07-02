<?php

namespace App\Form\Admin;

use App\Entity\Reservation;
use App\Entity\Allergie;
use App\Entity\SeuilMaximum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class ReservationType extends AbstractType
{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $maxValue = $this->entityManager->getRepository(SeuilMaximum::class)->createQueryBuilder('sm')
            ->select('MAX(sm.nbrSeatMax) as max_value')
            ->getQuery()
            ->getSingleScalarResult();
        $startHourM = 12;  // Heure de début de matin
        $endHourM = 14;   // Heure de fin de matin

        $startHourN = 19;  // Heure de début de soir
        $endHourN = 22;   // Heure de fin de soir

        $builder
            ->add('lastname', TextType::class, [
                'attr' => ['placeholder' => 'Entrer votre nom'],
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom',
                    ])
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['placeholder' => 'Entrer votre prénom'],
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom',
                    ])
                ],
            ])
            ->add('numbre_of_people', IntegerType::class, [
                'label' => 'Nombre de convives',
                'label_attr' => ['class' => 'heure'],
                'required' => true,
                'attr' => [
                    'min' => 1,
                    'max' => $maxValue,
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ ne peut pas être vide.'
                    ]),
                    new Assert\Positive([
                        'message' => 'Le nombre de convives doit être un nombre positif et supérieur à zéro.'
                    ]),
                    new Assert\LessThanOrEqual([
                        'value' => 50,
                        'message' => 'Le nombre de convives doit être inférieur ou égal à 50.'
                    ]),
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Choisir une date ',
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
            ])
            ->add('time', ChoiceType::class, [
                'label' => 'L’heure prévue',
                'choices' => $this->generateTimeChoices(),
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champ ne peut pas être vide'
                    ]),
                    new Callback([
                        $this, 'validateTime'
                    ]),
                ],
            ])
            // ->add('time', ChoiceType::class, [
            //     'label' => 'L’heure prévue',
            //     'choices' => $this->generateTimeChoices($startHour, $endHour, $startHourN,$endHourN),
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Ce champ ne peut pas être vide'
            //         ]),
            //         new Callback([
            //             $this, 'validateTime'
            //         ]),
            //     ],
            // ])

            // ->add('time', TimeType::class, [
            //     'label' => 'L’heure prévue',
            //     'data' => new \DateTime(),
            //     'widget' => 'choice',
            //     'input' => 'datetime',
            //     'hours' => ['12', '13', '14', '19', '20', '22'],
            //     'minutes' => range(0, 45, 15), // Sélection par tranche de 15 minutes
            //     'constraints' => [
            //         new NotBlank([
            //             'message' => 'Ce champ ne peut pas être vide'
            //         ]),
            //         new Callback([
            //             $this, 'validateTime'
            //         ]),
            //     ],
            // ])
            ->add('allergie', EntityType::class, [
                'class' => Allergie::class,
                'multiple' => true,
                'expanded' => false,
            ]);
    }
    // ->add('time')
    //     ->add('numbre_of_people',IntegerType::class,  [
    //         'label' => 'Nombre de convives '])
    //     ->add('allergie', EntityType::class, [
    //         'class' => Allergie::class,
    //         'multiple' => true,
    //         'expanded' => true,
    //     ])

    //     ->add('date')
    //     ->add('time')
    //     ->add('numbre_of_people')
    //     ->add('allergie')
    //     ->add('user')
    // ;

// Méthode pour générer les options de plage horaire en excluant la plage de 14h à 19h
private function generateTimeChoices(): array
{
    $choices = [];

    for ($hour = 12; $hour <= 22; $hour++) {
        if ($hour >= 14 && $hour < 19) {
            continue;  // Exclure la plage de 14h à 19h
        }

        for ($minute = 0; $minute <= 45; $minute += 15) {
            $time = sprintf('%02d:%02d', $hour, $minute);
            $choices[$time] = $time;
        }
    }

    return $choices;
}

    // private function generateTimeChoices(int $startHourM, int $endHourM, int $startHourN, int $endHourN): array
    // {
    //     $choices = [];

    //     for ($hour = $startHour; $hour <= $endHour; $hour++) {
    //         for ($minute = 0; $minute <= 45; $minute += 15) {
    //             $time = sprintf('%02d:%02d', $hour, $minute);
    //             $choices[$time] = $time;
    //         }
    //     }

    //     return $choices;
    // }
    public function validateTime($value, ExecutionContextInterface $context): void
    {
        $form = $context->getRoot();

        // /** @var Reservation $reservation */
        $reservation = $form->getData();

        if (empty($reservation->getTime())) {
            $context->buildViolation('Veuillez choisir une heure.')
                ->atPath('time')
                ->addViolation();
        }
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
