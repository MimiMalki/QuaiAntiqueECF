<?php

namespace App\Form\Admin;

use App\Entity\Reservation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Positive;

class ReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('date', DateType::class, [
            'label' => 'Choisir une date ',
            'widget' => 'single_text', 
            'format' => 'yyyy-MM-dd',
            'data' => new \DateTime(),
            ])
        
        ->add('time')
            ->add('numbre_of_people',IntegerType::class,  [
                'label' => 'Nombre de convives '])
            ->add('allergie', EntityType::class, [
                'class' => Allergie::class,
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('allergie')
        ;
    }
        //     ->add('date')
        //     ->add('time')
        //     ->add('numbre_of_people')
        //     ->add('allergie')
        //     ->add('user')
        // ;
    

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reservation::class,
        ]);
    }
}
