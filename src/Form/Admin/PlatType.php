<?php

namespace App\Form\Admin;

use App\Entity\Plat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PlatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('Categoy')
        ;

                // // Titre
                // $builder->add('title', TextType::class, [
                //     'label' => 'Titre*',
                //     'constraints' => [
                //         new NotBlank([
                //             'message' => 'Ce champ ne peut être vide'
                //         ])
                //     ]
                // ]);
                // // description
                // $builder->add('description', TextareaType::class, [
                //     'label' => 'Description du plat'
                // ]);
                // // price
                // $builder->add('price', MoneyType::class, [
                //     'label' => 'Prix'
                // ]);
                // $builder->add('Category');
                // // Bouton Envoyer
                // $builder->add('submit', SubmitType::class, array(
                //     'label' => 'Enregistrer'
                // ));

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Plat::class,
        ]);
    }
}
