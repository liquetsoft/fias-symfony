<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Downloader\Downloader;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformer;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformerResponse;
use Liquetsoft\Fias\Component\Unpacker\Unpacker;
use Marvin255\FileSystemHelper\FileSystemFactory;
use Marvin255\FileSystemHelper\FileSystemHelperInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Консольная команда, которая загружает указанную версию ФИАС в указанную папку.
 *
 * @internal
 */
final class DownloadCommand extends Command
{
    private const LATEST_VERSION_NAME = 'latest';

    protected static string $defaultName = 'liquetsoft:fias:download';

    private readonly FileSystemHelperInterface $fs;

    public function __construct(
        private readonly Downloader $downloader,
        private readonly Unpacker $unpacker,
        private readonly FiasInformer $informer,
    ) {
        $this->fs = FileSystemFactory::create();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Downloads provided version of FIAS')
            ->addArgument('pathToDownload', InputArgument::REQUIRED, 'Path in local file system to download file')
            ->addArgument('version', InputArgument::OPTIONAL, 'Version number to download. "' . self::LATEST_VERSION_NAME . '" is for the latest version')
            ->addOption('extract', null, InputOption::VALUE_NONE, 'Extract data from the archive after downloading')
            ->addOption('delta', null, InputOption::VALUE_NONE, 'Download archive for delta between the provided version and the previous one')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $version = $this->getVersionArgument($input);
        $pathToDownload = $this->getPathArgument($input);
        $needExtraction = $this->getBooleanOptionValue($input, 'extract');
        $needDelta = $this->getBooleanOptionValue($input, 'delta');

        $version = $this->findVersion($version);
        $url = $needDelta ? $version->getDeltaUrl() : $version->getFullUrl();

        $io->note("Downloading '{$url}' to '{$pathToDownload->getPathname()}'");

        $this->downloader->download($url, $pathToDownload);

        if ($needExtraction) {
            $io->note("Extracting '{$pathToDownload->getPathname()}' to folder");
            $this->extract($pathToDownload);
        }

        $io->success('Downloading complete');

        return 0;
    }

    /**
     * Получает описание указанной версии ФИАС.
     */
    private function findVersion(string $version): FiasInformerResponse
    {
        if ($version === self::LATEST_VERSION_NAME) {
            return $this->informer->getLatestVersion();
        } else {
            $version = (int) $version;
            foreach ($this->informer->getAllVersions() as $allVersionsItem) {
                if ($allVersionsItem->getVersion() === $version) {
                    return $allVersionsItem;
                }
            }
        }

        throw new \RuntimeException("Can't find url for '{$version}' version");
    }

    /**
     * Распаковывает загруженный архив.
     */
    private function extract(\SplFileInfo $archive): void
    {
        $extractTo = $archive->getPath() . \DIRECTORY_SEPARATOR . $archive->getBasename('.zip');
        $extractTo = new \SplFileInfo($extractTo);

        $this->fs->mkdirIfNotExist($extractTo);
        $this->fs->emptyDir($extractTo);

        $this->unpacker->unpack($archive, $extractTo);

        unlink($archive->getRealPath());
    }

    /**
     * Получает значение аргумента с номером версии.
     */
    private function getVersionArgument(InputInterface $input): string
    {
        $version = $input->getArgument('version');

        if (\is_array($version)) {
            $version = reset($version);
        }

        if ($version === null) {
            return self::LATEST_VERSION_NAME;
        }

        return (string) $version;
    }

    /**
     * Получает значение аргумента с путем, по которому нужно сохранить файл.
     */
    private function getPathArgument(InputInterface $input): \SplFileInfo
    {
        $version = $this->getVersionArgument($input);

        $pathToDownload = $input->getArgument('pathToDownload');
        if (\is_array($pathToDownload)) {
            $pathToDownload = reset($pathToDownload);
        }

        $pathToDownload = (string) $pathToDownload;
        $pathToDownload = rtrim($pathToDownload, '/\\')
            . \DIRECTORY_SEPARATOR
            . 'fias_' . $version . '.zip';

        return new \SplFileInfo($pathToDownload);
    }

    /**
     * Получает значение флага по имени.
     */
    private function getBooleanOptionValue(InputInterface $input, string $name): bool
    {
        return (bool) $input->getOption($name);
    }
}
