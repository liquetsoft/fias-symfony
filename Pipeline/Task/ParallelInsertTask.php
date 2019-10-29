<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Pipeline\Task;

use Liquetsoft\Fias\Component\EntityDescriptor\EntityDescriptor;
use Liquetsoft\Fias\Component\Parallel\Pool;
use Liquetsoft\Fias\Component\Pipeline\State\State;
use Liquetsoft\Fias\Component\Pipeline\Task\Task;
use Liquetsoft\Fias\Component\Parallel\ParallelTask;
use Liquetsoft\Fias\Component\EntityManager\EntityManager;
use SplFileInfo;
use Closure;

/**
 * Задача для загрузки данных из xml в базу данных в несколько потоков.
 */
class ParallelInsertTask implements Task
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Pool
     */
    protected $parallelTasksPool;

    /**
     * @var string
     */
    protected $kernelClass;

    /**
     * @param EntityManager $entityManager
     * @param Pool          $parallelTasksPool
     * @param string        $kernelClass
     */
    public function __construct(EntityManager $entityManager, Pool $parallelTasksPool, string $kernelClass = '\\App\\Kernel')
    {
        $this->entityManager = $entityManager;
        $this->parallelTasksPool = $parallelTasksPool;
        $this->kernelClass = $kernelClass;
    }

    /**
     * @inheritDoc
     */
    public function run(State $state): void
    {
        $filesToInsert = $this->getFileNamesFromState($state, Task::FILES_TO_INSERT_PARAM);
        $filesToDelete = $this->getFileNamesFromState($state, Task::FILES_TO_DELETE_PARAM);
        $filesByThreads = $this->groupFilesByThreads($filesToInsert, $filesToDelete);

        foreach ($filesByThreads as $threadNumber => $threadFiles) {
            $threadParams = $threadFiles;
            $threadParams['kernel'] = $this->kernelClass;

            $task = new ParallelTask($this->createTaskCallback(), $threadParams, $threadNumber);

            $this->parallelTasksPool->addTask($task);
        }

        $this->parallelTasksPool->run();
    }

    /**
     * Создает коллбэк для таска.
     *
     * @return Closure
     */
    protected function createTaskCallback(): Closure
    {
        return function (array $params) {
            (new ParallelInsertCallback($params['kernel'] ?? '', $params))->run();
        };
    }

    /**
     * Формирует список файлов для загрузки и удаления, сгруппированный по тредам.
     *
     * @param string[] $filesToInsert
     * @param string[] $filesToDelete
     *
     * @return array
     */
    protected function groupFilesByThreads(array $filesToInsert, array $filesToDelete): array
    {
        $filesByThreads = [];

        $threadNumbersForEntities = $this->getThreadsNumbersForEntities();
        foreach ($threadNumbersForEntities as $entityName => $threadNumber) {
            if (isset($filesToInsert[$entityName])) {
                $filesByThreads[$threadNumber][Task::FILES_TO_INSERT_PARAM][] = $filesToInsert[$entityName];
                unset($filesToInsert[$entityName]);
            }
            if (isset($filesToDelete[$entityName])) {
                $filesByThreads[$threadNumber][Task::FILES_TO_DELETE_PARAM][] = $filesToDelete[$entityName];
                unset($filesToDelete[$entityName]);
            }
        }

        $filesByThreads = array_values($filesByThreads);
        $maxThreadNumber = count($filesByThreads) - 1;
        $currentThreadNumber = 0;
        foreach ($filesToInsert as $entityName => $fileName) {
            $filesByThreads[$currentThreadNumber][Task::FILES_TO_INSERT_PARAM][] = $fileName;
            if (isset($filesToDelete[$entityName])) {
                $filesByThreads[$currentThreadNumber][Task::FILES_TO_DELETE_PARAM][] = $filesToDelete[$entityName];
            }
            ++$currentThreadNumber;
            if ($currentThreadNumber > $maxThreadNumber) {
                $currentThreadNumber = 0;
            }
        }

        return $filesByThreads;
    }

    /**
     * Получает из объекта состояния список файлов для загрузки данных.
     *
     * @param State  $state
     * @param string $paramName
     *
     * @return array
     */
    protected function getFileNamesFromState(State $state, string $paramName): array
    {
        $fileNamesByEntities = [];

        $fileNames = $state->getParameter($paramName);
        $fileNames = is_array($fileNames) ? $fileNames : [];
        foreach ($fileNames as $fileName) {
            $descriptor = $this->getDescriptor($fileName, $paramName);
            if ($descriptor) {
                $fileNamesByEntities[$descriptor->getName()] = $fileName;
            }
        }

        return $fileNamesByEntities;
    }

    /**
     * Возвращает дескриптора для файла по имени файла и типу параметра.
     *
     * @param string $fileName
     * @param string $paramName
     *
     * @return EntityDescriptor|null
     */
    protected function getDescriptor(string $fileName, string $paramName): ?EntityDescriptor
    {
        $descriptor = null;
        $fileInfo = new SplFileInfo($fileName);

        if ($paramName === Task::FILES_TO_INSERT_PARAM) {
            $descriptor = $this->entityManager->getDescriptorByInsertFile($fileInfo->getFilename());
        } elseif ($paramName === Task::FILES_TO_DELETE_PARAM) {
            $descriptor = $this->entityManager->getDescriptorByDeleteFile($fileInfo->getFilename());
        }

        return $descriptor;
    }

    /**
     * Возвращает массив, в котором ключом являются имена сущностей, а значением - номер потока, для исполнения.
     *
     * @return array<string, int>
     */
    protected function getThreadsNumbersForEntities(): array
    {
        return [
            'AddressObject' => 0,
            'House' => 1,
            'Stead' => 2,
            'Room' => 3,
            'NormativeDocument' => 4,
        ];
    }
}
