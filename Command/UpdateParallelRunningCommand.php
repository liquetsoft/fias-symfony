<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\State;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Команда для параллельных процессов, в которых идет обновление ФИАС.
 *
 * @internal
 */
final class UpdateParallelRunningCommand extends Command
{
    public function __construct(
        private readonly Pipe $pipeline,
        private readonly SerializerInterface $serializer,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('liquetsoft:fias:update_parallel_running')
            ->setDescription('Command for running one single thread of updating process')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stdIn = file_get_contents('php://stdin');
        if ($stdIn === false || $stdIn === '') {
            return 1;
        }

        $state = $this->serializer->deserialize($stdIn, State::class, 'json');

        $this->pipeline->run($state);

        return 0;
    }
}
