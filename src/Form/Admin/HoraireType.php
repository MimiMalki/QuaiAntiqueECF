<?php

namespace App\Form\Admin;

use App\Entity\Horaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
class HoraireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('day')
            ->add('timeStartM')
            ->add('timeEndM')
            ->add('timeStartN')
            ->add('timeEndN')
            ->add('closeM')
            ->add('closeN')
            // ->add('closeM', CheckboxType::class, [
            //     'label' => 'Fermé le matin ?',
            // ])
            // ->add('closeN', CheckboxType::class,[
            //     'label' => 'Fermé le soir ?',
            // ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horaire::class,
        ]);
    }
}
