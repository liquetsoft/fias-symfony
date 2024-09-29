<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasStatusChecker\FiasStatusChecker;
use Liquetsoft\Fias\Component\FiasStatusChecker\FiasStatusCheckerResult;
use Liquetsoft\Fias\Component\FiasStatusChecker\FiasStatusCheckerStatus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Консольная команда, которая отображает текущий статус всех сервисов ФИАС.
 *
 * @internal
 */
final class StatusCheckCommand extends Command
{
    public function __construct(private readonly FiasStatusChecker $checker)
    {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('liquetsoft:fias:status')
            ->setDescription('Shows information about current status of FIAS services')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $status = $this->checker->check();
        if ($status->getResultStatus() === FiasStatusCheckerStatus::AVAILABLE) {
            $io->success('FIAS is OK and available');
        } else {
            $io->error('FIAS is not available');
            $table = new Table($output);
            $table->setColumnWidths([15, 15, 60]);
            $table->setHeaders(['Service', 'Status', 'Reason'])->setRows($this->convertStatusToTableBody($status));
            $table->render();
        }

        return 0;
    }

    /**
     * Конвертирует массив статусов в массив строк для таблицы.
     *
     * @return array<int, array<int, string>>
     */
    private function convertStatusToTableBody(FiasStatusCheckerResult $status): array
    {
        $tableBody = [];

        foreach ($status->getPerServiceStatuses() as $serviceStatus) {
            $tableBody[] = [
                $serviceStatus->getService()->value,
                $serviceStatus->getStatus()->value,
                $serviceStatus->getReason(),
            ];
        }

        return $tableBody;
    }
}
