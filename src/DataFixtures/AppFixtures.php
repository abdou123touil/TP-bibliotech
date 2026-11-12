<?php

namespace App\DataFixtures;
use App\Entity\Livre;
use App\Entity\Adherent;
use App\Entity\Emprunt;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');



        $livres = [
            [
                'titre' => '1984',
                'auteur' => 'George Orwell',
                'genre' => 'Science-fiction',
                'anneeParution' => 1949,
                'nbExemplaire' => 3,
            ],
            [
                'titre' => 'Dune',
                'auteur' => 'Frank Herbert',
                'genre' => 'Science-fiction',
                'anneeParution' => 1965,
                'nbExemplaire' => 2,
            ],
            [
                'titre' => 'Fondation',
                'auteur' => 'Isaac Asimov',
                'genre' => 'Science-fiction',
                'anneeParution' => 1951,
                'nbExemplaire' => 2,
            ],
            [
                'titre' => 'Germinal',
                'auteur' => 'Émile Zola',
                'genre' => 'Roman',
                'anneeParution' => 1885,
                'nbExemplaire' => 3,
            ],
            [
                'titre' => "L'Étranger",
                'auteur' => 'Albert Camus',
                'genre' => 'Roman',
                'anneeParution' => 1942,
                'nbExemplaire' => 2,
            ],
            [
                'titre' => 'Le Chien des Baskerville',
                'auteur' => 'Arthur Conan Doyle',
                'genre' => 'Policier',
                'anneeParution' => 1902,
                'nbExemplaire' => 2,
            ],
            [
                'titre' => 'Le Comte de Monte-Cristo',
                'auteur' => 'Alexandre Dumas',
                'genre' => 'Aventure',
                'anneeParution' => 1844,
                'nbExemplaire' => 2,
            ],
            [
                'titre' => "Le Crime de l'Orient-Express",
                'auteur' => 'Agatha Christie',
                'genre' => 'Policier',
                'anneeParution' => 1934,
                'nbExemplaire' => 3,
            ],
            [
                'titre' => 'Le Tour du monde en 80 jours',
                'auteur' => 'Jules Verne',
                'genre' => 'Aventure',
                'anneeParution' => 1872,
                'nbExemplaire' => 4,
            ],
            [
                'titre' => 'Les Misérables',
                'auteur' => 'Victor Hugo',
                'genre' => 'Roman',
                'anneeParution' => 1862,
                'nbExemplaire' => 2,
            ],
            [
                'titre' => 'Madame Bovary',
                'auteur' => 'Gustave Flaubert',
                'genre' => 'Roman',
                'anneeParution' => 1857,
                'nbExemplaire' => 1,
            ],
            [
                'titre' => 'Vingt mille lieues sous les mers',
                'auteur' => 'Jules Verne',
                'genre' => 'Aventure',
                'anneeParution' => 1870,
                'nbExemplaire' => 3,
            ],
        ];

     

        $livresObjets = [];

        foreach ($livres as $data) {

            $livre = new Livre();

            $titre = $data['titre'];

            $livre->setTitre($titre);
            $livre->setAuteur($data['auteur']);
            $livre->setGenre($data['genre']);
            $livre->setAnnee($data['anneeParution']);
            $livre->setExemplaires($data['nbExemplaire']);

  
            $livre->setResume($faker->paragraph());

        
            $livre->setCouverture(
                'https://placehold.co/400x600?text=' . urlencode($titre)
            );

            $manager->persist($livre);

           
            $livresObjets[] = $livre;
        }

      

        $adherents = [];

        for ($i = 0; $i < 20; $i++) {

            $adherent = new Adherent();

            $adherent->setNom($faker->name());
            $adherent->setVille($faker->city());
            $adherent->setEmail(
                $faker->unique()->safeEmail()
            );

            $manager->persist($adherent);

            $adherents[] = $adherent;
        }

   

        for ($i = 0; $i < 60; $i++) {

            $emprunt = new Emprunt();

            $adherent = $faker->randomElement($adherents);

            $livre = $faker->randomElement($livresObjets);

            $emprunt->setAdherent($adherent);

            $dateEmprunt = \DateTimeImmutable::createFromMutable(
                $faker->dateTimeBetween('-6 months', 'now')
            );

            $emprunt->setDateEmp($dateEmprunt);

            $empruntsEnCours = 0;

            foreach ($livre->getEmprunts() as $ancienEmprunt) {
                if ($ancienEmprunt->getDateRetour() === null) {
                    $empruntsEnCours++;
                }
            }

   
            $rendu = $faker->boolean(70);

            if (!$rendu && $empruntsEnCours < $livre->getExemplaires()) {

              
                $emprunt->setDateRetour(null);

            } else {

     
                $jours = $faker->numberBetween(7, 30);

                $dateRetour = $dateEmprunt->modify(
                    '+' . $jours . ' days'
                );

                $emprunt->setDateRetour($dateRetour);
            }

   
            $livre->addEmprunt($emprunt);

            $manager->persist($emprunt);
        }

    

        $manager->flush();
    }
}