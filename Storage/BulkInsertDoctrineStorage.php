<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\DBAL\Exception as DBALException;
use Doctrine\ORM\EntityManager;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 *
 * В отличие от базового объекта использует для вставки bulk (множественный) insert.
 * Следует понимать, что события и другие фичи Doctrine, связанные с сущностями
 * при такой вставке не работают. Кроме того, данная реализация подходит не для всех СУБД.
 */
final class BulkInsertDoctrineStorage extends DoctrineStorage
{
    /**
     * Сохраненные в памяти данные для множественной вставки.
     *
     * Массив вида 'имя таблицы' => 'массив массивов данных для вставки'.
     *
     * @var array<string, array<int, array<string, mixed>>>
     */
    private array $insertData = [];

    public function __construct(
        EntityManager $em,
        int $batchCount = 1000,
        private readonly ?LoggerInterface $logger = null,
    ) {
        parent::__construct($em, $batchCount);
    }

    /**
     * {@inheritdoc}
     */
    public function stop(): void
    {
        parent::stop();
        $this->checkAndFlushInsert(true);
    }

    /**
     * {@inheritdoc}
     */
    public function insert(object $entity): void
    {
        try {
            $meta = $this->getEntityMeta($entity);

            $table = $meta->getTableName();
            $fields = $meta->getFieldNames();

            $insertArray = [];
            foreach ($fields as $field) {
                $value = $this->convertToPrimitive($meta->getFieldValue($entity, $field));
                $column = $meta->getColumnName($field);
                $insertArray[$column] = $value;
            }

            $this->insertData[$table][] = $insertArray;
            $this->checkAndFlushInsert(false);
        } catch (\Throwable $e) {
            throw new StorageException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Проверяет нужно ли отправлять запросы на множественные вставки элементов,
     * сохраненых в памяти.
     */
    private function checkAndFlushInsert(bool $force = false): void
    {
        foreach ($this->insertData as $tableName => $insertData) {
            if ($force || \count($insertData) >= $this->batchCount) {
                $this->bulkInsert($tableName, $insertData);
                unset($this->insertData[$tableName]);
            }
        }
    }

    /**
     * Отправляет запрос на массовую вставку данных в таблицу.
     *
     * @param array<int, array<string, mixed>> $data
     */
    private function bulkInsert(string $tableName, array $data): void
    {
        try {
            $this->prepareAndRunBulkInsert($tableName, $data);
        } catch (DBALException $e) {
            $this->prepareAndRunBulkSafely($tableName, $data);
        }
    }

    /**
     * В случае исключения при множественной вставке, пробуем вставку по одной
     * записи, чтобы не откатывать весь блок записей.
     *
     * Только для некоторых случаев:
     *    - повторяющийся первичный ключ
     *
     * @param array<int, array<string, mixed>> $data
     */
    private function prepareAndRunBulkSafely(string $tableName, array $data): void
    {
        foreach ($data as $item) {
            try {
                $this->prepareAndRunBulkInsert($tableName, [$item]);
            } catch (\Exception $e) {
                $this->log(
                    LogLevel::ERROR,
                    "Error while inserting item to '{$tableName}' table. Item wasn't proceed.",
                    [
                        'table' => $tableName,
                        'error_message' => $e->getMessage(),
                        'item' => $data,
                    ]
                );
            }
        }
    }

    /**
     * Непосредственное создание и запуск запроса на исполнение.
     *
     * @param array<int, array<string, mixed>> $data
     */
    private function prepareAndRunBulkInsert(string $tableName, array $data): void
    {
        $dataSample = reset($data);

        if ($dataSample !== false) {
            $paramNames = implode(', ', array_map([$this->em->getConnection(), 'quoteIdentifier'], array_keys($dataSample)));
            $paramValues = implode(', ', array_fill(0, \count($dataSample), '?'));
            $dataValues = '(' . implode('), (', array_fill(0, \count($data), $paramValues)) . ')';
            $sql = "INSERT INTO {$tableName} ({$paramNames}) VALUES {$dataValues}";

            $stmt = $this->em->getConnection()->prepare($sql);
            $count = 0;
            foreach ($data as $item) {
                foreach ($item as $value) {
                    $stmt->bindValue(++$count, $value);
                }
            }

            $stmt->executeStatement();
        }
    }

    /**
     * Запись сообщения в лог.
     */
    private function log(string $errorLevel, string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->log($errorLevel, $message, $context);
        }
    }
}
