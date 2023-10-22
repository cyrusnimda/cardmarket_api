<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Entity\Card;
use Doctrine\ORM\EntityManagerInterface;

#[AsCommand(
    name: 'app:import-cards',
    description: 'Add a short description for your command',
)]
class ImportCardsCommand extends Command
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('cards_file', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $cards_file = $input->getArgument('cards_file');

        if (!$cards_file) {
            $io->error('Please provide a file with cards to import');
            return Command::FAILURE;
        }

        if(!file_exists($cards_file)) {
            $io->error('File does not exist');
            return Command::FAILURE;
        }

        $doc = new \DOMDocument();
        

        $handle = fopen($cards_file, "r");
        if ($handle) {
            while (($line = fgets($handle)) !== false) {
                $doc->loadHTML($line);
                $xpath = new \DOMXPath($doc);
                $image = $xpath->evaluate("string(//img/@src)"); 
                $pos = strpos($image, '.jpg');
                if ($pos !== false) {
                    $image = substr($image, 0, $pos +4);
                }   
                $name = $xpath->evaluate("string(//img/@title)"); 
                $pos = strpos($name, ' (Limited');
                if ($pos !== false) {
                    $name = substr($name, 0, $pos);
                }

                $card = new Card();
                $card->setName($name);
                $card->setEdition("A");
                $card->setImage($image);
                $card->setStock(rand(0, 4));
                $card->setPrice(rand(10, 1000) / 100);

                $this->entityManager->persist($card);
                $this->entityManager->flush();

                $io->text('Card ' . $name . ' imported');
            }

            fclose($handle);
        }


        $io->success('All good.');

        return Command::SUCCESS;
    }
}
