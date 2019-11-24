<?php

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Pipeline\Task;

use Liquetsoft\Fias\Component\FilesDispatcher\FilesDispatcher;
use Liquetsoft\Fias\Component\Pipeline\State\State;
use Liquetsoft\Fias\Component\Pipeline\Task\LoggableTask;
use Liquetsoft\Fias\Component\Pipeline\Task\LoggableTaskTrait;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Psr\Log\LogLevel;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

/**
 * Задача, которая распределяет файлы в обработку для symfony/process.
 */
class SymfonyProcessSwitchTask implements Task, LoggableTask
{
    use LoggableTaskTrait;

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
        int $numberOfParallel = 5
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
     * Запускает все процессы и обрабатывает результат.
     *
     * @param Process[] $processes
     */
    protected function runProcesses(array $processes): void
    {
        $this->startProcesses($processes);
        $this->log(LogLevel::INFO, 'All process started.');
        $this->waitTillProcessesComplete($processes);
        $this->log(LogLevel::INFO, 'All process completed.');
        $this->handleProcessesResults($processes);
    }

    /**
     * Запускает все процессы асинхронно.
     *
     * @param Process[] $processes
     */
    protected function startProcesses(array $processes): void
    {
        foreach ($processes as $process) {
            $process->disableOutput();
            $process->setTimeout(null);
            $process->start();
        }
    }

    /**
     * Цикл, который ждет завершения всех процессов.
     *
     * @param Process[] $processes
     */
    protected function waitTillProcessesComplete(array $processes): void
    {
        do {
            sleep(1);
            $isProcessesFinished = true;
            foreach ($processes as $process) {
                if ($process->isRunning()) {
                    $isProcessesFinished = false;
                    break;
                }
            }
        } while (!$isProcessesFinished);
    }

    /**
     * Обрабатывает результаты всех процессов.
     *
     * @param Process[] $processes
     */
    protected function handleProcessesResults(array $processes): void
    {
        foreach ($processes as $process) {
            if (!$process->isSuccessful()) {
                $this->log(
                    LogLevel::ERROR,
                    'Process complete with error: ' . $process->getErrorOutput()
                );
            }
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

        $this->log(LogLevel::INFO, 'Creating new process.', [
            'insert_files' => $dispatchedInsert,
            'delete_files' => $dispatchedDelete,
            'path_to_php' => $phpBinaryPath,
            'path_to_bin' => $this->pathToBin,
            'command' => $this->commandName,
        ]);

        return new Process([
            $phpBinaryPath,
            $this->pathToBin,
            $this->commandName,
            json_encode($dispatchedInsert),
            json_encode($dispatchedDelete),
        ]);
    }
}
