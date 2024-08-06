<?php

namespace App\Form;

use App\Entity\CR;
use App\Service\CRNumberGenerator;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CRType extends AbstractType
{
    private CRNumberGenerator $crNumberGenerator;

    public function __construct(CRNumberGenerator $crNumberGenerator)
    {
        $this->crNumberGenerator = $crNumberGenerator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $newCRNumber = $this->crNumberGenerator->generateNewCRNumber();

        $builder
            ->add('numeroCR', TextType::class, [
                'label' => 'Numéro CR',
                'attr' => [
                    'readonly' => true,
                    'class' => 'form-control mb-3'
                ],
                'data' => $newCRNumber,
            ])
            ->add('nomDemandeur', TextType::class, [
                'label' => 'Nom du Demandeur',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez le nom du demandeur',
                ],
            ])
            ->add('dateDemande', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de Demande',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Sélectionnez une date',
                ],
            ])
            ->add('crMateriels', CollectionType::class, [
                'entry_type' => CRMaterielType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'attr' => [
                    'class' => 'form-collection mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CR::class,
        ]);
    }
}
