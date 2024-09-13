<?php

namespace App\Form;

use App\Entity\Categories;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class CategoriesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom de la catégorie'
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
            ->add('categoryOrder', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => "Veuillez insérer l'ordre de la catégorie"
                    ]),
                    new Positive([
                        'message' => "Aucun chiffre négatif n'est accepté"
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'step' => 1,
                    'min' => 0
                ],
                'label' => 'Ordre de la Catégorie',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Categories::class,
        ]);
    }
}
