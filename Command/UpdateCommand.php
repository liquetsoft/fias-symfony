<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Component\Pipeline\Pipe\Pipe;
use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\State\StateParameter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая обновляет ФИАС с текущей версии до самой свежей.
 */
class UpdateCommand extends Command
{
    protected static $defaultName = 'liquetsoft:fias:update';

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
        $this->setDescription('Updates FIAS to latest version.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Updating FIAS.');
        $start = microtime(true);

        do {
            $state = new ArrayState();
            $this->pipeline->run($state);

            $info = $state->getParameter(StateParameter::FIAS_INFO);
            if (!($info instanceof InformerResponse)) {
                throw new \RuntimeException(
                    "There is no '" . StateParameter::FIAS_INFO . "' parameter in state."
                );
            }

            if ($info->hasResult()) {
                $io->note("Updated to version '{$info->getVersion()}'.");
            }
        } while ($info->hasResult());

        $total = round(microtime(true) - $start, 4);
        $io->success("FIAS updated after {$total} s.");

        return 0;
    }
}
