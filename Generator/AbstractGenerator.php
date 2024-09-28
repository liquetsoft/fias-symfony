<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Generator;

use Liquetsoft\Fias\Component\EntityRegistry\EntityRegistry;

/**
 * Абстрактный генератор.
 */
abstract class AbstractGenerator
{
    protected EntityRegistry $registry;

    public function __construct(EntityRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * Возвращает список всех сущностей ФИАС.
     */
    public function getRegistry(): EntityRegistry
    {
        return $this->registry;
    }

    /**
     * Проверяет, что каталог существует и доступен на запись.
     *
     * @throws \InvalidArgumentException
     */
    protected function checkDir(\SplFileInfo $dir): void
    {
        if (!$dir->isDir() || !$dir->isWritable()) {
            throw new \InvalidArgumentException(
                "Destination folder '" . $dir->getPathname() . "' isn't writable or doesn't exist."
            );
        }
    }

    /**
     * Приводит имена классов к единообразному виду.
     */
    protected function unifyClassName(string $name): string
    {
        $name = trim($name, " \t\n\r\0\x0B\\_");

        if (strpos($name, '_') !== false) {
            $arName = array_map('ucfirst', array_map('strtolower', explode('_', $name)));
            $res = implode('', $arName);
        } elseif (strtoupper($name) === $name) {
            $res = ucfirst(strtolower($name));
        } else {
            $res = $name;
        }

        return $res;
    }

    /**
     * Приводит пространсва имен к единообразному виду.
     */
    protected function unifyNamespace(string $namespace): string
    {
        return trim($namespace, " \t\n\r\0\x0B\\");
    }

    /**
     * Приводит имена колонок к единообразному виду.
     */
    protected function unifyColumnName(string $name): string
    {
        return trim(strtolower(str_replace('_', '', $name)));
    }
}
