<?php

namespace App\Form;

use App\Entity\Agence;
use App\Entity\Materiel;
use App\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;

class MaterielType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du matériel',
                'attr' => [
                    'class' => 'form-control mb-3', // Ajout de la classe mb-3
                    'placeholder' => 'Entrez le nom du matériel',
                ],
            ])
            ->add('dateAjout', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'ajout',
                'format' => 'yyyy-MM-dd',
                'attr' => [
                    'class' => 'form-control mb-3', // Ajout de la classe mb-3
                    'placeholder' => 'Sélectionnez une date',
                ],
            ])
            ->add('serialNumber', TextType::class, [
                'label' => 'Numéro de série',
                'attr' => [
                    'class' => 'form-control mb-3', // Ajout de la classe mb-3
                    'placeholder' => 'Entrez le numéro de série',
                ],
            ])
            ->add('agence', EntityType::class, [
                'class' => Agence::class,
                'choice_label' => 'name',
                'label' => 'Agence',
                'attr' => [
                    'class' => 'form-select mb-3', // Ajout de la classe mb-3
                ],
            ])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'name',
                'label' => 'Catégorie',
                'attr' => [
                    'class' => 'form-select mb-3', // Ajout de la classe mb-3
                ],
            ])
            ->add('prix', MoneyType::class, [
                'currency' => 'MGA',
                'divisor' => 1,
                'label' => 'Prix',
                'attr' => [
                    'class' => 'form-control mb-3', // Ajout de la classe mb-3
                    'placeholder' => 'Entrez le prix',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Materiel::class,
        ]);
    }
}

?>
