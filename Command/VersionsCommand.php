<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasInformer\FiasInformer;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Консольная команда, которая отображает текущую версию, полную версию
 * и список версий на обновление.
 */
class VersionsCommand extends Command
{
    protected static $defaultName = 'liquetsoft:fias:versions';

    private FiasInformer $informer;

    private VersionManager $versionManager;

    public function __construct(FiasInformer $informer, VersionManager $versionManager)
    {
        $this->informer = $informer;
        $this->versionManager = $versionManager;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription('Shows information about current version, delta versions and full version.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln('');

        $currentVersion = [$this->versionManager->getCurrentVersion()];
        $this->renderTable('Current version of FIAS', $currentVersion, $output);

        $output->writeln('');

        $completeVersion = [$this->informer->getCompleteInfo()];
        $this->renderTable('Complete version of FIAS', $completeVersion, $output);

        $output->writeln('');

        $deltaVersions = \array_slice($this->informer->getDeltaList(), 0, 15);
        $this->renderTable('Delta versions of FIAS', $deltaVersions, $output);

        $output->writeln('');

        return 0;
    }

    /**
     * Отображает список версий в виде таблицы.
     *
     * @param string             $header
     * @param InformerResponse[] $versions
     * @param OutputInterface    $output
     */
    private function renderTable(string $header, array $versions, OutputInterface $output): void
    {
        $rows = [];
        foreach ($versions as $version) {
            if (!$version->hasResult()) {
                continue;
            }
            $rows[] = [
                'Version' => $version->getVersion(),
                'Url' => $version->getUrl(),
            ];
        }

        $table = new Table($output);
        $table->setHeaderTitle($header);
        $table->setColumnWidths([10, 80]);
        $table->setHeaders(['Version', 'Url'])->setRows($rows);
        $table->render();
    }
}
