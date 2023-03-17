<?php

namespace App\Form;

use App\Entity\Resevation;
use App\Entity\Allergie;
use App\Repository\UserRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class ResevationType extends AbstractType
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
            // ->add('allergie')
        ;
    }
        // ->add('time', ChoiceType::class, [
        //     'label' => 'un horaire',
        //     'placeholder' => '',
        //     'choices' => $this->getTime(),
        //     'expanded' => false,
        //     'multiple' => false,
        //     // 'attr' => ['class' => ''],
        // ])
    // private function getTime()
    // {
    //     $start = new \DateTime('12:00');
    //     $end = new \DateTime('14:00');
    //     $interval = new \DateInterval('PT15M');
    //     $times = [];
    //     $current = clone $start;
    //     while ($current <= $end) {
    //         $times[$current->format('H:i')] = $current->format('H:i');
    //         $current->add($interval);
    //     }
    //     return $times;
    // }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Resevation::class,
            
        ]);
    }
}
