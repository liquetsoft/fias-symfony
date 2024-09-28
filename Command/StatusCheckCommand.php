<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasStatusChecker\FiasStatusChecker;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Консольная команда, которая отображает текущий статус всех сервисов ФИАС.
 */
final class StatusCheckCommand extends Command
{
    protected static string $defaultName = 'liquetsoft:fias:status';

    public function __construct(private readonly FiasStatusChecker $checker)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Shows information about current status of FIAS services.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $status = $this->checker->check();
        if ($status->getResultStatus() === FiasStatusChecker::STATUS_AVAILABLE) {
            $io->success('FIAS is OK and available.');
        } else {
            $io->error('FIAS is not available.');
            $table = new Table($output);
            $table->setColumnWidths([15, 15, 60]);
            $table->setHeaders(['Service', 'Status', 'Reason'])->setRows($status->getPerServiceStatuses());
            $table->render();
        }

        return 0;
    }
}
