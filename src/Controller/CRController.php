<?php
namespace App\Controller;

use App\Entity\CR;
use App\Entity\Notification;
use App\Entity\Stock;
use App\Form\CRType;
use App\Repository\CRRepository;
use App\Repository\NotificationRepository;
use App\Repository\FicheDeStockRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

#[Route('/cr')]
class CRController extends AbstractController
{
    private TokenStorageInterface $tokenStorage;
    private EntityManagerInterface $entityManager;
    private NotificationRepository $notificationRepository;
    private FicheDeStockRepository $stockRepository;
    private UserRepository $userRepository;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $entityManager,
        NotificationRepository $notificationRepository,
        FicheDeStockRepository $stockRepository,
        UserRepository $userRepository
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->entityManager = $entityManager;
        $this->notificationRepository = $notificationRepository;
        $this->stockRepository = $stockRepository;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'app_c_r_index', methods: ['GET'])]
    public function index(CRRepository $cRRepository): Response
    {
        return $this->render('cr/index.html.twig', [
            'c_rs' => $cRRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $cR = new CR();
        $form = $this->createForm(CRType::class, $cR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($cR->getCrMateriels() as $crMateriel) {
                $crMateriel->setCr($cR);
                $this->entityManager->persist($crMateriel);
            }
            $this->entityManager->persist($cR);
            $this->entityManager->flush();

            // Créer une notification pour les DP RH
            $dpRhUsers = $this->userRepository->findBy(['departement' => 'DPRH']);
            foreach ($dpRhUsers as $dpRhUser) {
                $notification = new Notification();
                $notification->setMessage("Nouvelle demande de CR #{$cR->getNumeroCR()} de {$cR->getNomDemandeur()}.");
                $notification->setRead(false);
                $notification->setUtilisateur($dpRhUser);
                $notification->setCreatedAt(new \DateTime());
                $notification->setLink($this->generateUrl('app_c_r_confirm', ['id' => $cR->getId()]));
                $this->entityManager->persist($notification);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('app_c_r_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cr/new.html.twig', [
            'c_r' => $cR,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_show', methods: ['GET'])]
    public function show(CR $cR): Response
    {
        return $this->render('cr/show.html.twig', [
            'c_r' => $cR,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CR $cR): Response
    {
        $form = $this->createForm(CRType::class, $cR);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            foreach ($cR->getCrMateriels() as $crMateriel) {
                $crMateriel->setCr($cR);
                $this->entityManager->persist($crMateriel);
            }
            $this->entityManager->flush();

            return $this->redirectToRoute('app_c_r_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cr/edit.html.twig', [
            'c_r' => $cR,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/delete', name: 'app_c_r_delete', methods: ['POST'])]
    public function delete(Request $request, CR $cR): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cR->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($cR);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('app_c_r_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/{id}/confirm', name: 'app_c_r_confirm', methods: ['GET', 'POST'])]
    public function confirm(Request $request, CR $cR): Response
    {
        $user = $this->tokenStorage->getToken()->getUser();
        // Vérifier que l'utilisateur est un DP RH
        if (!$user || !in_array('ROLE_DPRH', $user->getRoles())) {
            throw new AccessDeniedException('Vous n\'avez pas les droits nécessaires pour confirmer cette demande.');
        }

        if ($request->isMethod('POST')) {
            $cR->setStatus('acceptée');
            $this->entityManager->flush();

            // Notifier l'utilisateur demandeur
            $notification = new Notification();
            $notification->setMessage("Votre demande de CR #{$cR->getNumeroCR()} a été acceptée.");
            $notification->setRead(false);
            $notification->setUtilisateur($cR->getNomDemandeur()); // Assigner l'utilisateur demandeur
            $notification->setCreatedAt(new \DateTime());
            $notification->setLink($this->generateUrl('app_c_r_show', ['id' => $cR->getId()]));
            $this->entityManager->persist($notification);

            // Mettre à jour la fiche de stock
            foreach ($cR->getCrMateriels() as $crMateriel) {
                $stock = $this->stockRepository->findOneBy(['materiel' => $crMateriel->getMateriel()]);
                if ($stock) {
                    $quantiteDisponible = $stock->getQuantiteDisponible();
                    $stock->setQuantiteDisponible($quantiteDisponible - 1);
                    if ($stock->getQuantiteDisponible() <= 0) {
                        $stock->setQuantiteDisponible(10); // Réapprovisionnement
                    }
                    $this->entityManager->persist($stock);
                }
            }

            $this->entityManager->flush();

            return $this->redirectToRoute('app_c_r_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('cr/confirm.html.twig', [
            'c_r' => $cR,
        ]);
    }
}
