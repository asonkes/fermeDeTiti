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
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom'
                    ]),
                    new Length([
                        'minMessage' => 3,
                        'maxMessage' => 50,
                        'minMessage' => 'Votre nom doit faire au moins {{limit}} caractères',
                        'maxMessage' => 'Votre nom doit faire au maximum {{limit}} caractères'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form__label'
                ],
            ])
            ->add('firstname', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom'
                    ]),
                    new Length([
                        'minMessage' => 3,
                        'maxMessage' => 50,
                        'minMessage' => 'Votre nom doit faire au moins {{limit}} caractères',
                        'maxMessage' => 'Votre nom doit faire au maximum {{limit}} caractères'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form__label'
                ],
            ])
            ->add('address', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre addresse'
                    ]),
                    new Length([
                        'minMessage' => 15,
                        'maxMessage' => 100,
                        'minMessage' => 'Votre nom doit faire au moins {{limit}} caractères',
                        'maxMessage' => 'Votre nom doit faire au maximum {{limit}} caractères'
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9\s\'\-]+$/',
                        'message' => 'Votre adresse ne doit contenir que des lettres, des chiffres, des espaces, des tirets et des apostrophes.',
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Addresse',
                'label_attr' => [
                    'class' => 'form__label'
                ],
            ])
            ->add('zipcode', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre code postal'
                    ]),
                    new Regex([
                        'pattern' => '/^\d{4}$/',
                        'message' => 'Veuillez entrer un code postal valide à 4 chiffres'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Code Postal',
                'label_attr' => [
                    'class' => 'form__label'
                ],
            ])
            ->add('city', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre ville'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form__label'
                ],
            ])
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
            ->add('RGPDConsent', CheckboxType::class, [
                'attr' => [
                    'class' => 'form__input--margin'
                ],
                'mapped' => false,
                'label' => " J'accepte la politique de confidentialité ",
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions générales',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Entrez votre mot de passe',
                    ]),
                    new Length([
                        'min' => 12,
                        'minMessage' => 'Votre mot de passe doit faire minimum {{ limit }} caractères',
                        'max' => 128,
                    ]),
                    new Regex([
                        'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
                        'message' => 'Votre mot de passe doit contenir au moins un caractère spécial.',
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
