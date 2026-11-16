<?php
namespace App\Controller;

use App\Repository\LivreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CatalogueController extends AbstractController
{
    #[Route('/', name: 'app_catalogue', methods: ['GET'])]
    public function index(
        Request $request,
        LivreRepository $livreRepository
    ): Response {

        // Récupération des filtres dans l'URL
        $genre = $request->query->get('genre');
        $auteur = $request->query->get('auteur');
        $tri = $request->query->get('tri', 'titre_asc');

        // Construction de la requête
        $qb = $livreRepository->createQueryBuilder('l');

        if ($genre) {
            $qb->andWhere('l.genre = :genre')
               ->setParameter('genre', $genre);
        }

        if ($auteur) {
            $qb->andWhere('l.auteur = :auteur')
               ->setParameter('auteur', $auteur);
        }

        // Tri sécurisé : seules ces colonnes sont autorisées
        $tris = [
            'titre_asc' => ['l.titre', 'ASC'],
            'titre_desc' => ['l.titre', 'DESC'],
            'annee_asc' => ['l.annee', 'ASC'],
            'annee_desc' => ['l.annee', 'DESC'],
            'auteur_asc' => ['l.auteur', 'ASC'],
        ];

        if (!isset($tris[$tri])) {
            $tri = 'titre_asc';
        }

        [$champ, $sens] = $tris[$tri];

        $qb->orderBy($champ, $sens);

        // Récupération des livres filtrés
        $livres = $qb->getQuery()->getResult();

        // Calcul des disponibilités
        $catalogue = [];

        foreach ($livres as $livre) {

            $empruntsEnCours = 0;

            foreach ($livre->getEmprunts() as $emprunt) {
                if ($emprunt->getDateRetour() === null) {
                    $empruntsEnCours++;
                }
            }

            $total = $livre->getExemplaires();
            $disponibles = max(0, $total - $empruntsEnCours);

            $catalogue[] = [
                'livre' => $livre,
                'disponibles' => $disponibles,
                'total' => $total,
            ];
        }

        // Genres et auteurs disponibles dans toute la base
        $genres = $livreRepository->createQueryBuilder('l')
            ->select('DISTINCT l.genre')
            ->orderBy('l.genre', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        $auteurs = $livreRepository->createQueryBuilder('l')
            ->select('DISTINCT l.auteur')
            ->orderBy('l.auteur', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        return $this->render('catalogue/index.html.twig', [
            'catalogue' => $catalogue,
            'nombreLivres' => count($catalogue),
            'genres' => $genres,
            'auteurs' => $auteurs,
            'filtres' => [
                'genre' => $genre,
                'auteur' => $auteur,
                'tri' => $tri,
            ],
        ]);
    }
}
