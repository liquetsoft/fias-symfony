<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Pipeline\State\ArrayState;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Команда, которая очищает хранилища для всех сущностей проекта, привязанных
 * к сущностям ФИАС.
 *
 * @internal
 */
final class TruncateCommand extends Command
{
    public function __construct(private readonly Task $truncateTask)
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
            ->setName('liquetsoft:fias:truncate')
            ->setDescription('Truncates storage for bound entities')
        ;
    }

    /**
     * {@inheritdoc}
     */
    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Truncating storage for bound entities');

        $state = new ArrayState();
        $this->truncateTask->run($state);

        $io->success('Storage truncated');

        return 0;
    }
}
