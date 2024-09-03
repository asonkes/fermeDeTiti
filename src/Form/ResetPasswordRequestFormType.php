<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class ResetPasswordRequestFormType extends AbstractType
{
    // Partie de fabrication du formulaire
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'label' => 'Entrez votre e-mail',
                'label_attr' => [
                    'class' => 'form__label'
                ]
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
