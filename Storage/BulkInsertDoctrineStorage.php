<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use DateTimeInterface;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Exception;
use Liquetsoft\Fias\Component\Exception\StorageException;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Ramsey\Uuid\UuidInterface;
use RuntimeException;
use Throwable;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 *
 * В отличие от базового объекта использует для вставки bulk (множественный) insert.
 * Следует понимать, что события и другие фичи Doctrine, связанные с сущностями
 * при такой вставке не работают. Кроме того, данная реализация подходит не для всех СУБД.
 */
class BulkInsertDoctrineStorage extends DoctrineStorage
{
    protected ?LoggerInterface $logger;

    /**
     * Сохраненные в памяти данные для множественной вставки.
     *
     * Массив вида 'имя таблицы' => 'массив массивов данных для вставки'.
     *
     * @var mixed[]
     */
    protected array $insertData = [];

    /**
     * @param EntityManager        $em
     * @param int                  $insertBatch
     * @param LoggerInterface|null $logger
     */
    public function __construct(EntityManager $em, int $insertBatch = 1000, ?LoggerInterface $logger = null)
    {
        parent::__construct($em, $insertBatch);
        $this->logger = $logger;
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
            $meta = $this->em->getClassMetadata(\get_class($entity));

            $table = $meta->getTableName();
            $fields = $meta->getFieldNames();

            $insertArray = [];
            foreach ($fields as $field) {
                $value = $meta->getFieldValue($entity, $field);
                if ($value instanceof DateTimeInterface) {
                    $value = $value->format('Y-m-d H:i:s');
                } elseif ($value instanceof UuidInterface) {
                    $value = $value->toString();
                }
                $column = $meta->getColumnName($field);
                $insertArray[$column] = $value;
            }

            $this->insertData[$table][] = $insertArray;
            $this->checkAndFlushInsert(false);
        } catch (Throwable $e) {
            throw new StorageException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Проверяет нужно ли отправлять запросы на множественные вставки элементов,
     * сохраненых в памяти.
     *
     * @param bool $forceInsert
     */
    protected function checkAndFlushInsert(bool $forceInsert = false): void
    {
        foreach ($this->insertData as $tableName => $insertData) {
            if ($forceInsert || \count($insertData) >= $this->insertBatch) {
                $this->bulkInsert($tableName, $insertData);
                unset($this->insertData[$tableName]);
            }
        }
    }

    /**
     * Отправляет запрос на массовую вставку данных в таблицу.
     *
     * @param string  $tableName
     * @param mixed[] $data
     *
     * @throws RuntimeException
     */
    protected function bulkInsert(string $tableName, array $data): void
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
     * @param string  $tableName
     * @param mixed[] $data
     */
    protected function prepareAndRunBulkSafely(string $tableName, array $data): void
    {
        foreach ($data as $item) {
            try {
                $this->prepareAndRunBulkInsert($tableName, [$item]);
            } catch (Exception $e) {
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
     * @param string  $tableName
     * @param mixed[] $data
     *
     * @throws RuntimeException
     * @throws DBALException
     */
    protected function prepareAndRunBulkInsert(string $tableName, array $data): void
    {
        $dataSample = reset($data);

        $paramNames = implode(', ', array_keys($dataSample));
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

        $stmt->execute();
    }

    /**
     * Запись сообщения в лог.
     *
     * @param string $errorLevel
     * @param string $message
     * @param array  $context
     */
    protected function log(string $errorLevel, string $message, array $context = []): void
    {
        if ($this->logger) {
            $this->logger->log($errorLevel, $message, $context);
        }
    }
}
