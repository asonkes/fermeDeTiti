<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^\D+$/',
                        'message' => 'Votre nom ne peut pas contenir de chiffres.',
                    ])
                ]
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^\D+$/',
                        'message' => 'Votre prénom ne peut pas contenir de chiffres.'
                    ])
                ]
            ])
            ->add('address', TextType::class, [
                'label' => 'Addresse',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre addresse'
                    ])
                ]
            ])
            ->add('zipcode', NumberType::class, [
                'label' => 'Code Postal',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre code postal'
                    ]),
                    new Positive([
                        'message' => 'Le code postal indiqué ne peut être négatif'
                    ]),
                    new Length([
                        'min' => 4,
                        'max' => 4,
                        'minMessage' => 'Le code postal doit contenir exactement {{ limit }} chiffres',
                        'maxMessage' => 'Le code postal doit contenir exactement {{ limit }} chiffres',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{4}$/',
                        'message' => 'Le code postal doit contenir exactement 4 chiffres'
                    ])
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez insérer votre ville"
                    ]),
                    new Length([
                        'max' => 50,
                        'maxMessage' => "Le nom de votre ville ne peut dépasser 50 caractères"
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
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
                ]
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'mapped' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre mot de passe doit faire {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'label' => " J'accepte la politique de confidentialité ",
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'attr' => [
                    'class' => 'form__input--margin'
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions générales',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
