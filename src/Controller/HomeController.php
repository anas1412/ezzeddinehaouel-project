<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UserRepository; // Replace with your actual UserRepository
use App\Repository\ClientRepository; // Replace with your actual ClientRepository
use App\Repository\HonoraireRepository; // Replace with your actual HonorairesRepository

class HomeController extends AbstractController
{
    private $entityManager;
    private $userRepository;
    private $clientRepository;
    private $honoraireRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository, ClientRepository $clientRepository, HonoraireRepository $honoraireRepository)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->clientRepository = $clientRepository;
        $this->honoraireRepository = $honoraireRepository;
    }

    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        // Calculate the sum of users
        $userCount = $this->userRepository->count([]);

        // Calculate the sum of clients
        $clientCount = $this->clientRepository->count([]);

        // Calculate the sum of honoraires
        $honoraireCount = $this->honoraireRepository->count([]);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'user_count' => $userCount,
            'client_count' => $clientCount,
            'honoraire_count' => $honoraireCount,
        ]);
    }
}
