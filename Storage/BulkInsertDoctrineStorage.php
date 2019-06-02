<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Storage;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Ramsey\Uuid\UuidInterface;
use DateTimeInterface;
use RuntimeException;
use Exception;

/**
 * Объект, который сохраняет данные ФИАС с помощью Doctrine.
 *
 * В отличие от базового объекта использует для вставки bulk (множественный) insert.
 * Следует понимать, что события и другие фичи Doctrine, связанные с сущностями
 * при такой вставке не работают. Кроме того, данная реализация подходит не для всех СУБД.
 */
class BulkInsertDoctrineStorage extends DoctrineStorage
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Сохраненные в памяти данные для мнодественной вставки.
     *
     * Массив вида 'имя таблицы' => 'массив массивов данных для вставки'.
     *
     * @var mixed[]
     */
    protected $insertData = [];

    /**
     * @param ManagerRegistry $doctrine
     * @param int             $insertBatch
     */
    public function __construct(ManagerRegistry $doctrine, int $insertBatch = 1000)
    {
        $em = $doctrine->getManager();
        if (!($em instanceof EntityManager)) {
            throw new RuntimeException(
                "Bulk insert can only be used with '" . EntityManager::class . "'"
            );
        }
        $this->em = $em;

        $this->insertBatch = $insertBatch;
    }

    /**
     * @inheritdoc
     */
    public function stop(): void
    {
        parent::stop();
        $this->checkAndFlushInsert(true);
    }

    /**
     * @inheritdoc
     */
    public function insert(object $entity): void
    {
        $meta = $this->em->getClassMetadata(get_class($entity));

        $table = $meta->getTableName();
        $fileds = $meta->getFieldNames();

        $insertArray = [];
        foreach ($fileds as $field) {
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
            if ($forceInsert || count($insertData) >= $this->insertBatch) {
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
        } catch (UniqueConstraintViolationException $e) {
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
     * @param string  $table
     * @param mixed[] $data
     */
    protected function prepareAndRunBulkSafely(string $tableName, array $data): void
    {
        foreach ($data as $item) {
            try {
                $this->prepareAndRunBulkInsert($tableName, [$item]);
            } catch (Exception $e) {
                //@TODO залогировать исключение
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
     */
    protected function prepareAndRunBulkInsert(string $tableName, array $data): void
    {
        $dataSample = reset($data);

        $paramNames = implode(', ', array_keys($dataSample));
        $paramValues = implode(', ', array_fill(0, count($dataSample), '?'));
        $dataValues = '(' . implode('), (', array_fill(0, count($data), $paramValues)) . ')';
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
}
