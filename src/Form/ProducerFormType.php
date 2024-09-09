<?php

namespace App\Form;

use App\Entity\Producer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProducerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom du producteur'
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
            ->add('society', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer le nom de votre société'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin input__imageJS'
                ],
                'label' => 'Société',
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
            ])
            ->add('description', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez insérer la description de votre société"
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Producer::class,
        ]);
    }
}
