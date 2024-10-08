<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Orders;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class OrdersFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choice_label' => 'lastname',
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => "Nom de l'Utilisateur",
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('reference', TextType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'readonly' => true,
                ],
                'label' => 'Référence',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('subtotal', NumberType::class, [
                'scale' => 2, // 2 décimales
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'step' => '0.01',  // 2 décimales
                    // Pas de valeur négative
                    'min' => 0,
                ],
                'label' => 'Sous-Total',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('status', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer le nom de la catégorie'
                    ])
                ],
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Statut',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('deliveryFee', NumberType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'readonly' => true,
                ],
                'label' => 'Frais de Livraison',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('total', NumberType::class, [
                'scale' => 2, // 2 décimales
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    'step' => '0.01',  // 2 décimales
                    // Pas de valeur négative
                    'min' => 0,
                ],
                'label' => 'Total',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Orders::class,
        ]);
    }
}
