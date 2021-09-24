<?php

namespace App\Controller;

use App\Entity\Season;
use App\Entity\TvShow;
use DateTimeImmutable;
use App\Entity\Episode;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FakeDataController extends AbstractController
{
     /**
     * @Route("/fake/data/got/", name="fake_data_got")
     */
    public function got(EntityManagerInterface $entityManager): Response
    {

        // créer un  show
        $got = new TvShow();
        $got->setTitle('Game of Thrones');
        $got->setSynopsys('Game of Thrones - il fait froid et plein de gens font pleins de trucs, Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam, debitis atque perferendis neque natus optio minus. Nihil temporibus velit in nam at blanditiis quisquam eligendi nisi aspernatur magni, dolore, reprehenderit ad enim architecto atque fugiat libero, quae eos beatae? Aliquid iure, ullam vitae eaque deleniti quidem quod fugit dignissimos et? Voluptatem voluptates tempore sapiente hic suscipit eveniet excepturi, iure dicta. Maiores animi nostrum iste nulla placeat exercitationem, dolorum quasi magnam magni incidunt cumque voluptate eaque quaerat, dolor consequatur sit voluptas, officiis fuga voluptatem deleniti blanditiis amet? Rem quo, qui sed numquam ex similique aperiam harum cum deleniti commodi officia sit.');
        // les valeurs suivantes sont définies en valeur par défaut
        // $got->setCreatedAt(new DateTimeImmutable());
        // $got->setNbLikes(0);
       

        $entityManager->persist($got);


        // créer des saisons
        $year = 2010;
        for ($seasonNumber = 1; $seasonNumber <= 7; $seasonNumber++) {
            $season = new Season();
            $seasonYear = $year + $seasonNumber;
            $season->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
            $season->setSeasonNumber($seasonNumber);
            // associer les saisons au show
            $season->setTvShow($got);

            $entityManager->persist($season);

            // pour la saison actuelle, on crée des épisodes
            for ($episodeNumber = 1; $episodeNumber <= 9; $episodeNumber++) {

                $episode = new Episode();
                $episode->setEpisodeNumber($episodeNumber);
                $episode->setTitle('S0' . $seasonNumber . 'x0' . $episodeNumber);
                // associer les épisodes aux saisons
                $episode->setSeason($season);

                $entityManager->persist($episode);
            }
        }

        // enregistrer le tout en BDD
        $entityManager->flush();


        return $this->render('fake_data_maker/index.html.twig', [
            'controller_name' => 'FakeDataMakerController',
        ]);
    }

    /**
     * @Route("/fake/data/mrRobot/", name="fake_data_robot")
     */
    public function mrRobot(EntityManagerInterface $entityManager): Response
    {

        // créer un  show
        $tvShow = new TvShow();
        $tvShow->setTitle('Mr Robot');
        $tvShow->setSynopsys('Hacking++ , Lorem ipsum dolor sit amet consectetur, adipisicing elit. Totam, debitis atque perferendis neque natus optio minus. Nihil temporibus velit in nam at blanditiis quisquam eligendi nisi aspernatur magni, dolore, reprehenderit ad enim architecto atque fugiat libero, quae eos beatae? Aliquid iure, ullam vitae eaque deleniti quidem quod fugit dignissimos et? Voluptatem voluptates tempore sapiente hic suscipit eveniet excepturi, iure dicta. Maiores animi nostrum iste nulla placeat exercitationem, dolorum quasi magnam magni incidunt cumque voluptate eaque quaerat, dolor consequatur sit voluptas, officiis fuga voluptatem deleniti blanditiis amet? Rem quo, qui sed numquam ex similique aperiam harum cum deleniti commodi officia sit.');
        // les valeurs suivantes sont définies en valeur par défaut
        // $tvShow->setCreatedAt(new DateTimeImmutable());
        // $tvShow->setNbLikes(0);
       

        $entityManager->persist($tvShow);


        // créer des saisons
        $year = 2010;
        for ($seasonNumber = 1; $seasonNumber <= 4; $seasonNumber++) {
            $season = new Season();
            $seasonYear = $year + $seasonNumber;
            $season->setPublishedAt(new DateTimeImmutable($seasonYear . '-01-01'));
            $season->setSeasonNumber($seasonNumber);
            // associer les saisons au show
            $season->setTvShow($tvShow);

            $entityManager->persist($season);

            // pour la saison actuelle, on crée des épisodes
            for ($episodeNumber = 1; $episodeNumber <= 10; $episodeNumber++) {

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

        // enregistrer le tout en BDD
        $entityManager->flush();


        return $this->render('fake_data_maker/index.html.twig', [
            'controller_name' => 'FakeDataMakerController',
        ]);
    }
}
