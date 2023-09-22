<?php

namespace App\Form;

use App\Entity\Recruteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecruteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('company', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => "Entreprise",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'mapped' => false
            ])
            ->add('company_adress', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => "Adresse",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'mapped' => false
            ])
            ->add('company_postcode', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => "Code Postal",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'mapped' => false
            ])
            ->add('company_city', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                ],
                'label' => "Ville",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'mapped' => false
            ])
            ->add('submit',  SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Recruteur::class,
        ]);
    }
}
