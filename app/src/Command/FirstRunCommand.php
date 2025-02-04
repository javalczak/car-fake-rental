<?php

namespace App\Command;

use AllowDynamicProperties;
use App\Service\CommandService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AllowDynamicProperties] #[AsCommand(
    name: 'first:run',
    description: '',
)]
class FirstRunCommand extends Command
{
    public function __construct(CommandService $commandService)
    {
        $this -> commandService = $commandService;
        parent::__construct();
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // first run
        $this -> commandService -> resetAutoIncrement("customer");
        $this -> commandService -> truncate("customer");

        $this -> commandService -> resetAutoIncrement("vehicle");
        $this -> commandService -> truncate("vehicle");

        $this -> commandService -> resetAutoIncrement("city");
        $this -> commandService -> truncate("city");

        $this -> commandService -> resetAutoIncrement("fuel_type");
        $this -> commandService -> truncate("fuel_type");

        $this -> commandService -> resetAutoIncrement("brand");
        $this -> commandService -> truncate("brand");

        $this -> commandService -> resetAutoIncrement("admin");
        $this -> commandService -> truncate("admin");

        $io -> success('Data cleared!');

        $this -> getApplication() -> find('doctrine:fixtures:load') -> run($input, $output);

        return Command::SUCCESS;
    }
}