<?php

namespace App\Controller;

use App\Entity\Client;
use App\Entity\Honoraire;
use App\Form\ClientType;
use App\Repository\ClientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\HonoraireRepository; // Import the HonoraireRepository class
use Knp\Component\Pager\PaginatorInterface;



#[Route('/client')]
class ClientController extends AbstractController
{


    #[Route('/', name: 'app_client_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, ClientRepository $clientRepository)
    {
        $clients = $clientRepository->findAll(); // Use findAll to get all clients

        $pagination = $paginator->paginate(
            $clients, // Pass the array of clients
            $request->query->getInt('page', 1), // Current page number
            10 // Items per page
        );

        return $this->render('client/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/', name: 'app_client_index2', methods: ['GET'])]
    public function index2(ClientRepository $clientRepository): Response
    {
        return $this->render('client/index.html.twig', [
            'clients' => $clientRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_client_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $client = new Client();
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($client);
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/new.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_show', methods: ['GET'])]
    public function show(Client $client, PaginatorInterface $paginator, Request $request, HonoraireRepository $honoraireRepository): Response
    {
        // Get all the honoraires associated with the client
        $honoraires = $honoraireRepository->findBy(['client' => $client]);

        // Paginate the honoraires
        $pagination = $paginator->paginate(
            $honoraires, // Pass the array of honoraires
            $request->query->getInt('page', 1), // Current page number
            10 // Items per page
        );

        return $this->render('client/show.html.twig', [
            'client' => $client,
            'pagination' => $pagination, // Pass the pagination to the template
        ]);
    }

    #[Route('/{id}', name: 'app_client_show2', methods: ['GET'])]
    public function show2(Client $client): Response
    {
        return $this->render('client/show.html.twig', [
            'client' => $client,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_client_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Client $client, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ClientType::class, $client);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('client/edit.html.twig', [
            'client' => $client,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_client_delete', methods: ['POST'])]
    public function delete(Request $request, Client $client, EntityManagerInterface $entityManager, HonoraireRepository $honoraireRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $client->getId(), $request->request->get('_token'))) {
            // Fetch all honoraires associated with the client
            $honoraires = $honoraireRepository->findBy(['client' => $client]);

            // Remove each honoraire
            foreach ($honoraires as $honoraire) {
                $entityManager->remove($honoraire);
            }

            // Remove the client
            $entityManager->remove($client);

            // Flush changes to the database
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_client_index', [], Response::HTTP_SEE_OTHER);
    }
}
