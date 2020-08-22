<?php

declare(strict_types=1);

namespace Liquetsoft\Fias\Symfony\LiquetsoftFiasBundle\Tests;

use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

/**
 * Базовый класс для всех тестов.
 */
abstract class BaseCase extends TestCase
{
    private ?Generator $faker = null;

    /**
     * Возвращает объект php faker для генерации случайных данных.
     *
     * Использует ленивую инициализацию и создает объект faker только при первом
     * запросе, для всех последующих запросов возвращает тот же самый инстанс,
     * который был создан в первый раз.
     *
     * @return Generator
     */
    public function createFakeData(): Generator
    {
        if ($this->faker === null) {
            $this->faker = Factory::create();
        }

        return $this->faker;
    }
}
