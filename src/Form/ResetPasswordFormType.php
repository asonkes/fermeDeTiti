<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class ResetPasswordFormType extends AbstractType
{
    // Partie de fabrication du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'form__input form__input--supp form__input--margin'
                ],
                'label' => 'Entrez votre nouveau mot de passse',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre mot de passe',
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
            ]);
    }

    // Partie des options de configuration du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
