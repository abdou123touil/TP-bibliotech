<?php

namespace App\Repository;

use App\Entity\Livre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Livre>
 */
class LivreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Livre::class);
    }


    public function findGenre(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT l.genre
            FROM App\Entity\Livre l'
        );

        return array_map(fn($result) => $result['genre'], $query->getResult());
    }
    public function findAuteur(): array
    {
        $entityManager = $this->getEntityManager();

        $query = $entityManager->createQuery(
            'SELECT DISTINCT l.auteur
            FROM App\Entity\Livre l'
        );

        return array_map(fn($result) => $result['auteur'], $query->getResult());
    }
    public function findCatalogue(?string $genre, ?string $auteur, ?string $tri): array
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('l')
            ->from(Livre::class, 'l');

        if ($genre) {
            $queryBuilder->andWhere('l.genre = :genre')
                ->setParameter('genre', $genre);
        }

        if ($auteur) {
            $queryBuilder->andWhere('l.auteur = :auteur')
                ->setParameter('auteur', $auteur);
        }

        if ($tri) {
            switch ($tri) {
                case 'titre_asc':
                    $queryBuilder->orderBy('l.titre', 'ASC');
                    break;
                case 'titre_desc':
                    $queryBuilder->orderBy('l.titre', 'DESC');
                    break;
                case 'annee_asc':
                    $queryBuilder->orderBy('l.annee', 'ASC');
                    break;
                case 'annee_desc':
                    $queryBuilder->orderBy('l.annee', 'DESC');
                    break;
            }
        }

        return $queryBuilder->getQuery()->getResult();
    }
    public function findMemeGenre(Livre $livre, int $nombre = 4): array
    {
        $entityManager = $this->getEntityManager();

        $queryBuilder = $entityManager->createQueryBuilder()
            ->select('l')
            ->from(Livre::class, 'l')
            ->where('l.genre = :genre')
            ->andWhere('l.id != :id')
            ->setParameter('genre', $livre->getGenre())
            ->setParameter('id', $livre->getId())
            ->setMaxResults($nombre);

        return $queryBuilder->getQuery()->getResult();
    }
   
public function findPlusEmpruntes(): array
{
    return $this->createQueryBuilder('l')
        ->leftJoin('l.emprunts', 'e')
        ->groupBy('l.id')
        ->orderBy('COUNT(e.id)', 'DESC')
        ->addOrderBy('l.titre', 'ASC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Livre[] Returns an array of Livre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('l.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Livre
//    {
//        return $this->createQueryBuilder('l')
//            ->andWhere('l.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
