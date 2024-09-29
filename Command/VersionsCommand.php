<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasInformer\FiasInformer;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformerResponse;
use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Консольная команда, которая отображает текущую версию, полную версию
 * и список версий на обновление.
 *
 * @internal
 */
final class VersionsCommand extends Command
{
    protected static string $defaultName = 'liquetsoft:fias:versions';

    public function __construct(
        private readonly FiasInformer $informer,
        private readonly VersionManager $versionManager,
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Shows information about current version, delta versions and full version');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');

        $currentVersion = $this->versionManager->getCurrentVersion();
        if ($currentVersion !== null) {
            $this->renderTable('Current version of FIAS', [$currentVersion], $output);
        }

        $output->writeln('');

        $completeVersion = [
            $this->informer->getLatestVersion(),
        ];
        $this->renderTable('Complete version of FIAS', $completeVersion, $output);

        $output->writeln('');

        $deltaVersions = \array_slice($this->informer->getAllVersions(), 0, 15);
        $this->renderTable('Delta versions of FIAS', $deltaVersions, $output);

        $output->writeln('');

        return 0;
    }

    /**
     * Отображает список версий в виде таблицы.
     *
     * @param FiasInformerResponse[] $versions
     */
    private function renderTable(string $header, array $versions, OutputInterface $output): void
    {
        $rows = [];
        foreach ($versions as $version) {
            $rows[] = [
                'Version' => $version->getVersion(),
                'Full url' => $version->getFullUrl(),
                'Delta url' => $version->getDeltaUrl(),
            ];
        }

        $table = new Table($output);
        $table->setHeaderTitle($header);
        $table->setColumnWidths([10, 80, 80]);
        $table->setHeaders(['Version', 'Full url', 'Delta url'])->setRows($rows);
        $table->render();
    }
}
