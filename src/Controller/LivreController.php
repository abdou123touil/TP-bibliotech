<?php

namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class LivreController extends AbstractController
{
    #[Route('/livre/{id<\d+>}', name: 'app_livre', methods: ['GET'])]
    public function show(int $id, LivreRepository $livreRepository): Response
    {
        // get() renvoie null si le livre n'existe pas.
        $livre = $livreRepository->find($id);

        if (!$livre) {
            throw $this->createNotFoundException('Livre introuvable.');
        }

        // Compter les emprunts et les emprunts encore en cours.
        $nombreEmprunts = $livre->getEmprunts()->count();
        $empruntsEnCours = 0;

        foreach ($livre->getEmprunts() as $emprunt) {
            if ($emprunt->getDateRetour() === null) {
                $empruntsEnCours++;
            }
        }

        $nombreExemplaires = $livre->getExemplaires();
        $disponibles = max(0, $nombreExemplaires - $empruntsEnCours);

        // Les emprunts les plus récents en premier.
        $derniersEmprunts = $livre->getEmprunts()->toArray();

        usort(
            $derniersEmprunts,
            fn ($a, $b) => $b->getDateEmp() <=> $a->getDateEmp()
        );

        // Autres livres appartenant au même genre.
        $livresMemeGenre = $livreRepository->createQueryBuilder('l')
            ->andWhere('l.genre = :genre')
            ->andWhere('l.id != :id')
            ->setParameter('genre', $livre->getGenre())
            ->setParameter('id', $livre->getId())
            ->orderBy('l.titre', 'ASC')
            ->getQuery()
            ->getResult();

        return $this->render('livre/index.html.twig', [
            'livre' => $livre,
            'nombreEmprunts' => $nombreEmprunts,
            'disponibles' => $disponibles,
            'nombreExemplaires' => $nombreExemplaires,
            'derniersEmprunts' => array_slice($derniersEmprunts, 0, 10),
            'livresMemeGenre' => $livresMemeGenre,
        ]);
    }
}
