<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ValidateFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'attr' => [

                    'class' => 'form__input2',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le champ ne peut pas être vide.',
                    ]),
                    new Regex([
                        'pattern' => '/^\D+$/',
                        'message' => 'Votre prénom ne peut pas contenir de chiffres.',
                    ])
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'attr' => [
                    'class' => 'form__input2',
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
            ->add('address', TextType::class, [
                'label' => 'Adresse',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre addresse'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input2'
                ]
            ])
            ->add('zipcode', NumberType::class, [
                'label' => 'Code Postal',
                'label_attr' => [
                    'class' => 'form__label'
                ],
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
                    'class' => 'form__input2',
                    'min' => 0
                ]
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form__label'
                ],
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
                    'class' => 'form__input2'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
