<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre adresse mail'
                    ]),
                    new Email([
                        'message' => 'Veuillez entrer une adresse email valide, contenant un "@" et un domaine valide'
                    ]),
                    new Length([
                        'max' => 180,
                        'maxMessage' => 'Votre adresse email ne doit pas dépasser {{ limit }} caractères'
                    ]),
                ],
                'attr' => [
                    'placeholder' => 'exemple@email.com',
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Utilisateur' => 'ROLE_USER'
                ],
                'multiple' => true, // Permet la sélection de plusieurs rôles
                //'expanded' => true, // Affiche les rôles sous forme de cases à cocher
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre role'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('lastname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre addresse'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Addresse',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('zipcode', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre code postal'
                    ]),
                    new Positive([
                        'message' => 'Le code postal indiqué ne peut être négatif'
                    ]),
                    new Length([
                        'max' => '4',
                        'maxMessage' => 'Le code postal doit contenir maximum chiffres '
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'min' => 0
                ],
                'label' => 'Code Postal',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez insérer votre ville"
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => "Le nom de votre ville ne peut dépasser 50 caractères"
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'maxlength' => 50
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
