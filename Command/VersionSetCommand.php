<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\FiasInformer\FiasInformer;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformerResponse;
use Liquetsoft\Fias\Component\VersionManager\VersionManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Консольная команда, которая принудительно задает номер текущей версии ФИАС.
 *
 * @internal
 */
final class VersionSetCommand extends Command
{
    protected static string $defaultName = 'liquetsoft:fias:version_set';

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
        $this
            ->setDescription('Force current version of FIAS with provided version')
            ->addArgument('number', InputArgument::REQUIRED, 'New version')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $number = $this->getNumber($input);

        $io->note("Setting '{$number}' FIAS version");

        $version = $this->getVersion($number);
        $this->versionManager->setCurrentVersion($version);

        $io->success("'{$number}' FIAS version set");

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
            throw new \InvalidArgumentException('Version number must integer instance more than 0');
        }

        return $number;
    }

    /**
     * Ищет указанную версию в списке на обновление и возвращает найденную.
     */
    private function getVersion(int $number): FiasInformerResponse
    {
        foreach ($this->informer->getAllVersions() as $allVersionsItem) {
            if ($allVersionsItem->getVersion() === $number) {
                return $allVersionsItem;
            }
        }

        throw new \InvalidArgumentException("Can't find '{$number}' version");
    }
}
