<?php

namespace App\Form;

use App\Entity\CRMateriel;
use App\Entity\Materiel;
use App\Entity\StockMateriel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormError;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\EntityManagerInterface;

class CRMaterielType extends AbstractType
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('materiel', EntityType::class, [
                'class' => Materiel::class,
                'choice_label' => function (Materiel $materiel) {
                    return $materiel->getName();
                },
                'label' => 'Matériel',
                'attr' => [
                    'class' => 'form-control mb-3'
                ]
            ])
            ->add('quantite', IntegerType::class, [
                'label' => 'Quantité',
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La quantité ne peut pas être vide.'
                    ]),
                    new Assert\GreaterThan([
                        'value' => 0,
                        'message' => 'La quantité doit être supérieure à zéro.'
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                    'placeholder' => 'Entrez la quantité'
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $form->getData();

                if ($data) {
                    $materiel = $data->getMateriel();
                    $quantiteDemandee = $data->getQuantite();

                    if ($materiel && $quantiteDemandee) {
                        $materielName = $materiel->getName();
                        $repository = $this->entityManager->getRepository(StockMateriel::class);
                        $quantiteDisponible = $repository->createQueryBuilder('s')
                            ->select('SUM(s.quantiteDisponible)')
                            ->where('s.name = :name')
                            ->setParameter('name', $materielName)
                            ->getQuery()
                            ->getSingleScalarResult();

                        if ($quantiteDemandee > $quantiteDisponible) {
                            $form->get('quantite')->addError(new FormError(sprintf(
                                'La quantité demandée dépasse le stock disponible pour ce matériel. Quantité disponible: %d, Quantité demandée: %d',
                                $quantiteDisponible,
                                $quantiteDemandee
                            )));
                        }
                    }
                }
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CRMateriel::class,
        ]);
    }
}
