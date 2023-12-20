<?php

namespace App\Controller;

use App\Entity\Honoraire;
use App\Entity\Client;
use App\Form\HonoraireType;
use App\Repository\HonoraireRepository;
use App\Controller\RegistryInterface;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

#[Route('/honoraire')]
class HonoraireController extends AbstractController
{

    #[Route('/', name: 'app_honoraire_index', methods: ['GET'])]
    public function index(Request $request, PaginatorInterface $paginator, HonoraireRepository $honoraireRepository)
    {
        $honoraires = $honoraireRepository->findAll(); // Use findAll to get all clients

        $pagination = $paginator->paginate(
            $honoraires,
            $request->query->getInt('page', 1), // Current page number
            10 // Items per page
        );

        return $this->render('honoraire/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/', name: 'app_honoraire_index2', methods: ['GET'])]
    public function index2(HonoraireRepository $honoraireRepository): Response
    {
        return $this->render('honoraire/index.html.twig', [
            'honoraires' => $honoraireRepository->findAll(),
        ]);
    }

    #[Route('/new/{clientId}', name: 'app_honoraire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, int $clientId): Response

    {

        $client = $entityManager->getRepository(Client::class)->find($clientId);

        if (!$client) {
            throw $this->createNotFoundException('Client not found');
        }

        // Inject the HonoraireRepository and use the countHonorairesByClient method
        $clientHonoraireCount = $entityManager->getRepository(Honoraire::class)->countHonorairesByClient($client);



        // Extract the current year
        $year = (new \DateTime())->format('Y');


        $honoraire = new Honoraire();
        $honoraire->setClient($client);

        $honoraire->setNote(sprintf('N.H%02d/%d', $clientHonoraireCount + 1, $year)); // Increment count

        $form = $this->createForm(HonoraireType::class, $honoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $tva = $this->getParameter('tva') ?? '19';
            $rs = $this->getParameter('rs') ?? '3';
            $tf = $this->getParameter('timbre_fiscal') ?? '1.000';
            $honoraire->setTva($honoraire->getMontantht() * $tva / 100);
            $honoraire->setMontantttc($honoraire->getMontantht() + $honoraire->getTva());
            $honoraire->setRs($honoraire->getMontantttc() * $rs / 100);
            $honoraire->setTimbrefiscal($tf);
            $honoraire->setNetapayer($honoraire->getMontantttc() - $honoraire->getRs() + $honoraire->getTimbrefiscal());

            $entityManager->persist($honoraire);
            $entityManager->flush();

            // Redirect to the client's honoraires index page
            return $this->redirectToRoute('app_client_honoraires_index', ['id' => $clientId]);
        }

        return $this->render('honoraire/new.html.twig', [
            'honoraire' => $honoraire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_honoraire_show', methods: ['GET'])]
    public function show(Honoraire $honoraire): Response
    {
        $tva = $this->getParameter('tva') ?? '19';
        $rs = $this->getParameter('rs') ?? '3';
        $tf = $this->getParameter('timbre_fiscal') ?? '1.000';
        return $this->render('honoraire/show.html.twig', [
            'honoraire' => $honoraire,
            'rs' => $rs,
            'tva' => $tva,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_honoraire_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Honoraire $honoraire, EntityManagerInterface $entityManager): Response
    {
        // Get the client associated with the honoraire
        $client = $honoraire->getClient();


        // Create the edit form
        $form = $this->createForm(HonoraireType::class, $honoraire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tva = $this->getParameter('tva') ?? '19';
            $rs = $this->getParameter('rs') ?? '3';
            $tf = $this->getParameter('timbre_fiscal') ?? '1.000';
            $honoraire->setTva($honoraire->getMontantht() * $tva / 100);
            $honoraire->setMontantttc($honoraire->getMontantht() + $honoraire->getTva());
            $honoraire->setRs($honoraire->getMontantttc() * $rs / 100);
            $honoraire->setTimbrefiscal($tf);
            $honoraire->setNetapayer($honoraire->getMontantttc() - $honoraire->getRs() + $honoraire->getTimbrefiscal());

            $entityManager->flush();

            // Redirect to the client's honoraires index page
            return $this->redirectToRoute('app_client_honoraires_index', ['id' => $client->getId()]);
        }

        return $this->render('honoraire/edit.html.twig', [
            'honoraire' => $honoraire,
            'form' => $form->createView(),
        ]);
    }


    #[Route('/{id}', name: 'app_honoraire_delete', methods: ['POST'])]
    public function delete(Request $request, Honoraire $honoraire, EntityManagerInterface $entityManager): Response
    {
        $clientId = $honoraire->getClient()->getId();
        if ($this->isCsrfTokenValid('delete' . $honoraire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($honoraire);
            $entityManager->flush();
        }

        #return $this->redirectToRoute('app_honoraire_index', [], Response::HTTP_SEE_OTHER);
        return $this->redirectToRoute('app_client_honoraires_index', ['id' => $clientId]);
    }
}
