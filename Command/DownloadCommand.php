<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Command;

use Liquetsoft\Fias\Component\Downloader\Downloader;
use Liquetsoft\Fias\Component\FiasInformer\FiasInformer;
use Liquetsoft\Fias\Component\Unpacker\Unpacker;
use Marvin255\FileSystemHelper\FileSystemException;
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
 */
class DownloadCommand extends Command
{
    private const FULL_VERSION_NAME = 'full';

    protected static $defaultName = 'liquetsoft:fias:download';

    private Downloader $downloader;

    private Unpacker $unpacker;

    private FiasInformer $informer;

    private FileSystemHelperInterface $fs;

    public function __construct(
        Downloader $downloader,
        Unpacker $unpacker,
        FiasInformer $informer
    ) {
        $this->downloader = $downloader;
        $this->unpacker = $unpacker;
        $this->informer = $informer;
        $this->fs = FileSystemFactory::create();

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Downloads set version of FIAS.')
            ->addArgument('pathToDownload', InputArgument::REQUIRED, 'Path in local file system to download file.')
            ->addArgument('version', InputArgument::OPTIONAL, 'Version number to download. "full" is for full version.')
            ->addOption('extract', null, InputOption::VALUE_NONE, 'Extract archive after downloading')
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
        $needExtraction = $this->getExtractionOption($input);

        $url = $this->findUrlForVersion($version);

        $io->note("Downloading '{$url}' to '{$pathToDownload->getPathname()}'.");

        $this->downloader->download($url, $pathToDownload);

        if ($needExtraction) {
            $io->note("Extracting '{$pathToDownload->getPathname()}' to folder.");
            $this->extract($pathToDownload);
        }

        $io->success('Downloading complete.');

        return 0;
    }

    /**
     * Получает url для указанной версии.
     *
     * @param string $version
     *
     * @return string
     */
    private function findUrlForVersion(string $version): string
    {
        $url = '';

        if ($version === self::FULL_VERSION_NAME) {
            $url = $this->informer->getCompleteInfo()->getUrl();
        } else {
            $allVersions = $this->informer->getDeltaList();
            $version = (int) $version;
            foreach ($allVersions as $deltaVervion) {
                if ($deltaVervion->getVersion() === $version) {
                    $url = $deltaVervion->getUrl();
                    break;
                }
            }
        }

        if (empty($url)) {
            $message = sprintf("Can't find url for '%s' version.", $version);
            throw new \RuntimeException($message);
        }

        return $url;
    }

    /**
     * Распаковывает загруженный архив.
     *
     * @param \SplFileInfo $archive
     *
     * @throws FileSystemException
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
     *
     * @param InputInterface $input
     *
     * @return string
     */
    private function getVersionArgument(InputInterface $input): string
    {
        $version = $input->getArgument('version');

        if (\is_array($version)) {
            $version = reset($version);
        } elseif ($version === null) {
            $version = self::FULL_VERSION_NAME;
        }

        return (string) $version;
    }

    /**
     * Получает значение аргумента с путем, по которому нужно сохранить файл.
     *
     * @param InputInterface $input
     *
     * @return \SplFileInfo
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
     * Получает значение флага нужно ли распаковывать архив после загрузки.
     *
     * @param InputInterface $input
     *
     * @return bool
     */
    private function getExtractionOption(InputInterface $input): bool
    {
        $needExtraction = $input->getOption('extract');

        if ($needExtraction === null) {
            $needExtraction = false;
        }

        return (bool) $needExtraction;
    }
}
