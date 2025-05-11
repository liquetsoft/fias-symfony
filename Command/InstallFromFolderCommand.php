<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\State\StateParameter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая запускает полную установку ФИАС из xml файлов,
 * сохраненных на локальном диске.
 *
 * @internal
 */
final class InstallFromFolderCommand extends Command
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
            ->setName('liquetsoft:fias:install_from_folder')
            ->setDescription('Installs full version of FIAS from folder')
            ->addArgument('folder', InputArgument::REQUIRED, 'Path to folder on local system with FIAS xmls')
        ;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $folder = $input->getArgument('folder');
        if (\is_array($folder)) {
            $folder = reset($folder);
        }
        $folder = (string) $folder;

        $io->note("Installing full version of FIAS from '{$folder}' folder");
        $start = microtime(true);

        $state = new ArrayState(
            [
                StateParameter::PATH_TO_SOURCE->value => $folder,
            ]
        );
        $this->pipeline->run($state);

        $total = round(microtime(true) - $start, 4);
        $io->success("Full version of FIAS installed after {$total} s.");

        return 0;
    }
}
