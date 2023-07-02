<?php

namespace App\Form;

use App\Entity\Allergie;
use App\Entity\Reservation;
use App\Entity\User;
use App\Entity\SeuilMaximum;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

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
            ->add('email', EmailType::class, [
                'attr' => ['placeholder' => 'Entrer votre email'],
                'label' => 'Email',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre email',
                    ]),
                    new Email([
                        'message' => 'L\'adresse email "{{ value }}" n\'est pas valide',
                        // message affiché si l'utilisateur saisit une adresse email incorrecte
                    ]),
                ],
            ])
            ->add('date', DateType::class, [
                'label' => 'Choisir une date ',
                'label_attr' => ['class' => 'heure'],
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
                'data' => new \DateTime(),
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
            ->add('allergie', EntityType::class, [
                'class' => Allergie::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('time', ChoiceType::class, [
                'label' => 'Choisir une heure',
                'placeholder' => '',
                // 'choices' => [
                //     'Déjeuner' => $this->getTime('midday'),
                //     'Dîner' => $this->getTime('evening'),
                // ],
                'choices' => array_merge(
                    ['Plages horaires du matin' => null],
                    $this->getTime('midday'),
                    ['Plages horaires du soir' => null],
                    $this->getTime('evening')
                ),
                'group_by' => function ($value, $key, $index) {
                    if (strpos($key, 'Plages horaires') !== false) {
                        return $key;
                    } elseif ($index <= 2) {
                        return 'Plages horaires du matin';
                    } else {
                        return 'Plages horaires du soir';
                    }
                },
                'expanded' => true,
                'multiple' => false,
                'attr' => ['class' => 'btn-group'],
                'label_attr' => ['class' => 'heure'],
                'choice_attr' => ['class' => 'heure btn-outline-secondary'],
                'constraints' => [
                    new Callback([$this, 'validateTime']),
                ],
            ]);
            
    }
    private function getTime(string $timeOfDay): array
    {
        if ($timeOfDay === 'midday') {
            $start = new \DateTime('12:00');
            $end = new \DateTime('14:00');
        } else {
            $start = new \DateTime('19:00');
            $end = new \DateTime('22:00');
        }

        $interval = new \DateInterval('PT15M');
        $times = [];
        $current = clone $start;
        while ($current <= $end) {
            $times[$current->format('H:i')] = $current->format('H:i');
            $current->add($interval);
        }
        return $times;
    }
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
