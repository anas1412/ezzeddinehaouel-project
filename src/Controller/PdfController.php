<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Mpdf\Mpdf;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Honoraire;
use \Numbers_Words;
use Mpdf\Config\Config;


class PdfController extends AbstractController

{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function convertToWords(Request $request): Response
    {
        // Get the float value from the request
        $floatValue = (float) $request->query->get('float_value');

        // Check if the value is a valid float
        if (!is_numeric($floatValue)) {
            return new Response('Invalid input. Please provide a valid float value.', 400);
        }

        // Convert the float to French words
        $converter = new Numbers_Words();
        $frenchWords = $converter->toWords($floatValue, 'fr');

        return $this->render('number_to_words/index.html.twig', [
            'float_value' => $floatValue,
            'french_words' => $frenchWords,
        ]);
    }

    #[Route('/pdf/{honoraireId}', name: 'app_pdf')]
    public function generateHonorairePdf(int $honoraireId): Response
    {



        $tva = $_ENV['TVA'] ?? '19';
        $rs = $_ENV['RS'] ?? '3';
        // Retrieve the Honoraire entity from the database

        $honoraire = $this->entityManager->getRepository(Honoraire::class)->find($honoraireId);

        if (!$honoraire) {
            throw $this->createNotFoundException('Honoraire not found');
        }

        // Get the float value from the request
        $floatValue = $honoraire->getNetapayer();

        // Check if the value is a valid float
        if (!is_numeric($floatValue)) {
            return new Response('Invalid input. Please provide a valid float value.', 400);
        }

        // Convert the float to French words
        $converter = new Numbers_Words();
        $frenchWords = $converter->toWords($floatValue, 'fr');

        // Create an mPDF object
        #$mpdf = new Mpdf();
        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4', // Specify the page format (A4, letter, etc.)
            'margin_top' => 5, // Adjust the top margin in millimeters
            'margin_bottom' => 0, // Adjust the bottom margin in millimeters
            'margin_left' => 1, // Adjust the left margin in millimeters
            'margin_right' => 1, // Adjust the right margin in millimeters
        ]);

        // Generate HTML content for the PDF
        $html = $this->renderView('pdf/honoraire_template.html.twig', [
            'honoraire' => $honoraire,
            'frenchWords' => $frenchWords,
            'rs' => $rs,
            'tva' => $tva
        ]);


        // Write the HTML content to the PDF
        $mpdf->WriteHTML($html);

        // Output the PDF to the browser
        $mpdf->Output();

        // Replace $mfValue with the actual variable containing the 'mf' value.
        $mfValue = $honoraire->getMf();

        // Get the current date in a desired format (e.g., YYYY-MM-DD)
        $currentDate = (new \DateTime())->format('Y-m-d');

        // Concatenate the desired filename with the current date
        $filename = 'honoraire-' . $mfValue . '-' . $currentDate . '.pdf';

        $response = new Response($mpdf->Output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);

        return $response;
    }
}
