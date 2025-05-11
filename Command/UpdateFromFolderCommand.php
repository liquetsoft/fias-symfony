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
 * Команда, которая запускает обновление ФИАС из xml файлов,
 * сохраненных на локальном диске.
 *
 * @internal
 */
final class UpdateFromFolderCommand extends Command
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
            ->setName('liquetsoft:fias:update_from_folder')
            ->setDescription('Updates version of FIAS from the provided folder.')
            ->addArgument('folder', InputArgument::REQUIRED, 'Path to the folder on local file system with FIAS xmls')
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

        $io->note("Updating FIAS from the '{$folder}' folder");

        $state = new ArrayState(
            [
                StateParameter::PATH_TO_SOURCE->value => $folder,
            ]
        );
        $this->pipeline->run($state);

        $io->success("FIAS updated from the '{$folder}' folder");

        return 0;
    }
}
