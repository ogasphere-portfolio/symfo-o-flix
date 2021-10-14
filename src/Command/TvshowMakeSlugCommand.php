<?php

namespace App\Command;

use App\Utils\Slugger;
use App\Repository\TvShowRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TvshowMakeSlugCommand extends Command
{
    protected static $defaultName = 'app:tvshow:makeSlug';
    protected static $defaultDescription = 'Create slug from title';


    private $entityManager;
    private $tvShowRepository;
    private $slugger;

    public function __construct(EntityManagerInterface $entityManager, TvShowRepository $tvShowRepository, Slugger $slugger)
    {
        $this->entityManager = $entityManager;
        $this->tvShowRepository = $tvShowRepository;
        $this->slugger = $slugger;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            /* ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description') */
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        // $arg1 = $input->getArgument('arg1');

        // if ($arg1) {
        //     $io->note(sprintf('You passed an argument: %s', $arg1));
        // }

        // if ($input->getOption('option1')) {
        //     // ...
        // }

        $allTvShow = $this->tvShowRepository->findAll();
        foreach ($allTvShow as $tvShow) {
            $tvshowTitle = $tvShow->getTitle();
            $titleSlug = $this->slugger->makeSlug($tvshowTitle);
            $tvShow->setSlug($titleSlug);
        }
        $this->entityManager->flush();

        $io->success('Tvshows have been slugged ! ');

        return Command::SUCCESS;
    }
}
