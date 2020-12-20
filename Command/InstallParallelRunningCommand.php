<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Команда для параллельных процессов, в которых идет установка ФИАС.
 */
class InstallParallelRunningCommand extends Command
{
    protected static $defaultName = 'liquetsoft:fias:install_parallel_running';

    protected Pipe $pipeline;

    public function __construct(Pipe $pipeline)
    {
        $this->pipeline = $pipeline;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Command for running parallel installation.')
            ->addArgument('files_to_insert', InputArgument::REQUIRED, 'Json encoded list of files to insert data.')
            ->addArgument('files_to_delete', InputArgument::REQUIRED, 'Json encoded list of files to delete data.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesToInsert = $input->getArgument('files_to_insert');
        if (is_array($filesToInsert)) {
            $filesToInsert = reset($filesToInsert);
        }
        $filesToInsert = json_decode((string) $filesToInsert, true);

        $filesToDelete = $input->getArgument('files_to_delete');
        if (is_array($filesToDelete)) {
            $filesToDelete = reset($filesToDelete);
        }
        $filesToDelete = json_decode((string) $filesToDelete, true);

        $state = new ArrayState();
        $state->setAndLockParameter(Task::FILES_TO_INSERT_PARAM, $filesToInsert);
        $state->setAndLockParameter(Task::FILES_TO_DELETE_PARAM, $filesToDelete);
        $this->pipeline->run($state);

        return 0;
    }
}
