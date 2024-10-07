<?php

namespace App\Form;

use App\Entity\Orders;
use App\Entity\Products;
use App\Entity\OrdersDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class OrdersDetailsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('orders', EntityType::class, [
                'class' => Orders::class,
                'choice_label' => 'id',
            ])
            ->add('products', EntityType::class, [
                'class' => Products::class,
                'choice_label' => 'id',
            ])
            ->add('quantity', NumberType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez insérer votre quantité'
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => OrdersDetails::class,
        ]);
    }
}
