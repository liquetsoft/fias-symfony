<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasInformer\FiasInformer;
use Liquetsoft\Fias\Component\FiasInformer\InformerResponse;
use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Консольная команда, которая принудительно задает номер текущей версии ФИАС.
 */
class VersionSetCommand extends Command
{
    protected static $defaultName = 'liquetsoft:fias:version_set';

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
        $this
            ->setDescription('Sets number of current version of FIAS.')
            ->addArgument('number', InputArgument::REQUIRED, 'Number of new version.')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $number = $this->getNumber($input);

        $io->note("Setting '{$number}' FIAS version number.");

        $version = $this->getVersion($number);
        $this->versionManager->setCurrentVersion($version);

        $io->success("'{$number}' FIAS version number set.");

        return 0;
    }

    /**
     * Получает номер версии из параметров запуска команды.
     */
    private function getNumber(InputInterface $input): int
    {
        $number = $input->getArgument('number');
        $number = \is_array($number) ? (int) reset($number) : (int) $number;
        if ($number <= 0) {
            $message = 'Version number must integer instance more than 0.';
            throw new \InvalidArgumentException($message);
        }

        return $number;
    }

    /**
     * Ищет указанную версию в списке на обновление и возвращает найденную.
     */
    private function getVersion(int $number): InformerResponse
    {
        $version = null;

        $deltaVersions = $this->informer->getDeltaList();
        foreach ($deltaVersions as $deltaVersion) {
            if ($deltaVersion->getVersion() === $number) {
                $version = $deltaVersion;
                break;
            }
        }

        if ($version === null) {
            $message = \sprintf("Can't find '%s' version in list of deltas.", $number);
            throw new \InvalidArgumentException($message);
        }

        return $version;
    }
}
