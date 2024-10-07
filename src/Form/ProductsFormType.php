<?php

namespace App\Form;

use App\Entity\Producer;
use App\Entity\Products;
use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ProductsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
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
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'id',
            ])
            ->add('producer', EntityType::class, [
                'class' => Producer::class,
                'choice_label' => 'id',
            ])
            ->add('image', FileType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre image'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin input__imageJS'
                ],
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'mapped' => false, // Important si vous n'êtes pas en train de lier à une entité
                'required' => true // Si vous voulez que le champ soit obligatoire,
            ])
            ->add('price', MoneyType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre prix'
                    ]),
                    new Positive([
                        'message' => 'Le montant indiqué ne peut être négatif'
                    ])
                ],
                'attr' => [
                    'placeholder' => '0.00',
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Prix',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('stock', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre stock'
                    ]),
                    new Positive([
                        'message' => 'Le montant indiqué ne peut être négatif'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'step' => 1,
                    'min' => 0
                ],
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('alt', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez insérer la description de l'image"
                    ]),
                    new Length([
                        'max' => 125,
                        'maxMessage' => "Votre description de l'image ne peut dépasser 125 caractères"
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'maxlength' => 125
                ],
                'label' => 'Texte Image',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'kilo' => 'kilo',
                    'litre' => 'litre',
                    '300 gr' => '300 gr',
                    '500 gr' => '500 gr',
                    '1 pièce' => '1 pièce',
                    '6 pièces' => '6 pièces',
                    '12 pièces' => '12 pièces',
                    '33 cl' => '33 cl',
                    '50 cl' => '50 cl',
                    '75 cl' => '75 cl',
                    '500 ml' => '500 ml'
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Type',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('categories', EntityType::class, [
                'class' => Categories::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Catégories',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('producer', EntityType::class, [
                'class' => Producer::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Producteur',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Products::class,
        ]);
    }
}
