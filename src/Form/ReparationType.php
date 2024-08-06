<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Materiel;
use App\Entity\Reparation;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class ReparationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateAcqui', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'acquisition',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez la date d\'acquisition',
                ],
                'format' => 'yyyy-MM-dd',
            ])
            ->add('dateDebut', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de début',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez la date de début',
                ],
                'format' => 'yyyy-MM-dd',
            ])
            ->add('probleme', TextareaType::class, [
                'label' => 'Problème',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Décrivez le problème',
                    'rows' => 3,
                ],
            ])
            ->add('dateFin', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de fin',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez la date de fin',
                ],
                'format' => 'yyyy-MM-dd',
            ])
            ->add('responsable', TextType::class, [
                'label' => 'Responsable',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Entrez le nom du responsable',
                ],
            ])
            ->add('dateLivraison', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de livraison',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Sélectionnez la date de livraison',
                ],
                'format' => 'yyyy-MM-dd',
            ])
            ->add('observation', TextareaType::class, [
                'label' => 'Observation',
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => 'Ajoutez des observations',
                    'rows' => 3,
                ],
            ])
            ->add('direction', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Direction',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'choice_label' => 'name',
                'label' => 'Matériel',
                'attr' => [
                    'class' => 'form-select',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reparation::class,
        ]);
    }
}
