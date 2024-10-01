<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastname', TextType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Nom',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
            ])
            ->add('firstname', TextType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Prénom',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Addresse',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
            ])
            ->add('zipcode', NumberType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Code Postal',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Ville',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
            ])
            ->add('RGPDConsent', CheckboxType::class, [
                'attr' => [
                    'class' => 'form__input--margin'
                ],
                'mapped' => false,
                'label' => " J'accepte la politique de confidentialité ",
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions générales',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Mot de passe',
                'label_attr' => [
                    'class' => 'form__label form__label--supp'
                ],
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
