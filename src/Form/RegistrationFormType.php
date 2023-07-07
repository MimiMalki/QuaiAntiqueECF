<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Allergie;
use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints as Assert;
class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('lastname', TextType::class, [
                'attr' => ['placeholder' => 'Entrer votre nom'],
                'label' => 'Nom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le nom ne doit contenir que des lettres',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => ['placeholder' => 'Entrer votre prénom'],
                'label' => 'Prénom',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^[a-zA-Z\s]+$/',
                        'message' => 'Le prénom ne doit contenir que des lettres',
                    ]),
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
            ->add('numbre_of_people', IntegerType::class, [
                'attr' => [
                    'label' => 'Nombre de convives',
                    'placeholder' => 'Entrer le nombre de convives',
                    'min' => 1, 'max' => 20,
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nombre de convives',
                    ])
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs de mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe',
                    ]),
                    new Length(
                        [
                            'min' => 6, 'max' => 255,
                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} caractères',
                        ],
                    ),
                ],
            ])
            ->add('allergies', EntityType::class, [
                'class' => Allergie::class,
                'label' => 'Avez-vous des allergies alimentaires ?',
                'choice_label' => 'name',
                'multiple' => true,
                'mapped' => false,
                'expanded' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez selectionner vos allergies cas où vous n\'avez pas d\'allergies cocher Acune  ',
                    ])
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
