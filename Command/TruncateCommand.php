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
 */
class TruncateCommand extends Command
{
    protected static $defaultName = 'liquetsoft:fias:truncate';

    protected Task $truncateTask;

    public function __construct(Task $truncateTask)
    {
        $this->truncateTask = $truncateTask;

        parent::__construct();
    }

    /**
     * @inheritdoc
     */
    protected function configure(): void
    {
        $this->setDescription('Truncates storage for binded entities.');
    }

    /**
     * @inheritdoc
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->note('Truncating storage for binded entities.');

        $state = new ArrayState();
        $this->truncateTask->run($state);

        $io->success('Storage truncated.');

        return 0;
    }
}
