<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\CR;
use App\Entity\FicheDeStock;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class FicheDeStockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Sélectionnez une date',
                ],
            ])
            ->add('sortie', TextType::class, [
                'label' => 'Quantité Sortie',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez la quantité sortie',
                ],
            ])
            ->add('stock', TextType::class, [
                'label' => 'Quantité en Stock',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez la quantité en stock',
                ],
            ])
            ->add('signatureRH', TextType::class, [
                'label' => ' RH',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez la signature du RH',
                ],
            ])
            ->add('signatureDemandeur', TextType::class, [
                'label' => ' Demandeur',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez la signature du demandeur',
                ],
            ])
            ->add('numeroCR', EntityType::class, [
                'class' => CR::class,
                'choice_label' => 'numeroCR',
                'label' => 'Numéro CR',
                'attr' => [
                    'class' => 'form-select mb-3',
                ],
            ])
            ->add('demandeur', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Demandeur',
                'attr' => [
                    'class' => 'form-select mb-3',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FicheDeStock::class,
        ]);
    }
}
