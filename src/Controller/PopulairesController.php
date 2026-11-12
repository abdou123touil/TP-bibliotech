<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PopulairesController extends AbstractController
{
    #[Route('/populaires', name: 'app_populaires', methods: ['GET'])]
    public function index(LivreRepository $livreRepository): Response
    {
        $livres = $livreRepository->findPlusEmpruntes();

        return $this->render('populaires/index.html.twig', [
            'livres' => $livres,
        ]);
    }
}
