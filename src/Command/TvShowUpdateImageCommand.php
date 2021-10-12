<?php

namespace App\Command;

use App\Repository\TvShowRepository;
use App\Utils\OmdbApi;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TvShowUpdateImageCommand extends Command
{
    protected static $defaultName = 'app:tvshow:update-image';
    protected static $defaultDescription = 'Updates image url using omdb api';

    private $omdbApi;
    private $tvShowRepository;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager, OmdbApi $omdbApi, TvShowRepository $tvShowRepository)
    {
        // dans le code du constructeur de la classe parente
        // du code d'initialisation est exécuté
        // pour exécuter ce code, on utilise l'instruction suivante
        parent::__construct();

        $this->omdbApi = $omdbApi;
        $this->tvShowRepository = $tvShowRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('timesincelastupdate', InputArgument::OPTIONAL, 'time since last update (hours)', '1')
            ->addOption('tvshowid', 't', InputOption::VALUE_OPTIONAL, 'id of the tv show')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $timeSinceLastUpdate = $input->getArgument('timesincelastupdate');

        if ($timeSinceLastUpdate) {
            $io->note(sprintf('You passed an argument: %s', $timeSinceLastUpdate));
        }

        // si un id est fournit on récupère uniquement un seul élément
        if ($input->getOption('tvshowid')) {
            $tvShowId = $input->getOption('tvshowid');
            $tvShow =  $this->tvShowRepository->find($tvShowId);

            // que l'on range dans un tableau pour que la boucle qui suit puisse fonctionner
            $tvShows = [$tvShow];
        }
        else 
        {
            $tvShows = $this->tvShowRepository->findListSinceLastUpdate($timeSinceLastUpdate);
        }

        $moviesUpdated = 0;
        foreach ($tvShows as $currentTvShow) {
            // on ne fait pas de new car 
            // $omdbApi = new OmdbApi($logger);
            $imageUrlFromApi = $this->omdbApi->getImageUrlFromApi($currentTvShow->getTitle());

            if ($imageUrlFromApi !== OmdbApi::IMAGE_NOT_FOUND) {
                $currentTvShow->setImage($imageUrlFromApi);
                $moviesUpdated++;
            }
        }

        $this->entityManager->flush();

        $io->success('[' . $moviesUpdated . '] images found' );

        return Command::SUCCESS;
    }
}
