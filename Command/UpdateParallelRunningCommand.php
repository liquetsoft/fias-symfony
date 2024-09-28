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

/**
 * Команда для параллельных процессов, в которых идет обновление ФИАС.
 */
final class UpdateParallelRunningCommand extends Command
{
    protected static string $defaultName = 'liquetsoft:fias:update_parallel_running';

    public function __construct(private readonly Pipe $pipeline)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Command for running parallel update.')
            ->addArgument('files', InputArgument::OPTIONAL, 'Json encoded list of files data.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $files = $input->getArgument('files');
        if (\is_array($files)) {
            $files = reset($files);
        }

        if (!empty($files)) {
            $files = json_decode((string) $files, true);
        } else {
            $stdIn = file_get_contents('php://stdin');
            $files = !empty($stdIn) ? json_decode($stdIn, true) : [];
        }

        $state = new ArrayState();
        $state->setAndLockParameter(StateParameter::FILES_TO_PROCEED, $files);
        $this->pipeline->run($state);

        return 0;
    }
}
