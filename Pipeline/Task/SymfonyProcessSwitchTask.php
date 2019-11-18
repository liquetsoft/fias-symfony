<?php

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Pipeline\Task;

use Liquetsoft\Fias\Component\FilesDispatcher\FilesDispatcher;
use Liquetsoft\Fias\Component\Pipeline\State\State;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Задача, которая распределяет файлы в обработку для symfony/process.
 */
class SymfonyProcessSwitchTask implements Task
{
    /**
     * @var FilesDispatcher
     */
    protected $filesDispatcher;

    /**
     * @var string
     */
    protected $pathToBin;

    /**
     * @var string
     */
    protected $commandName;

    /**
     * @var int
     */
    protected $numberOfParallel;

    /**
     * @param FilesDispatcher $filesDispatcher
     * @param string          $pathToBin
     * @param string          $commandName
     * @param int             $numberOfParallel
     */
    public function __construct(
        FilesDispatcher $filesDispatcher,
        string $pathToBin,
        string $commandName,
        int $numberOfParallel = 6
    ) {
        $this->filesDispatcher = $filesDispatcher;
        $this->pathToBin = $pathToBin;
        $this->commandName = $commandName;
        $this->numberOfParallel = $numberOfParallel;
    }

    /**
     * @inheritDoc
     */
    public function run(State $state): void
    {
        $filesToInsert = $state->getParameter(Task::FILES_TO_INSERT_PARAM);
        $filesToInsert = is_array($filesToInsert) ? $filesToInsert : [];
        $dispatchedInsert = $this->filesDispatcher->dispatchInsert($filesToInsert, $this->numberOfParallel);

        $filesToDelete = $state->getParameter(Task::FILES_TO_DELETE_PARAM);
        $filesToDelete = is_array($filesToDelete) ? $filesToDelete : [];
        $dispatchedDelete = $this->filesDispatcher->dispatchDelete($filesToDelete, $this->numberOfParallel);

        $processes = $this->createProcessesList($dispatchedInsert, $dispatchedDelete);
        $this->runProcesses($processes);
    }

    /**
     * @param Process[] $processes
     */
    protected function runProcesses(array $processes): void
    {
        foreach ($processes as $process) {
            $process->disableOutput();
            $process->start();
        }
    }

    /**
     * Создает список процессов для параллельного запуска.
     *
     * @param string[][] $dispatchedInsert
     * @param string[][] $dispatchedDelete
     *
     * @return Process[]
     */
    protected function createProcessesList(array $dispatchedInsert, array $dispatchedDelete): array
    {
        $processes = [];

        for ($i = 0; $i < $this->numberOfParallel; ++$i) {
            $dispatchedInsertForProcess = $dispatchedInsert[$i] ?? [];
            $dispatchedDeleteForProcess = $dispatchedDelete[$i] ?? [];
            if ($dispatchedInsertForProcess || $dispatchedDeleteForProcess) {
                $processes[] = $this->createProcess($dispatchedInsertForProcess, $dispatchedDeleteForProcess);
            }
        }

        return $processes;
    }

    /**
     * Создает новый процесс для списка файлов.
     *
     * @param string[] $dispatchedInsert
     * @param string[] $dispatchedDelete
     *
     * @return Process
     */
    protected function createProcess(array $dispatchedInsert, array $dispatchedDelete): Process
    {
        $phpBinaryFinder = new PhpExecutableFinder();
        $phpBinaryPath = $phpBinaryFinder->find();

        return new Process([
            $phpBinaryPath,
            $this->pathToBin,
            $this->commandName,
            json_encode($dispatchedInsert),
            json_encode($dispatchedDelete),
        ]);
    }
}
