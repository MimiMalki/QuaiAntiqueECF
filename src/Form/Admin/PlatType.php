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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

use App\Repository\ImagesRepository;
use App\Form\DataTransformer\ImageToEntityTransformer;


class PlatType extends AbstractType
{
    private $imagesRepository;
    private $imageTransformer;

    public function __construct(ImagesRepository $imagesRepository, ImageToEntityTransformer $imageTransformer)
    {
        $this->imagesRepository = $imagesRepository;
        $this->imageTransformer = $imageTransformer;

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $imageNames = $this->imagesRepository->getImageFileNames(); // Obtenez les noms des images depuis le référentiel

        $builder
            ->add('title')
            ->add('description')
            ->add('price')
            ->add('Categoy')
            ->add('image', EntityType::class, [
                'class' => 'App\Entity\Images',
                'choice_label' => 'fileName', // ou le nom de l'attribut qui contient le nom du fichier dans l'entité Images
                'placeholder' => 'Sélectionnez une image',
                'required' => false, // Permet de ne pas rendre le champ obligatoire
            ])
            // ->add('image', ChoiceType::class, [
            //     'choices' => $this->imagesRepository->getImageFileNames(),
            //     'placeholder' => 'Sélectionnez une image',
            //     'required' => false,

            // ])
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
