<?php


namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints as Assert;

class ContactFormType extends AbstractType
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
                    'placeholder' => 'Entrez votre nom',
                    'class' => 'form__input',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ ne peut pas être vide.',
                    ]),
                    new Assert\Regex([
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
                    'placeholder' => 'Entrez votre prénom',
                    'class' => 'form__input',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ ne peut pas être vide.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/^\D+$/',
                        'message' => 'Votre nom ne peut pas contenir de chiffres.',
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre addresse mail',
                    'class' => 'form__input',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'L\'adresse e-mail ne peut pas être vide.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/@.*\.(com|be|net)$/i',
                        'message' => 'L\'adresse e-mail doit contenir un "@" et se terminer par ".com", ".be" ou ".net".',
                    ]),
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
                    'placeholder' => 'Entrez votre adresse mail',
                    'class' => 'form__input'
                ]
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'label_attr' => [
                    'class' => 'form__label'
                ],
                'attr' => [
                    'placeholder' => 'Entrez votre message',
                    'class' => 'form form__textarea',
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'Le champ ne peut pas être vide.',
                    ]),
                    new Assert\Length([
                        'min' => 8,
                        'minMessage' => 'Le champ doit comporter au moins {{ limit }} caractères.',
                        'max' => 200,
                        'maxMessage' => 'Le champ doit comporter au maximum {{ limit }} caractères.',
                    ])
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
