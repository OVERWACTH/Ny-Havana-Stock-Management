<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\NotificationRepository;

class NotificationController extends AbstractController
{
    #[Route('/notifications', name: 'app_notifications_index', methods: ['GET'])]
    public function notifications(NotificationRepository $notificationRepository): Response
    {
        $notifications = $notificationRepository->findBy(['user' => $this->getUser()]);
    
        return $this->render('notification/notification.html.twig', [
            'notifications' => $notifications,
        ]);
    }
    
}
