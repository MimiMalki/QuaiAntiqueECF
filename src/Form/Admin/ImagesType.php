<?php

namespace App\Form\Admin;

use App\Entity\Images;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ImagesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('fileName')
            ->add('file',FileType::class)
            ->add('updatedAt')
            ->add('plat', EntityType::class, [
                'class' => 'App\Entity\Plat',
                'choice_label' => 'title', // ou le nom de l'attribut qui contient le nom du fichier dans l'entité Images
                'placeholder' => 'Sélectionnez un plat',
                'required' => false, // Permet de ne pas rendre le champ obligatoire
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Images::class,
        ]);
    }
}
