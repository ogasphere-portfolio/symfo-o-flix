<?php

namespace App\DataFixtures;

use App\DataFixtures\Provider\OflixProvider;
use App\Entity\Episode;
use App\Entity\Season;
use App\Entity\TvShow;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $entityManager)
    {
        $faker = Factory::create();
        $faker->addProvider(new OflixProvider($faker));

        // créer un  show
        for ($i = 0; $i < 4; $i++) {
            $tvShow = new TvShow();
            $tvShow->setTitle($faker->unique()->tvShowTitle());
            $tvShow->setSynopsys($faker->realText(200));

            $entityManager->persist($tvShow);

            // créer des saisons
            $year = 2010;
            $nbSeason = mt_rand(1, 10);
            for ($seasonNumber = 1; $seasonNumber <= $nbSeason; $seasonNumber++) {
                $season = new Season();
                $seasonYear = $year + $seasonNumber;
                $season->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
                $season->setSeasonNumber($seasonNumber);
                // associer les saisons au show
                $season->setTvShow($tvShow);

                $entityManager->persist($season);

                // pour la saison actuelle, on crée des épisodes
                $nbEpisodes = mt_rand(4, 15);
                for ($episodeNumber = 1; $episodeNumber <= $nbEpisodes; $episodeNumber++) {
                    $episode = new Episode();
                    $episode->setEpisodeNumber($episodeNumber);
                    $episodeTitle = $tvShow->getTitle() . ' - S0' . $seasonNumber . 'x0' . $episodeNumber;
                    if ($episodeNumber >= 10) {
                        $episodeTitle = $tvShow->getTitle() . ' - S0' . $seasonNumber . 'x' . $episodeNumber;
                    }
                    $episode->setTitle($episodeTitle);
                    // associer les épisodes aux saisons
                    $episode->setSeason($season);

                    $entityManager->persist($episode);
                }
            }
        }

        // enregistrer le tout en BDD
        $entityManager->flush();

    }

}


// code a utiliser pour nelmio_alice
/* $loader = new NativeLoader ();
        
//importe le fichier de fixtures et récupère les entités générés
$entities = $loader->loadFile(__DIR__.'/fixtures.yml')->getObjects();

//empile la liste d'objet à enregistrer en BDD
foreach ($entities as $entity) {
    $em->persist($entity);
};

//enregistre
$em->flush(); */