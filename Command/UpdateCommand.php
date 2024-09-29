<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\State\StateParameter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая обновляет ФИАС с текущей версии до самой свежей.
 *
 * @internal
 */
final class UpdateCommand extends Command
{
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
            ->setName('liquetsoft:fias:update')
            ->setDescription('Updates FIAS to the latest version')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Updating FIAS');
        $start = microtime(true);

        do {
            $state = new ArrayState();
            try {
                $this->pipeline->run($state);
            } catch (\Throwable $e) {
                throw new \RuntimeException(
                    message: "Something went wrong during the updating. Please check the Laravel's log to get more information",
                    previous: $e
                );
            }
            $newVersion = $state->getParameterString(StateParameter::FIAS_NEXT_VERSION_NUMBER);
            if ($newVersion !== '') {
                $io->note("Updated to version '{$newVersion}'");
            }
        } while ($newVersion !== '');

        $total = round(microtime(true) - $start, 4);
        $io->note("FIAS updated after {$total} s.");

        return 0;
    }
}
