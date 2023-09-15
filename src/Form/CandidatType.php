<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;

class CandidatType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('nom', TextType::class, [
        'label' => "Nom",
        'mapped' => false
      ])
      ->add('prenom', TextType::class, [
        'label' => "Prénom",
        'mapped' => false
      ])
      ->add('cv', FileType::class, [
        'label' => "CV (PDF uniquement)",
        'mapped' => false,
        'constraints' => [
          new File([
            'maxSize' => '1024k',
            'mimeTypes' => [
              'application/pdf',
            ],
            'mimeTypesMessage' => 'Veuillez sélectionner un fichier PDF',
          ])
        ]
      ]);
  }
}
