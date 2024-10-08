<?php

namespace App\Form;

use App\Entity\Orders;
use App\Entity\OrdersDetails;
use App\Entity\Products;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdersDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', NumberType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin',
                    // Pas de valeur négative
                    'min' => 0,
                ],
                'label' => 'Sous-Total',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('price', NumberType::class, [
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
            ->add('orders', EntityType::class, [
                'class' => Orders::class,
                'choice_label' => 'id',
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Commandes',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
            ->add('products', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Nom du Produit',
                'label_attr' => [
                    'class' => 'form__label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrdersDetails::class,
        ]);
    }
}
