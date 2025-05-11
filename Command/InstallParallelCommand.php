<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая запускает полную установку ФИАС в параллельных процессах.
 *
 * @internal
 */
final class InstallParallelCommand extends Command
{
    public function __construct(private readonly Pipe $pipeline)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function configure(): void
    {
        $this
            ->setName('liquetsoft:fias:install')
            ->setDescription('Installs full version of FIAS from scratch in parallel processes')
        ;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Installing full version of FIAS in parallel processes');
        $start = microtime(true);

        $state = new ArrayState();
        $this->pipeline->run($state);

        $total = round(microtime(true) - $start, 4);
        $io->success("Full version of FIAS installed after {$total} s.");

        return 0;
    }
}
